<?php
session_start();
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    body {
        display: flex;
        justify-content: center;
    }

    .register-container {
        margin: 50px;
        border: 1px solid black;
        padding: 20px;
        width: 500px;
    }

    h2 {
        justify-self: center;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .form-element {
        display: flex;
    }

    .form-element .label {
        width: 20%;
        display: flex;
        justify-content: end;
        margin-right: 3px;
    }

    .action {
        display: flex;
        justify-content: center;
        gap: 5px;
    }
</style>

<body>
    <?php include './header.php'; ?>
    <div class="register-container">
        <h2>THÔNG TIN THÀNH VIÊN</h2>
        <?php
        if (isset($_SESSION['isLogged'])) {
            echo " <h3><strong>Tên: </strong>" . $_SESSION['name'] . "</h3>";
            echo " <h3><strong>Email: </strong>" . $_SESSION['email'] . "</h3>";
            echo " <h3><strong>Năm sinh: </strong>" . $_SESSION['birthYear'] . "</h3>";
            echo " <h3><strong>Giới tính: </strong>" . ($_SESSION['isMale'] ? 'Nam' : "Nữ") . "</h3>";
            
            // Show flag for flag admin user
            if ($_SESSION['email'] == 'admin@example.com') {
                $flag = trim(file_get_contents('./flag.txt'));
                echo "<hr>";
                echo "<h2 style='color: red; text-align: center;'>🎉 CONGRATULATIONS! 🎉</h2>";
                echo "<h3 style='color: green; text-align: center; font-family: monospace;'>$flag</h3>";
                echo "<p style='text-align: center;'>You have successfully exploited the SQL injection vulnerability!</p>";
            }
        } else {
            header("Location: ./login.php");
        }
        ?>

    </div>
</body>

</html>