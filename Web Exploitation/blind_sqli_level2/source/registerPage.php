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
        <h2>THÔNG TIN ĐĂNG KÝ THÀNH VIÊN</h2>
        <form method='POST' action="register.php">
            <div class="form-element">
                <label class='label' for="name">Họ tên:</label>
                <input type="text" id='name' name='name' required>
            </div>
            <div class="form-element">
                <label class='label' for="email">Địa chỉ Email: </label>
                <input type="email" id='email' name='email' required>
            </div>
            <div class="form-element">
                <label class='label' for="password">Mật khẩu:</label>
                <input type="password" id='password' name='password' required>
            </div>
            <div class="form-element">
                <label class='label' for="birthYear">Năm sinh:</label>
                <input type="number" name='birthYear' required id='birthYear' value=2000 min="1900" max="2099" step="1"
                    placeholder="YYYY" />
            </div>
            <div class="form-element">
                <label class='label' for="isMale">Giới tính:</label>
                <input type="radio" name='isMale' id='isMale' value=1/ required><label>Nam</label>
                <input type="radio" name='isMale' id='isMale' value=0/ required><label>Nữ</label>
            </div>
            <div class="form-element action">
                <input type="submit" value='Đăng ký'>
                <input type="reset" value='Xóa form'>
            </div>
        </form>
    </div>
</body>

</html>