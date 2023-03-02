<?php 
    // start a session
    session_start();

    // establish db connection
    require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/Account/db.php");

    // check if the user is logged in
    if (!isset($_SESSION["user_id"])) {
        header("Location: /WebTech_TeamProject/Account/user_dashboard.php");
        exit();
    }

    // import helper methods
    require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/Account/helper.php");

    // ensure the delete request is valid
    if (isset($_GET["project_id"]) && isset($_GET["action"]) == "delete") {
        // ensure the user making the request is the admin of the project

        // retrieve the details of the project
        $project_details = get_project_details($_GET["project_id"], "project_id");

        // retrieve the user who is the admin of the project
        $admin_role = get_admin_role();

        // checking if the user is the admin of the project
        $get_sql = $connection->prepare("SELECT * FROM project_members WHERE user_id=? AND project_id=?");
        $get_sql->bind_param("ii", $_SESSION["user_id"], $project_details["project_id"]);
        $get_sql->execute();
        $result = $get_sql->get_result();

        // check if user exists
        if ($result->num_rows > 0) {
            // fetch associated results
            $project_member = $result->fetch_assoc();

            // check if the role of the project member is admin
            if ($project_member["role_id"] == $admin_role["role_id"]) {
                
                // 1 for deleted and 0 for not deleted
                $is_deleted = 1;
                // prepare update query
                $update_sql = $connection->prepare("UPDATE project SET is_deleted=? WHERE project_id=?");
                $update_sql->bind_param("ii", $is_deleted, $_GET["project_id"]);
                $update_sql->execute();

                if ($update_sql->affected_rows > 0) {
                    echo '<script>alert("The '. $project_details["project_name"] . ', project has been deleted successfully!")</script>';

                    header("Location: /WebTech_TeamProject/Account/user_dashboard.php");
                    exit();
                } else {
                    echo '<script>alert("Failed to delete the project, '. $project_details["project_name"] . '!")</script>';
                }
            } else {
                // reject delete request and redirect to user dashboard
                echo '<script>alert("Request to delete '. $project_details["project_name"] . ', rejected!")</script>';
            }
        }

    } else {
        header("Location: /WebTech_TeamProject/Account/user_dashboard.php");
        exit();
    }
?>