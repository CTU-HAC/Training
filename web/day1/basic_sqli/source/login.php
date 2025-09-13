<?php
session_start(); // Start session first
require_once('./connectDatabase.php');
$mysqli->select_db("user_management");

$message = ""; // Store messages to display later

if (!isset($_POST['email']) || !isset($_POST['password'])) {
    $message = "<h3>Vui lòng nhập đủ dữ liệu!</h3>";
} else {
    try {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Vulnerable SQL query - susceptible to SQL injection
        $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        
        $result = $mysqli->query($query);
        
        if ($result && $result->num_rows > 0) {
            $res = $result->fetch_assoc();
            $_SESSION['isLogged'] = true;
            $_SESSION['email'] = $res['email'];
            $_SESSION['name'] = $res['name'];
            $_SESSION['isMale'] = $res['isMale'];
            $_SESSION['birthYear'] = $res['birthYear'];
            // Redirect immediately without any output
            header("Location: ./pIPage.php");
            exit();
        } else {
            $message = "<h3>Sai email hoặc mật khẩu!</h3>";
        }
    } catch (Exception $exc) {
        $message = "Error: " . $exc->getMessage();
    }
}

// Only output messages if there's no redirect
if (!empty($message)) {
    echo $message;
}
?>