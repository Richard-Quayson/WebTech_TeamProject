<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>Your Projects</title>
    <link rel="stylesheet" href="assets/css/all-projects.css">
    <link rel="stylesheet" type="text/css" href="assets/css/project_view.css">
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v6.3.0/css/all.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
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

        // retrieve user details
        $user_details = get_user_details($_SESSION["user_id"], "user_id");
    ?>

    <div id="wrapper">
        <div style="background: rgba(235, 174, 142, 1); min-height: 100vh;">
            <div id="nav-contain" style=" padding: 2em;">

                <div style="height: 200px; margin: auto; width: 200px">
                    <?php if ($user_details["profile_image"] == NULL) {
                        echo '<img id = "profile" class="rounded-circle mb-3 mt-4" src="/WebTech_TeamProject/WebGeeks/assets/img/default_profile.png" width="160" height="160" style="margin-left: -68px;">';
                    } else {
                        echo '<img id = "profile" class="rounded-circle mb-10 mt-4" src="/WebTech_TeamProject/WebGeeks/assets/images/profile_images/' . $user_details["user_id"]  . '-' . $user_details["profile_image"] . '"' .
                        'width="160" height="80" style="margin-left: -68px;">';
                    }
                    ?>
                </div>

                <div>
                    <h3 style="color: black; padding: 10px 5px;"><?php echo $user_details["firstname"] . " " . $user_details["lastname"] ?></h3>
                </div>
                

                <ul class="navbar-nav text-light" id="accordionSidebar">
                        <li class="nav-item">
                            <a class="nav-link active" id="link1" href="user_dashboard.php">
                                <img src="assets/img/Project%20Management.png" width="30" height="22" style="width: 31px;height: 31px;">
                                <span style="margin: 4px;color: var(--bs-black);">Projects</span>
                            </a>
                            <div style="padding-left: 2em;">
                                <a class="nav-link active" id="link-3" href="your_projects.php">
                                    <img src="assets/img/Training.png" width="26" height="27">
                                    <span style="margin: 4px;color: var(--bs-black);">Your Projects</span>
                                </a>
                                <a class="nav-link active" id="link-2" href="requested_projects.php">
                                    <img src="assets/img/How%20Quest.png" width="31" height="24" style="height: 31px;">
                                    <span style="margin: 4px;color: var(--bs-black);">Request Projects</span>
                                </a>
                                <a class="nav-link active" id="link-1" href="invited_projects.php">
                                    <img src="assets/img/Send%20Hot%20List.png" width="21" height="21" style="width: 31px;height: 31px;">
                                    <span style="margin: 4px;color: var(--bs-black);">Invited Projects</span>
                                </a>
                            </div>
                            <a class="nav-link active" id="link-1" href="trash_page.php">
                                <img src="https://img.icons8.com/ios/50/null/empty-trash.png" width="21" height="21" style="width: 31px;height: 31px;">
                                <span style="margin: 4px;color: var(--bs-black);">Trash</span>
                            </a>
                            <a id="logout-btn" class="btn btn-primary ms-lg-3"  href="logout.php" role="button">Log out
                                <img src="https://img.icons8.com/ios-glyphs/30/null/logout-rounded-left.png"height="23px"/>
                            </a>
                        </li>
                    </ul>
                <div class="text-center d-none d-md-inline"></div>
            </div>
        </div>

        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav id = "mybar" class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="icons-house">
                        <div class="eye" id="eye1"><p class="visibility-text1">Member Acquisition:</p>
                            <div class="visibility"><i id = "lock" class="fa-sharp fa-solid fa-lock"></i></div>
                    </div>
                        <div class="eye"><p class="visibility-text">Project Visibility:</p><div class="visibility"><i id = "eye-slash" class="fa-solid fa-eye"></i></div></div>
                        <div class="eye">
                            <div class="visibility2" onclick="showform()">
                                <i style="width:20px; text-align:center;"id = "visibility2" class="fa-sharp fa-solid fa-ellipsis-vertical"></i>
                                <span class="tooltiptext">
                                    <ul style="list-style-type: none;">
                                        <li id="add-member">Add new members</li>
                                        <li id="invite-member">Invite members</li>
                                    </ul>
                                </span>
                            </div>
                            
                        </div>
                    </div>
                </nav>
                

                <div class="container-fluid">
                <h3 class="description">Your projects</h3>

                    <div class="main-content">

                        <!-- YOUR PROJECTS -->
                        <div class="page-header">

                            <div class="cards">
                                <section id = "projects">
                                    <div class="row mb-5 ">
                                        <?php 

                                            // retrieve and echo user projects
                                            $projects = get_user_projects($_SESSION["user_id"]);

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
                                                        echo '<div class="col-lg-4 col-sm-6">' .
                                                                '<div class="project">' .
                                                                    '<a href="/WebTech_TeamProject/WebGeeks/view_project.php?project_id=' . $project_details["project_id"] . '">' . 
                                                                        '<img src="/WebTech_TeamProject/WebGeeks/assets/images/project_images/' . $project_details["project_name"] . "-" . $project_details["project_image"] . '" width="100%" alt="">' .
                                                                    '</a>' .
                                                                '</div>' .
                                                        
                                                                $project_details["project_name"] .
                                                            '</div>';
                                                        // '<a style="padding-left: 20px" href="' . '/WebTech_TeamProject/Project/update_project.php?project_id=' . $project_details["project_id"] . '">Edit</a>' .
                                                        // '<a style="padding-left: 20px" href="' . '/WebTech_TeamProject/Project/delete_project.php?project_id=' . $project_details["project_id"] . '&action=delete">Delete</a><br>';
                                                    } else {
                                                        echo 'You have not created any project yet. Click <a href="create_project.php">here</a> to create one. <br>';
                                                    }
                                                }
                                            } else {
                                                echo 'You have not created any project yet. Click <a href="create_project.php">here</a> to create one. <br>';
                                            }                         
                                        ?>
                                    </div>
                                </section>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white sticky-footer">
        <div class="container my-auto"></div>
    </footer>

    </div>
        <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/theme.js"></script>

</body>
</html>