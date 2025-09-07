<?php
session_start(); // Start session first
require_once('./connectDatabase.php');
$mysqli->select_db("user_management");

$message = ""; // Store messages to display later

if (!isset($_POST['email']) || !isset($_POST['password'])) {
    $_SESSION['login_message'] = "Vui lòng nhập đủ dữ liệu!";
    header("Location: ./loginPage.php");
    exit();
} else {
    // Suppress error reporting for blind SQLi
    mysqli_report(MYSQLI_REPORT_OFF);
    
    $email = $_POST['email'];
    $password = $_POST['password'];

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
                header("Location: ./pIPage.php");
                exit();
            }
            $stmt->close();
        }
    }
    
    // Always show the same generic error message regardless of SQL result (fast response)
    $_SESSION['login_message'] = "Sai email hoặc mật khẩu!";
    header("Location: ./loginPage.php");
    exit();
}
?>