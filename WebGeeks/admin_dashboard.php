<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>

<body>

    <?php
        // start a session
        session_start();

        // import helper methods
        require("helper.php");

        // check if the user is logged in
        // if not, redirect to login page
        if (!isset($_SESSION["user_id"])) {
            header("Location: login.php");
            exit();
        } else {
            // if successful, check the account type of user
            // if not an admin, redirect back to login page
            if ($_SESSION["account_type"] == 1 || $_SESSION["account_type"] == 0) {
                header("Location: user_dashboard.php");
                exit();
            } 
        }
    ?>

    <h1>Welcome <?php $_SESSION["user_id"] ?> to admin landing page</h1>
    <?php 
        $user_details = get_user_details($_SESSION["user_id"], "user_id");
        // echo the result
        echo "Firstname: " . $user_details["firstname"] . "<br>";
        echo "Lastname: " . $user_details["lastname"] . "<br>";
        echo "Email: " . $user_details["email"] . "<br><br>";

        get_all_users();
    ?>
    <!-- <a href="change-password.php?user_id=<?php echo $_SESSION["user_id"]; ?>">Delete</a> -->
    <!-- <a href="delete_account.php?user_id=16">Delete</a> -->
    <br>
    <a href="logout.php">Logout</a>
</body>
</html>