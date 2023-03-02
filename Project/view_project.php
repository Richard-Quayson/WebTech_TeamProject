<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php ?></title>
</head>
<body>
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
        if (isset($_GET["project_id"])) {

            // collect project id
            $project_id = $_GET["project_id"];

            // retrieve project details
            $project_details = get_project_details($project_id, "project_id");

            echo '<b>Project Name: </b><br>' . $project_details["project_name"] . "<br><br>" . 
            '<img width=100px height=100px src="/WebTech_TeamProject/images/project_images/' . 
            $project_details["project_name"]. "-" . $project_details["project_image"] . '"><br>' .
            '<b>Project Description: </b><br>' . $project_details["project_description"] . "<br><br>" .
            '<b>Project Visibility: </b>' . $project_details["visibility"] . "<br><br>" .
            '<b>Member Acquisition: </b>' . $project_details["member_acquisition"] . "<br><br>";

            echo "<b>Project Members: </b><br>";

            // retrieve all project members
            $project_members = get_project_members($project_id);

            // loop through the members and echo their roles
            if ($project_members == -1) {
                echo "Failed to load project members.";
            } else {
                // project_member attributes and corresponding indexes
                $USER_ID_INDEX = 0;
                $PROJECT_ID_INDEX = 1;
                $ROLE_ID_INDEX = 2;
                
                foreach ($project_members as $project_member) {
                    // retrieve details of the member
                    $member_details = get_user_details($project_member[$USER_ID_INDEX], "user_id");

                    // retrieve the role details
                    $role_details = get_role_details($project_member[$ROLE_ID_INDEX]);

                    // echo user and role details
                    echo $member_details["firstname"] . ' ' . $member_details["lastname"] . ': ' . 
                    '<a style="padding-left: 20px"></a>' . $role_details["role_name"] . '<br>';
                }
            }

            $is_admin = is_project_admin($_SESSION["user_id"], $project_id);

            if ($is_admin) {
                echo "<b><br>Requested members:</b><br>";

                // retrieve all people who have requested to join the project
                $requested_members = get_requested_project_members($project_id);

                // loop through the requested members and echo them with accept and decline options
                if ($requested_members == -1) {
                    echo "No members have requested to join the project!";
                } else {
                    
                    foreach ($requested_members as $requested_member) {
                        // retrieve requested_member details
                        $member_details = get_user_details($requested_member[$USER_ID_INDEX], "user_id");

                        // echo member details with accept and decline options
                        echo $member_details["firstname"] . ' ' . $member_details["lastname"] . ': ' . 
                        '<a style="padding-left: 20px" href="/WebTech_TeamProject/Project/accept_member.php?user_id=' . 
                        $requested_member[$USER_ID_INDEX] . '&project_id=' . $requested_member[$PROJECT_ID_INDEX] . '">Accept</a>' . 
                        '<a style="padding-left: 20px" href="/WebTech_TeamProject/Project/delete_request.php?user_id=' . $requested_member[$USER_ID_INDEX] . 
                        '&project_id=' . $requested_member[$PROJECT_ID_INDEX] . '&action=delete-request">Delete</a><br>';

                    }
                }
            }

        } else {
            header("Location: /WebTech_TeamProject/Account/user_dashboard.php");
            exit();
        }
    ?>
</body>
</html>