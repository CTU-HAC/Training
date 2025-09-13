<?php
session_start(); // Start session first
require_once("./connectDatabase.php");
$db_name = 'user_management';
$mysqli->select_db($db_name);

$message = ""; // Store messages to display later

if (
    !isset($_POST['name']) || !isset($_POST['birthYear']) || !isset($_POST['isMale'])
) {
    $message = "<h3>Vui lòng nhập đủ dữ liệu!</h3>";
} else {
    try {
        $email = $_SESSION['email'];
        $name = $_POST['name'];
        $birthYear = $_POST['birthYear'];
        $isMale = $_POST['isMale'];

        $stm = $mysqli->prepare("UPDATE users SET name=?, birthYear=?, isMale=? WHERE email=?;");
        $stm->bind_param('siis', $name, $birthYear, $isMale, $email);
        if ($stm->execute()) {
            $_SESSION['name'] = $name;
            $_SESSION['birthYear'] = $birthYear;
            $_SESSION['isMale'] = $isMale;

            // Redirect immediately without any output
            header("Location: ./pIPage.php");
            exit();
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