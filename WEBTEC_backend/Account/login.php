<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

    <!-- <?php
        // if user is already logged in, redirect them to the dashboard
        session_start();

        if ($_SESSION["user_id"]) {
            header("Location: user_dashboard.php");
            exit();
        } 
    ?> -->

    <h1>Login</h1>
    <p>Please fill this form to login into your account</p>
    <hr>

    <form action="login_proc.php" method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>
        <br><br>

        <label>Password:</label>
        <input type="password" name="password" required>
        <br><br>

        <button type="submit" value="login" class="login-btn">Login</button>
    </form>

    <div class="container-signup">
        <p>
            Don't have an account?
            <a href="register.php">Sign up</a>
        </p>
        <p>
            Forgot Password?
            <a href="forgot_password.php">Reset</a>
        </p>
    </div>
</body>
</html>