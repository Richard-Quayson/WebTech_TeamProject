<?php
    // start a session
    session_start();

    // check if the user is logged in
    if (!isset($_SESSION["user_id"])) {
        header("Location: /WebTech_TeamProject/Account/login.php");
        exit();
    }

    // establish db connection
    require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/Account/db.php");

    // import helper methods
    require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/Account/helper.php");

    // ensure that the get request is valid
    if (isset($_GET["project_id"]) && isset($_GET["action"]) == "join-project") {
        // get the project id
        $project_id = $_GET["project_id"];

        // retrieve project details and check if project is accepting members
        $project_details = get_project_details($project_id, "project_id");

        // if request is being made by project owner or member, redirect to project page
        if (is_project_member($_SESSION["user_id"], $project_id)) {
            header("Location: view_project.php?project_id=" . $project_id);
            exit();
        }

        // if project is accepting members, add requested member
        if ($project_details["member_acquisition"] == "Open") {
            $current_date = date("Y-m-d H:i:s");

            // insert member into requested_projects table
            $insert_sql = $connection->prepare("INSERT INTO requested_project (user_id, project_id, date_of_request) 
                VALUES (?, ?, ?)");
            $insert_sql->bind_param("iis", $_SESSION["user_id"], $project_id, $current_date);
            $insert_sql->execute();
            
            // check if the query was successful
            if ($insert_sql->affected_rows > 0) {
                echo 'Request to join project sent successfully!';
            } else {
                echo 'Request to join project failed!';
            }
        } else {
            echo '<script>alert("The project is currently not accepting members!")</script>';
        }
    } else {
        header("Location: /WebTech_TeamProject/Account/index.php");
        exit();
    }
?>