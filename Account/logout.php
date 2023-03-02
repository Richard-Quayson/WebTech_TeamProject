<?php
    // start a session
    session_start();

    // unsetting the session variable for the specific user
    unset($_SESSION["user_id"]);
    unset($_SESSION["account_type"]);
    unset($_SESSION["is_active"]);

    // redirect to login page
    header("Location: login.php");
?>