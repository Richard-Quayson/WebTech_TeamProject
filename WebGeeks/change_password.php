<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Change Password</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/css/reset-style.css">
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
                                        <h4 class="text-dark mb-2">Change your password.</h4>
                                        <br>
                                    <form class="user" method="POST">
                                        <div class="mb-3">
                                            <input class="form-control" type="password" id="" aria-describedby="emailHelp" placeholder="Enter Current Password..." name="current-password" style="font-size: 16px;">
                                            <input class="form-control" type="password" id="" aria-describedby="emailHelp" placeholder="Enter New Password..." name="password" style="font-size: 16px;">
                                            <input class="form-control" type="password" id="" aria-describedby="emailHelp" placeholder="Re-enter New Password..." name="password-repeat" style="font-size: 16px;">

                                        </div>
                                        <button type="submit" name="change_password" value="change_password" class="btn btn-primary d-block btn-user w-100" id="btn2">Change Password</button>

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
</body>

</html>