<?php
require_once("./connectDatabase.php");
$db_name = 'user_management';
$mysqli->select_db($db_name);

$message = ""; // Store messages to display later

if (
    !isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['password'])
    || !isset($_POST['birthYear']) || !isset($_POST['isMale'])
) {
    $message = "<h3>Vui lòng nhập đủ dữ liệu!</h3>";
} else {
    try {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $hashedPassword = $_POST['password'];
        $birthYear = $_POST['birthYear'];
        $isMale = $_POST['isMale'];

        $stm = $mysqli->prepare("INSERT INTO users(email, name, password, birthYear, isMale) VALUES(?,?,?,?,?);");
        $stm->bind_param("sssii", $email, $name, $hashedPassword, $birthYear, $isMale);
        if ($stm->execute()) {
            // Redirect immediately without any output
            header("Location: ./loginPage.php");
            exit();
        } else {
            $message = "<h3>Đăng ký không thành công!</h3>";
        }
        $stm->close();
        $mysqli->close();
    } catch (Exception $exc) {
        $message = "Error: " . $exc->getMessage();
    }
}

// Only output messages if there's no redirect
if (!empty($message)) {
    echo $message;
}
?>