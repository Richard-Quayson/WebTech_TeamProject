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
    if (isset($_GET["social_id"])) {
        // get the social's id
        $social_id = $_GET["social_id"];

        // write the delete query
        $sql = $connection->prepare("DELETE FROM social WHERE social_id=?");
        $sql->bind_param("i", $social_id);
        $sql->execute();

        // redirect to dashboard
        header("Location: admin_dashboard.php");
        exit();
    } else {
        header("Location: admin_dashboard.php");
        exit();
    }
?>