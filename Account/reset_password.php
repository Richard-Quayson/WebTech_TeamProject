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

                <form method="POST" action="" name="reset">
                    <label for="email"><b>Email</b></label>
                    <input type="email" placeholder="Enter Email" name="email" id="email"
                        value = <?php echo $email ?> required>
                    <br><br>

                    <label for="password"><b>Password</b></label>
                    <input type="password" placeholder="Enter Password" name="password" id="password" required>
                    <br><br>

                    <label for="psw-repeat"><b>Re-Enter Password</b></label>
                    <input type="password" placeholder="Repeat Password" name="password-repeat" id="password-repeat" required>
                    <br><br>

                    <button type="submit" class="reset-btn" name="reset" value="reset">Reset</button>

                </form>

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

                echo '<h3>Congratulations! Your password has been reset successfully!</h3>
                <p>Click <a href="login.php">here</a> to Login.</p></div><br />';
            } else {
                echo "Password reset unsuccessful!";
            }
        }
    }
?>