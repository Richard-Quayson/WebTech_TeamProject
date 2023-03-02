<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover Project</title>
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

        // retrieve all deleted projects for the user
        $deleted_projects = get_deleted_projects($_SESSION["user_id"]);

        // if the query returned a result
        if ($deleted_projects != -1) {
            // loop through returned projects and display
            foreach ($deleted_projects as $deleted_project) {

                // project entity indexes
                $PROJECT_ID_INDEX = 0;
                $USER_ID_INDEX = 1;
                $PROJECT_NAME_INDEX = 2;
                $PROJECT_DESCRIPTION_INDEX = 3;
                $PROJECT_IMAGE_INDEX = 4;
                $PROJECT_VISIBILITY_INDEX = 5;
                $MEMBER_ACQUISITION = 6;

                echo '<img width=50px height=50px src="' . '/WebTech_TeamProject/images/project_images/' . $deleted_project[$PROJECT_NAME_INDEX] . "-" . 
                $deleted_project[$PROJECT_IMAGE_INDEX] . '">' . '<b style="padding-left: 20px">' . $deleted_project[$PROJECT_NAME_INDEX] . '</b>' . 
                '<a style="padding-left: 20px" href="' . '/WebTech_TeamProject/Project/recover_project.php?project_id=' . $deleted_project[$PROJECT_ID_INDEX] . 
                '&action=recover-project">Recover</a>' .
                '<a style="padding-left: 20px" href="' . '/WebTech_TeamProject/Project/delete_project_permanently.php?project_id=' . $deleted_project[$PROJECT_ID_INDEX] . 
                '&action=delete-permanently">Delete Permanently</a><br>';
            }
        } else {
            echo "<b>You have no projects in the trash can!</b><br>";
        }
        
        echo '<br><br> <a href="/WebTech_TeamProject/Account/user_dashboard.php">Back</a>';
    ?>
    
</body>
</html>