<?php
    // start a session
    session_start();

    // check if the user is logged in
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit();
    }

    // establish db connection
    require("db.php");

    // ensure that the request is valid
    if (isset($_GET["project_id"]) && isset($_GET["action"]) == "delete-request") {
        // collect the project id
        $project_id = $_GET["project_id"];

        // if request contains user_id, then request is coming from 
        // the admin of the project and the user_id in the request should be used
        // else, request is coming from the user who requested to join the project
        // and the user_id session variable should be used
        if (isset($_GET["user_id"])) {
            $user_id = $_GET["user_id"];
        } else {
            $user_id = $_SESSION["user_id"];
            $is_user = true;
        }

        // delete request from db
        $delete_request = $connection->prepare("DELETE FROM requested_project WHERE user_id=? AND project_id=?");
        $delete_request->bind_param("ii", $user_id, $project_id);
        $delete_request->execute();

        // check if the request was successful
        if ($delete_request->affected_rows > 0) {
            echo '<script>alert("Project request deleted successfully!")</script>';
        } else {
            echo '<script>alert("Failed to delete project request!")</script>';
        }

        if ($is_user) {
            header("Location: /WebTech_TeamProject/Account/user_dashboard.php");
            exit();
        } else {
            header("Location: view_project.php?project_id=" . $project_id);
            exit();
        }
    } else {
        header("Location: user_dashboard.php");
        exit();
    }
?>