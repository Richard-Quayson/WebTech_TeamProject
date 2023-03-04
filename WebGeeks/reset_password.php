<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/css/reset-style.css">

</head>
<body>
    
    <?php
        // establish db connection
        require("db.php");

        // check if GET request is valid
        if (isset($_GET["reset_key"]) && isset($_GET["email"]) && isset($_GET["action"])
        && ($_GET["action"] == "reset") && !isset($_POST["update"])) {

            // collect data from request
            $key = $_GET["reset_key"];
            $email = $_GET["email"];
            $current_date = date("Y-m-d H:i:s");

            // ensure user exists in password_reset table
            $sql = $connection->prepare("SELECT * FROM password_reset WHERE email=? AND reset_key=?");
            $sql->bind_param("ss", $email, $key);
            $sql->execute();
            $result = $sql->get_result();

            // check if query returned a result
            if ($result->num_rows == 0) {
                echo '<h2>Invalid Link</h2>
                <p>This link is either invalid or has expired. Please check the password reset link.</p>
                <p>Click <a href="forgot_password.php">here</a> to reset password.</p>';
            } else {
                $reset_details = $result->fetch_assoc();
                
                // check if the link has expired
                if ($reset_details["expDate"] >= $current_date) {
                    ?>

                    <div class="container" id="cont1">
                        <div class="row justify-content-center" id="row1">
                            <div class="col-md-8" id="col1">
                                <div class="card shadow-lg o-hidden border-0 my-5" id="card1">
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <!-- <div class="col-lg-6 d-none d-lg-flex" id="col2"><img src="assets/img/picture.png"></div> -->
                                                <div class="col-lg-10">
                                                <div class="p-5" style="margin-left: 15vw;">
                                                    <div class="text-center">
                                                        <h4 class="text-dark mb-2">Reset your password.</h4>
                                                        <br>
                                                    <form class="user" method="POST">
                                                        <div class="mb-3">
                                                            <input class="form-control" type="email" id="" aria-describedby="emailHelp" placeholder="Enter Email..." name="email" 
                                                                value = <?php echo $email ?> readonly style="font-size: 16px;" required>
                                                            <input class="form-control" type="password" id="" aria-describedby="emailHelp" placeholder="Enter New Password..." name="password" style="font-size: 16px;">
                                                            <input class="form-control" type="password" id="" aria-describedby="emailHelp" placeholder="Re-enter New Password..." name="password-repeat" style="font-size: 16px;">

                                                        </div>
                                                        <button type="submit" class="reset-btn" name="reset" value="reset" class="btn btn-primary d-block btn-user w-100" id="btn2">Reset</button>

                                                    </form>
                                                    
                                                    <p style="padding-right: 20px;margin-right: 236px;"></p>
                                                    <div class="text-center">
                                                        <p id="p2" style="font-size: 13px;"></p>
                                                    </div>
                                                </div>
                                            <!-- </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
                    <script src="assets/js/theme.js"></script>

                <?php
                } else {
                    echo '<h2>Invalid Link</h2>
                    <p>This link is has expired. The link is only valid within 
                    30 minutes after it is sent.</p>
                    <p>Click <a href="forgot_password.php">here</a> to reset password.</p>';
                }
            }

        }  else {
            echo "<h3>Password has expired! <br> Password reset links expires after 30 minutes. 
                <br> Generate a new link</h3>";
        }

        // check if POST request is valid
        if (isset($_POST["email"]) && isset($_POST["reset"])) {

            // collect form data
            $password = $_POST["password"];
            $password_repeat = $_POST["password-repeat"];
            $email = $_POST["email"];

            // check if passwords match
            if ($password != $password_repeat) {
                echo '<script>alert("Passwords must match!")</script>';
            } else {
                // hash the password
                $encrypted_password = password_hash($password, PASSWORD_DEFAULT);

                // write update query
                $sql = $connection->prepare("UPDATE account SET user_password=? WHERE email=?");
                $sql->bind_param("ss", $encrypted_password, $email);
                $sql->execute();

                // check if query was successful
                // if successful, delete reset token from password_reset table
                if ($sql->affected_rows > 0) {

                    // delete update token from password_reset table
                    $delete_sql = $connection->prepare("DELETE FROM password_reset WHERE email=?");
                    $delete_sql->bind_param("s", $email);
                    $delete_sql->execute();

                    header("Location: success_password_reset.html");
                    exit();
                } else {
                    echo "Password reset unsuccessful!";
                }
            }
        }
    ?>
</body>
</html>