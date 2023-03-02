<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Geeks</title>
</head>
<body>
    <?php 
        // establish db connection
        require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/Account/db.php");

        // get all projects and echo to user
        $get_projects = $connection->prepare("SELECT * FROM project");
        $get_projects->execute();
        $rows = $get_projects->get_result();

        // check if the query was successful
        if ($rows->num_rows > 0) {
            // fetch associated data
            $all_projects = $rows->fetch_all();

            // project attribute positions in resulting array
            $PROJECT_ID_INDEX = 0;
            $USER_ID_INDEX = 1;
            $PROJECT_NAME_INDEX = 2;
            $PROJECT_DESCRIPTION_INDEX = 3;
            $PROJECT_IMAGE_INDEX = 4;
            $PROJECT_VISIBILITY_INDEX = 5;
            $MEMBER_ACQUISITION_INDEX = 6;
            $IS_DELETED_INDEX = 7;

            // echo all public projects
            foreach ($all_projects as $project) {
                if ($project[$PROJECT_VISIBILITY_INDEX] == "Public" && $project[$IS_DELETED_INDEX] != 1) {
                    echo '<img width=50px height=50px src="/WebTech_TeamProject/images/project_images/' . $project[$PROJECT_NAME_INDEX] . "-" . $project[$PROJECT_IMAGE_INDEX] . '">' .
                    '<a style="padding-left: 20px" href="/WebTech_TeamProject/Project/view_project.php?project_id=' . $project[$PROJECT_ID_INDEX] . '">' . $project[$PROJECT_NAME_INDEX] . '</a>' .
                    '<a style="padding-left: 20px" href="/WebTech_TeamProject/Project/join_project.php?project_id=' . $project[$PROJECT_ID_INDEX] . '&action=join-project">Join Project</a><br>';                
                }
            }
        } else {
            header("index.php");
            exit();
        }
    ?>
</body>
</html>