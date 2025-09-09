<?php
session_start();
// Generate one-time CSRF token (expires quickly) and dynamic field names per render
$_SESSION['csrf'] = [
    'v' => bin2hex(random_bytes(16)),
    't' => time(),
    'used' => false
];
$_SESSION['fields'] = [
    'email' => 'e_' . bin2hex(random_bytes(4)),
    'pass' => 'p_' . bin2hex(random_bytes(4)),
    'hp' => 'h_' . bin2hex(random_bytes(4)) // honeypot
];
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
        <?php
        // Display login message if exists
        if (isset($_SESSION['login_message'])) {
            echo "<div style='color: red; text-align: center; margin-bottom: 15px;'>";
            echo "<h3>" . $_SESSION['login_message'] . "</h3>";
            echo "</div>";
            unset($_SESSION['login_message']); // Clear message after displaying
        }
        ?>
        <form method='POST' action="login.php" autocomplete="off" novalidate>
            <div class="form-element">
                <label class='label' for="email">Địa chỉ Email: </label>
                <input type="text" id='email' name='<?php echo htmlspecialchars($_SESSION['fields']['email'], ENT_QUOTES); ?>' maxlength="64" required>
            </div>
            <div class="form-element">
                <label class='label' for="password">Mật khẩu:</label>
                <input type="password" id='password' name='<?php echo htmlspecialchars($_SESSION['fields']['pass'], ENT_QUOTES); ?>' maxlength="64" required>
            </div>
            <!-- CSRF token and honeypot -->
            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']['v'], ENT_QUOTES); ?>">
            <div style="position:absolute; left:-10000px; top:auto; width:1px; height:1px; overflow:hidden;">
                <label for="hp">Website</label>
                <input type="text" id="hp" name="<?php echo htmlspecialchars($_SESSION['fields']['hp'], ENT_QUOTES); ?>" tabindex="-1" autocomplete="off">
            </div>
            <div class="form-element action">
                <input type="submit" value='đăng nhập'>
                <input type="reset" value='hủy'>
            </div>
        </form>
    </div>
</body>

</html>