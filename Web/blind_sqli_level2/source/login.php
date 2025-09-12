<?php
session_start(); // Start session first

$message = ""; // Store messages to display later

// Basic method and size checks to deter automation
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 1024) {
    $_SESSION['login_message'] = "Sai email hoặc mật khẩu!";
    header("Location: ./loginPage.php");
    exit();
} else {
    // Suppress error reporting for blind SQLi
    mysqli_report(MYSQLI_REPORT_OFF);
    
    // Optional same-origin checks (lenient: only if present)
    if (isset($_SERVER['HTTP_ORIGIN']) && stripos($_SERVER['HTTP_ORIGIN'], $_SERVER['HTTP_HOST']) === false) {
        $_SESSION['login_message'] = "Sai email hoặc mật khẩu!";
        header("Location: ./loginPage.php");
        exit();
    }

    // CSRF token validation (30s expiry, one-time)
    $csrfSession = $_SESSION['csrf'] ?? null;
    $csrfForm = $_POST['csrf'] ?? '';
    if (!$csrfSession || $csrfSession['used'] || !is_string($csrfForm) || strlen($csrfForm) !== 32 || !hash_equals($csrfSession['v'], $csrfForm) || (time() - ($csrfSession['t'] ?? 0)) > 30) {
        $_SESSION['login_message'] = "Sai email hoặc mật khẩu!";
        header("Location: ./loginPage.php");
        exit();
    }
    // Mark token as used to enforce one-time submission
    $_SESSION['csrf']['used'] = true;

    // Dynamic field names and honeypot
    $fields = $_SESSION['fields'] ?? null;
    if (!$fields || !isset($fields['email'], $fields['pass'], $fields['hp'])) {
        $_SESSION['login_message'] = "Sai email hoặc mật khẩu!";
        header("Location: ./loginPage.php");
        exit();
    }
    $email = $_POST[$fields['email']] ?? '';
    $password = $_POST[$fields['pass']] ?? '';
    $honeypot = $_POST[$fields['hp']] ?? '';
    // Clear mapping so it must be fetched again
    unset($_SESSION['fields']);
    if (!empty($honeypot)) {
        // Bot detected: generic deny
        $_SESSION['login_message'] = "Sai email hoặc mật khẩu!";
        header("Location: ./loginPage.php");
        exit();
    }
    // Tight length caps
    if (!is_string($email) || !is_string($password) || strlen($email) > 100 || strlen($password) > 100) {
        $_SESSION['login_message'] = "Sai email hoặc mật khẩu!";
        header("Location: ./loginPage.php");
        exit();
    }

    //block list
    $blocklist = ['sleep','union','order','if(','substr','ascii','--'];
    foreach ($blocklist as $bad) {
        if (stripos($email, $bad) !== false || stripos($password, $bad) !== false) {
            $_SESSION['login_message'] = "Sai email hoặc mật khẩu!";
            header("Location: ./loginPage.php");
            exit();
        }
    }

    // Fixed minimum latency to reduce timing signal (pad to ~200ms)
    $start = microtime(true);

    // rate limiting: 3 attempts per 30s (session) + 10 per 5m (IP)
    $limit = 3; $window = 30; // session-scoped
    $ipLimit = 20; $ipWindow = 300; // IP-scoped
    if (!isset($_SESSION['attempts']) || !isset($_SESSION['first_attempt_time'])) {
        $_SESSION['attempts'] = 0;
        $_SESSION['first_attempt_time'] = time();
    }

    // Reset the session window if it has expired
    if ((time() - $_SESSION['first_attempt_time']) >= $window) {
        $_SESSION['attempts'] = 0;
        $_SESSION['first_attempt_time'] = time();
    }

    // IP-based counters (stored transiently in session)
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    if (!isset($_SESSION['ip_rl'])) { $_SESSION['ip_rl'] = []; }
    if (!isset($_SESSION['ip_rl'][$ip])) {
        $_SESSION['ip_rl'][$ip] = ['count' => 0, 't' => time()];
    } elseif ((time() - $_SESSION['ip_rl'][$ip]['t']) >= $ipWindow) {
        $_SESSION['ip_rl'][$ip] = ['count' => 0, 't' => time()];
    }

    if ($_SESSION['attempts'] >= $limit || $_SESSION['ip_rl'][$ip]['count'] >= $ipLimit) {
        $_SESSION['login_message'] = "Sai email hoặc mật khẩu!";
        header("Location: ./loginPage.php");
        exit();
    }

    // Connect to DB only after early checks pass
    require_once('./connectDatabase.php');
    $mysqli->select_db("user_management");

    // Vulnerable SQL query - susceptible to time-based blind SQL injection
    $query = "SELECT COUNT(*) as count FROM users WHERE email='$email' AND password='$password'";
    
    $result = @$mysqli->query($query);
    
    if ($result) {
        $res = $result->fetch_assoc();
    if ($res['count'] > 0) {
            // First check if this is a legitimate login with exact credentials
            $userQuery = "SELECT * FROM users WHERE email=? AND password=? LIMIT 1";
            $stmt = $mysqli->prepare($userQuery);
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $userResult = $stmt->get_result();
            
            if ($userResult && $userResult->num_rows > 0) {
                // Legitimate login - redirect to flag page
                $user = $userResult->fetch_assoc();
                $_SESSION['isLogged'] = true;
                $_SESSION['email'] = $user['email'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['isMale'] = $user['isMale'];
                $_SESSION['birthYear'] = $user['birthYear'];
                // Reset rate limit on successful login
                unset($_SESSION['attempts'], $_SESSION['first_attempt_time']);
                header("Location: ./pIPage.php");
                exit();
            }
            $stmt->close();
        }
    }
    
    // Increment counters and pad response to a minimum time
    $_SESSION['attempts'] += 1;
    if (isset($ip)) { $_SESSION['ip_rl'][$ip]['count'] += 1; }
    $elapsed = microtime(true) - $start;
    $min = 0.20; // seconds
    if ($elapsed < $min) {
        usleep((int)(($min - $elapsed) * 1e6));
    }
    // Always show the same generic error message regardless of SQL result
    $_SESSION['login_message'] = "Sai email hoặc mật khẩu!";
    header("Location: ./loginPage.php");
    exit();
}
?>