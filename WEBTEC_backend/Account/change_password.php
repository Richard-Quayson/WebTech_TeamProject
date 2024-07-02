<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
</head>
<body>

    <?php 
        // start a session
        session_start();

        // establish database connection
        require("db.php");

        // import helper methods
        require("helper.php");
         
        // check if the user is logged in
        // if not, redirect to login page
        if (!isset($_SESSION["user_id"])) {
            header("Location: login.php");
            exit();
        }

        // if the request is a GET request, load the information of the user
        if (isset($_GET["user_id"])) {
            // get the user's id from url
            $id = $_GET["user_id"];

            // retrieve user details
            $user_details = get_user_details($id, "user_id");

            // check if the query was successful
            // if unsuccessful, redirect to dashboard
            if ($user_details == -1) {
                header("Location: user_dashboard.php");
                exit();
            } 
        } 

        // if the request is a POST request, update data in db
        if (isset($_POST["change_password"])) {

            // collect form data
            $current_password = $_POST["current-password"];
            $new_password = $_POST["password"];
            $confirm_password = $_POST["password-repeat"];

            // ensure the new passwords match
            // else, redirect to change-password page
            if ($new_password != $confirm_password) {
                header("Location: change-password.php?user_id=" . $_SESSION["user_id"]);
                exit();
            }

            // verify the current password
            // redirect to change-password page if unsuccessful
            // else hash the new password
            if (!password_verify($current_password, $user_details["user_password"])) {
                header("Location: change-password.php?user_id=" . $_SESSION["user_id"]);
                exit();
            } else {
                $encrypted_password = password_hash($new_password, PASSWORD_DEFAULT);
            }

            // update the password in db and log user out
            $sql = $connection->prepare("UPDATE account SET user_password=? WHERE user_id=?");
            $sql->bind_param("si", $encrypted_password, $_SESSION["user_id"]);
            $sql->execute();

            // check if the query was successful
            if ($sql->affected_rows > 0) {
                // alert user on update
                echo '<script>alert("Password changed successfully!")</script>';
            } else {
                echo '<script>alert("Failed to change password!")</script>';
            }

            // redirect user to logout page
            header("Location: logout.php");
            exit();
        }
    ?>

    <form method="POST">
        <h1>Hi <?php echo ucfirst($user_details["firstname"]) . " " .
            ucfirst($user_details["lastname"]); ?> </h1>

        <h1>Change password</h1>
        <p>Please fill this form to change account password</p>
        <hr>

        <label for="psw-repeat"><b>Current Password</b></label>
        <input type="password" placeholder="Current Password" name="current-password" id="password-repeat" required>
        <br><br>

        <label for="password"><b>New Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" id="password" required>
        <br><br>

        <label for="psw-repeat"><b>Confirm New Password</b></label>
        <input type="password" placeholder="Repeat Password" name="password-repeat" id="password-repeat" required>
        <br><br>

        <hr>

        <button type="submit" name="change_password" value="change_password">Save</button>
    </form>

    <br>
    <a href="user_dashboard.php">Back</a>
    
</body>
</html>