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
        <h2>ĐĂNG NHẬP</h2>
        <form method='POST' action="login.php">
            <div class="form-element">
                <label class='label' for="email">Địa chỉ Email: </label>
                <input type="text" id='email' name='email' required>
            </div>
            <div class="form-element">
                <label class='label' for="password">Mật khẩu:</label>
                <input type="password" id='password' name='password' required>
            </div>
            <div class="form-element action">
                <input type="submit" value='đăng nhập'>
                <input type="reset" value='hủy'>
            </div>
        </form>
    </div>
</body>

</html>