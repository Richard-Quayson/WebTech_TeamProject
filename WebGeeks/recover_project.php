<?php
    // start a session
    session_start();

    // check if the user is logged in
    if (!isset($_SESSION["user_id"])) {
        header("Location: /WebTech_TeamProject/WebGeeks/login.php");
    }

    // establish db connection
    require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/WebGeeks/db.php");

    // import helper methods
    require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/WebGeeks/helper.php");

    // ensure the request is a valid post request
    if (isset($_GET["project_id"]) && isset($_GET["action"]) == "recover-project") {
        // collect project id
        $project_id = $_GET["project_id"];

        // ensure that the user requesting the project is the admin
        $project_details = get_project_details($project_id, "project_id");
        
        // echo $project_details["user_id"] . "<br>";
        // echo $_SESSION["user_id"];

        if ($project_details["user_id"] == $_SESSION["user_id"]) {

            // update query
            // set is_deleted to 0
            $IS_DELETED = 0;
            $update_sql = $connection->prepare("UPDATE project SET is_deleted=? WHERE project_id=?");
            $update_sql->bind_param("ii", $IS_DELETED, $project_id);
            $update_sql->execute();

            // check if the query was successful
            if ($update_sql->affected_rows > 0) {
                header("Location: /WebTech_TeamProject/WebGeeks/trash_page.php");
                echo '<script>alert("Project recovered successfully!")</script>';
            } else {
                header("Location: /WebTech_TeamProject/WebGeeks/trash_page.php");
                echo '<script>alert("Failed to recover project!")</script>';
            }
        } else {
            header("Location: /WebTech_TeamProject/WebGeeks/trash_page.php");
            echo '<script>alert("You do not have permission to recover project!")</script>';
        }
    } else {
        header("Location: /WebTech_TeamProject/WebGeeks/trash_page.php");
    }
?>