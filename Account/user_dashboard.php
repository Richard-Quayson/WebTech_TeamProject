<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
</head>
<body>

    <?php
        // ensure db connection
        require("db.php");

        // start a session
        session_start();

        // import helper methods
        require("helper.php");

        // check if the user is logged in
        // if not, redirect to login page
        if (!isset($_SESSION["user_id"])) {
            header("Location: login.php");
            exit();
        } else {
            // if successful, check the account type of user
            // if not an individual or club, redirect back to login page
            if ($_SESSION["account_type"] == 1 || $_SESSION["account_type"] == 0) {
                // do nothing
            } else if ($_SESSION["account_type"] == 28) {
                header("Location: admin_dashboard.php");
                exit();
            } else {
                header("Location: login.php");
                exit();
            }
        }
    ?>
    
    <h1>Welcome to user landing page</h1>
    <?php 
        $user_details = get_user_details($_SESSION["user_id"], "user_id");
        // echo user details
        echo "Firstname: " . $user_details["firstname"] . "<br>";
        echo "Lastname: " . $user_details["lastname"] . "<br>";
        echo "Email: " . $user_details["email"] . "<br><br>";



        // retrieve and echo user socials
        $get_sql = $connection->prepare("SELECT * FROM user_socials WHERE user_id=?");
        $get_sql->bind_param("i", $_SESSION["user_id"]);
        $get_sql->execute();
        $result = $get_sql->get_result();

        echo "<b>Socials:</b><br>";

        // check if the request was successful
        if ($result->num_rows > 0) {

            // fetch associated data from get request
            $user_socials = $result->fetch_all();
            
            // loop through returned result and for each create a link with the social_url
            foreach ($user_socials as $user_social) {
                // retrieve the information for that social
                $SOCIAL_ID_INDEX = 0;
                $get_social = $connection->prepare("SELECT * FROM social WHERE social_id=?");
                $get_social->bind_param("i", $user_social[$SOCIAL_ID_INDEX]);
                $get_social->execute();
                $social_result = $get_social->get_result();
                
                // check if the query was successful
                if ($social_result->num_rows > 0) {
                    $social_details = $social_result->fetch_assoc();

                    // check if the social is a website or an email
                    // if email, create a mailing reference 
                    // else, create a website reference
                    if ($social_details["url_type"] == "Email") {
                        echo '<a href="mailto: ' . $social_details["url"] . '">' . $social_details["name"] . '</a>';
                    } else {
                        echo '<a target="_blank" href="' . $social_details["url"] . '">' . $social_details["name"] . '</a>';
                    }

                    echo '<a style="padding-left: 20px" href="update_social.php?social_id=' . $social_details["social_id"] . '">' . 'Edit</a>' .
                    '<a style="padding-left: 20px" href="delete_social.php?social_id=' . $social_details["social_id"] . '">' . 'Delete</a><br>';
                    
                } else {
                    echo "Failed to load socials. <br>";
                }
            } echo "<br>";
        } else {
            echo 'You have not added any social yet. Click <a href="add_social.php">here</a> to add one. <br><br>';
        }



        // retrieve and echo user projects
        $projects = get_user_projects($_SESSION["user_id"]);

        echo "<b>Your Projects:</b><br>";

        // check if the query returned a result
        if ($projects != -1) {
            // project_members indexes in resulting array
            $USER_ID_INDEX = 0;
            $PROJECT_ID_INDEX = 1;
            $ROLE_ID_INDEX = 2;
            
            // loop through returned projects and display to user
            foreach ($projects as $project) {
                $project_details = get_project_details($project[$PROJECT_ID_INDEX], "project_id");

                // if project has been deleted, don't show it
                if ($project_details["is_deleted"] == 0) {
                    echo '<a href="/WebTech_TeamProject/Project/view_project.php?project_id=' . $project_details["project_id"] . '">' . $project_details["project_name"] . '</a>' .
                    '<a style="padding-left: 20px" href="' . '/WebTech_TeamProject/Project/update_project.php?project_id=' . $project_details["project_id"] . '">Edit</a>' .
                    '<a style="padding-left: 20px" href="' . '/WebTech_TeamProject/Project/delete_project.php?project_id=' . $project_details["project_id"] . '&action=delete">Delete</a><br>';
                } else {
                    echo 'You have not created any project yet. Click <a href="create_project.php">here</a> to create one. <br>';
                }
            }
        } else {
            echo 'You have not created any project yet. Click <a href="create_project.php">here</a> to create one. <br>';
        }
        

        // INITIAL LOGIC
        // --------------------
        // retrieve and echo user projects
        // $projects = get_project_details($_SESSION["user_id"], "user_id");
        // echo "<b>Your Projects:</b><br>";
        // check if the query returned a result
        // if ($projects != -1) {

        //     // check the number of arrays in the $projects array after filtering
        //     // if there are arrays, then the number of projects is more than one and we can use a loop and indexes to access values
        //     // else the, number of project is one and we can use attribute names to access values
        //     $filtered_projects = array_filter($projects, 'is_array');
        //     if (count($filtered_projects) > 0) {
                
        //         // project attribute positions in resulting array
        //         $PROJECT_ID_INDEX = 0;
        //         $USER_ID_INDEX = 1;
        //         $PROJECT_NAME_INDEX = 2;
        //         $PROJECT_DESCRIPTION_INDEX = 3;
        //         $PROJECT_IMAGE_INDEX = 4;
        //         $PROJECT_VISIBILITY_INDEX = 5;
        //         $MEMBER_ACQUISITION_INDEX = 6;
        //         $IS_DELETED_INDEX = 7;

        //         // loop through returned projects and display to user
        //         foreach ($projects as $project) {
        //             // if project has been deleted, don't show it
        //             if ($project[$IS_DELETED_INDEX] == 0) {
        //                 echo '<a href="/WebTech_TeamProject/Project/view_project.php?project_id=' . $project[$PROJECT_ID_INDEX] . '">' . $project[$PROJECT_NAME_INDEX] . '</a>' .
        //                 '<a style="padding-left: 20px" href="' . '/WebTech_TeamProject/Project/update_project.php?project_id=' . $project[$PROJECT_ID_INDEX] . '">Edit</a>' .
        //                 '<a style="padding-left: 20px" href="' . '/WebTech_TeamProject/Project/delete_project.php?project_id=' . $project[$PROJECT_ID_INDEX] . '&action=delete">Delete</a><br>';
        //             }
        //         }
        //     } else {
        //         if ($projects["is_deleted"] == 0) {
        //             echo '<a href="/WebTech_TeamProject/Project/view_project.php?project_id=' . $projects["project_id"] . '">' . $projects["project_name"] . '</a>' .
        //             '<a style="padding-left: 20px" href="' . '/WebTech_TeamProject/Project/update_project.php?project_id=' . $projects["project_id"] . '">Edit</a>' .
        //             '<a style="padding-left: 20px" href="' . '/WebTech_TeamProject/Project/delete_project.php?project_id=' . $projects["project_id"] . '&action=delete">Delete</a><br>';
        //         } else {
        //             echo 'You have not created any project yet. Click <a href="create_project.php">here</a> to create one. <br>';
        //         }
        //     }

        // } else {
        //     echo 'You have not created any project yet. Click <a href="create_project.php">here</a> to create one. <br>';
        // }



        // retrieve and echo request projects
        $get_sql = $connection->prepare("SELECT * FROM requested_project WHERE user_id=?");
        $get_sql->bind_param("i", $_SESSION["user_id"]);
        $get_sql->execute();
        $row = $get_sql->get_result();

        echo "<b><br>Requested Projects:</b><br>";

        // check if the request was successful
        if ($row->num_rows > 0) {
            // fetch associated data from get request
            $requested_projects = $row->fetch_all();
            // loop through returned result and echo project name
            foreach ($requested_projects as $requested_project) {
                $RPROJECT_ID_INDEX = 1;

                // retrieve information for the project
                $project_details = get_project_details($requested_project[$RPROJECT_ID_INDEX], "project_id");

                // if the project hasn't been deleted and the project member acquisition is open, display project
                if ($project_details["is_deleted"] != 1 && $project_details["member_acquisition"] == "Open") {
                    echo '<a href="/WebTech_TeamProject/Project/view_project.php?project_id=' . $project_details["project_id"] . '">' . $project_details["project_name"] . '</a>' . 
                    '<a style="padding-left: 20px" href="/WebTech_TeamProject/Project/delete_request.php?project_id=' . $project_details["project_id"] . '&action=delete-request">Delete Request</a><br>';                
                } 
            }
        } else {
            echo 'You have not requested to join any project yet.<br>';
        }



        // retrieve and echo user roles
        $get_projects = $connection->prepare("SELECT * FROM user_roles WHERE user_id=?");
        $get_projects->bind_param("i", $_SESSION["user_id"]);
        $get_projects->execute();
        $rows = $get_projects->get_result();

        echo "<b><br>Roles you've created:</b><br>";

        // check if the query returned a result
        if ($rows->num_rows > 0) {
            // fetch associated data
            $roles = $rows->fetch_all();

            // role attribute positions in resulting array
            $ROLE_ID_INDEX = 1;

            // loop through returned projects and display to user
            foreach ($roles as $role) {
                // retrieve role details
                $role_details = get_role_details($role[$ROLE_ID_INDEX]);

                echo '<a href="/WebTech_TeamProject/Project/view_role.php?role=' . $role_details["role_id"] . '">' . $role_details["role_name"] . '</a>' .
                '<a style="padding-left: 20px" href="' . '/WebTech_TeamProject/Project/delete_role.php?role_id=' . $role_details["role_id"] . '&action=delete">Delete</a><br>';           
            }

        } else {
            echo '<br>You have not created any roles yet. Click <a href="create_role.php">here</a> to create one. <br>';
        }
    ?>



    <br>
    <a href="update_account.php?user_id=<?php echo $_SESSION["user_id"]; ?>">Update Profile</a>
    <br>
    <a href="add_social.php?user_id=<?php echo $_SESSION["user_id"]; ?>">Add social</a>
    <br>
    <a href="/WebTech_TeamProject/Project/create_role.php">Add Role</a>
    <br>
    <a href="/WebTech_TeamProject/Project/create_project.php">Create New Project</a>
    <br>
    <a href="change_password.php?user_id=<?php echo $_SESSION["user_id"]; ?>">Change Password</a>
    <br>
    <a href="/WebTech_TeamProject/Project/trash.php">Trash</a>
    <br><br>
	<a href="logout.php">Logout</a>
</body>
</html>