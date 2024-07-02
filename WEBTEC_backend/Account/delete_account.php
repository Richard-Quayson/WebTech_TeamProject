<?php
    // start a session
    session_start();

    // establish db connection
    require("db.php");

    // check if the user is logged in
    // if not, redirect to login page
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit();
    }

    // check if it's a get request
    // if not, redirect to dashboard
    if (isset($_GET["user_id"])) {
        // ensure the user making the request is an admin
        if ($_SESSION["account_type"] == 28) {
            // get the user's id
            $user_id = $_GET["user_id"];

            // write the delete query
            $sql = $connection->prepare("DELETE FROM account WHERE user_id=?");
            $sql->bind_param("i", $user_id);
            $sql->execute();

            // redirect to dashboard
            header("Location: admin_dashboard.php");
            exit();
        } else {
            header("Location: admin_dashboard.php");
            exit();
        }
    } else {
        header("Location: user_dashboard.php");
        exit();
    }
?>