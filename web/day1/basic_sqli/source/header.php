<!DOCTYPE html>
<?php
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    a {
        margin-right: 5px;
    }
</style>

<body>
    <div class="header-container">

        <?php
        if (isset($_SESSION['isLogged'])) {
            echo "<a href='./pIPage.php'>Thông tin cá nhân</a>";
            echo "<a href='./updatePIPage.php'>Cập nhật thông tin cá nhân</a>";
            echo "<a href='./logout.php'>Đăng xuất</a>";
        } else {
            echo "<a href='./registerPage.php'>Đăng ký</a>";
            echo "<a href='./loginPage.php'>Đăng nhập</a>";
        }
        ?>

    </div>
</body>

</html>