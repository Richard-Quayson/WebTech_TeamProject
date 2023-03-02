<?php
    // establish database connection
    require("db.php");

    // import helper methods
    require("helper.php");

    // ensure that the request is coming from the login page
    // if the request is not coming from login page, redirect to login page
    if (strstr($_SERVER['HTTP_REFERER'], "login.php")) {

        // collect data from the form
        $email = $_POST["email"];
        $password = $_POST["password"];

        // retrieve user details using the get_user_details function
        // returns -1 if query is unsuccessful
        $user_details = get_user_details($email, "email");
        
        // check if query was successful
        // if unsuccessful, redirect to login page
        if ($user_details == -1) {
            // header("Location: login.php");
            // exit();            
        } else {
            // if user exists, go ahead and verify password
            // else, redirect to login page
            if (password_verify($password, $user_details["user_password"])) {

                // check if user has an active account
                // if no, redirect to inactive page
                // if successful, create a session variable
                // else, redirect to login page
                if ($user_details["is_active"] != 1) {
                    echo "<h1>Account Suspended</h1>";
                    // header("Location: suspended.php");
                    exit();
                } else {
                    session_start();
                    $_SESSION["user_id"] = $user_details["user_id"];
                    $_SESSION["account_type"] = $user_details["account_type"];
                    $_SESSION["is_active"] = $user_details["is_active"];

                    // current date
                    $datetime = mktime(
                        date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")
                    );

                    $current_date = date("Y-m-d H:i:s", $datetime);

                    // update last login
                    $update_sql = $connection->prepare("UPDATE account SET last_login=? WHERE user_id=?");
                    $update_sql->bind_param("si", $current_date, $user_details["user_id"]);
                    $update_sql->execute();

                    // check the account type
                    // and redirect to appropriate dashboard
                    // account_type 0,1 ==> user dashboard
                    // account_type 28 ==> admin dashboard
                    if ($user_details["account_type"] == 28) {
                        // echo "Admin dashboard";
                        header("Location: admin_dashboard.php");
                        exit();
                    } else {
                        // echo "User dashboard";
                        header("Location: user_dashboard.php");
                        exit();
                    }
                }
            } else {
                echo '<script>alert("Password incorrect!")</script>';
            }
        }   
    } else {
        header("Location: login.php");
        exit();
    }
?>