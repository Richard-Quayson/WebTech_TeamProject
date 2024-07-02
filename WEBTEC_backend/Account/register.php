<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body>

    <h1>Register</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>

    <form action="register_proc.php" method="POST">
        <div class="container">
            <label for="firstname"><b>Firstname</b></label>
            <input type="text" placeholder="Enter Firstname" name="firstname" id="firstname" required>
            <br><br>

            <label for="lastname"><b>Lastname</b></label>
            <input type="text" placeholder="Enter Lastname" name="lastname" id="lastname" required>
            <br><br>

            <label for="email"><b>Email</b></label>
            <input type="email" placeholder="Enter Email" name="email" id="email" required>
            <br><br>

            <label for="password"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" id="password" required>
            <br><br>

            <label for="psw-repeat"><b>Re-Enter Password</b></label>
            <input type="password" placeholder="Repeat Password" name="password-repeat" id="password-repeat" required>
            <br><br>

            <!-- 0 for clubs and 1 for individuals -->
            <label for="account-type"><b>Account Type</b></label>
            <select name="account-type" id="account-type">
                <option value="1">Individual</option>
                <option value="0">Club/Team</option>
            </select>
            <hr>

            <p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>
            <button type="submit" class="register-btn" name="register" value="register">Register</button>
        </div>
    </form>

    <div class="container-signin">
        <p>Already have an account? <a href="login.php">Sign in</a>.</p>
    </div>

    <script src="script.js"></script>
</body>

</html>