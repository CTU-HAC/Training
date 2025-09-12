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
    <?php include 'header.php'; ?>
    <div class="register-container">
        <h2>CẬP NHẬT THÔNG TIN THÀNH VIÊN</h2>
        <?php
        if (isset($_SESSION['isLogged'])) {
            echo "<form method='POST' action='updatePI.php'>";
            echo "    <div class='form-element'>";
            echo "        <label class='label' for='name'>Họ tên:</label>";
            echo "       <input type='text' value='" . $_SESSION['name'] . "' id='name' name='name'  required>";
            echo "    </div>";
            echo "    <div class='form-element'>";
            echo "        <label class='label' for='birthYear'>Năm sinh:</label>";
            echo "        <input type='number' name='birthYear' required id='birthYear'  min='1900' max='2099' step='1'  value='" . $_SESSION['birthYear'] . "' placeholder='YYYY' />";
            echo "    </div>";
            echo "    <div class='form-element'>";
            echo "        <label class='label' for='isMale'>Giới tính:</label>";
            echo "        <input type='radio' name='isMale' id='isMale' value=1 " . ($_SESSION['isMale'] ? 'checked' : '') . " required><label>Nam</label>";
            echo "        <input type='radio' name='isMale' id='isMale' value=0 " . ($_SESSION['isMale'] ? '' : 'checked') . "  required><label>Nữ</label>";
            echo "    </div>";
            echo "    <div class='form-element action'>";
            echo "        <input type='submit' value='Cập nhật'>";
            echo "        <input type='reset' value='Hủy'>";
            echo "    </div>";
            echo "</form>";
        } else {
            header("Location: ./login.php");
        }
        ?>

    </div>
</body>

</html>