<?php
    // start a session
    session_start();

    // check if the user is logged in
    if (!isset($_SESSION["user_id"])) {
        header("Location: /WebTech_TeamProject/WebGeeks/Login.php");
    }

    // establish db connection
    require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/WebGeeks/db.php");

    // import helper methods
    require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/WebGeeks/helper.php");

    // ensure the request is a valid post request
    if (isset($_GET["project_id"]) && isset($_GET["action"]) == "delete-permanently") {
        // collect project id
        $project_id = $_GET["project_id"];

        // ensure that the user requesting the project is the admin
        $project_details = get_project_details($project_id, "project_id");
        
        // echo $project_details["user_id"] . "<br>";
        // echo $_SESSION["user_id"];

        if ($project_details["user_id"] == $_SESSION["user_id"]) {

            // delete query
            $delete_sql = $connection->prepare("DELETE FROM project WHERE project_id=?");
            $delete_sql->bind_param("i", $project_id);
            $delete_sql->execute();

            // check if the query was successful
            if ($delete_sql->affected_rows > 0) {
                header("Location: /WebTech_TeamProject/WebGeeks/trash_page.php");
                // echo '<script>alert("Project deleted successfully!")</script>';
                exit();
            } else {
                header("Location: /WebTech_TeamProject/WebGeeks/trash_page.php");
                exit();
                // echo '<script>alert("Failed to delete project!")</script>';
            }
        } else {
            header("Location: /WebTech_TeamProject/WebGeeks/trash_page.php");
            exit();
            // echo '<script>alert("You do not have permission to delete project!")</script>';
        }
    } else {
        header("Location: /WebTech_TeamProject/WebGeeks/trash_page.php");
        exit();
    }
?>