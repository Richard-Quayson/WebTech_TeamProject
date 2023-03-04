<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>Project Bin</title>
    <link rel="stylesheet" type="text/css" href="assets/css/project_view.css">
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v6.3.0/css/all.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
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
                    <h1>Trash</h1>  
                    <div class="content">

                        <?php 
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

                                    $_SESSION["recover_project"] = "/WebTech_TeamProject/WebGeeks/recover_project.php?project_id=" . $deleted_project[$PROJECT_ID_INDEX] . "&action=recover-project";
                                    $_SESSION["delete_project"] = "/WebTech_TeamProject/WebGeeks/delete_project_permanently.php?project_id=" . $deleted_project[$PROJECT_ID_INDEX] . "&action=delete-permanently";

                                    echo 
                                        '<table class="table table-striped table-hover">' .
                                            '<!-- Table head -->' .
                                            '<thead>' .
                                                '<tr>' .
                                                    '<th scope="col">#</th>' .
                                                    '<th scope="col">Project Name</th>' .
                                                    '<th scope="col">Recover</th>' .
                                                    '<th scope="col">Delete Permanently</th>' .
                                                '</tr>' .
                                            '</thead>' .

                                            '<tbody>' .
                                                '<tr>' .
                                                '<th scope="row">1</th>' .
                                                '<td>' . $deleted_project[$PROJECT_NAME_INDEX] . '</td>' .
                                                '<!-- accept button -->' .
                                                '<td>' .
                                                    '<a href=""id="accept"' .
                                                    'data-bs-toggle="modal"' .
                                                    'data-bs-target="#accept-modal"' .
                                                    'class="btn btn-primary"' .
                                                    'href="#"' .
                                                    'role="button"' .
                                                    
                                                    '>Recover</a>' .
                                                '</td>' .
                                                '<!-- decline button -->' .
                                                '<td>' .
                                                    '<a href=""id="decline"' .
                                                    'data-bs-toggle="modal"' .
                                                    'data-bs-target="#delete-modal"' .
                                                    'class="btn btn-primary"' .
                                                    'href="#"' .
                                                    'role="button"' .
                                                    '>Delete</a>' .
                                                '</td>' .
                                    
                                                '</tr>' .
                                            '</tbody>' .
                                        '</table>';
                                }
                            } else {
                                echo "<center>You have no projects in the trash can!</center>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="accept-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Recover project</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        Are you sure you want to recover the project?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <a href="<?php echo $_SESSION["recover_project"]?>"><button type="button" class="btn btn-danger">Yes</button></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Permanently</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        Are you sure you want to delete?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <a href="<?php echo $_SESSION["delete_project"]?>"><button type="button" class="btn btn-danger">Yes</button></a>
                    </div>
                </div>
            </div>
        </div>
        <footer class="bg-white sticky-footer">
            <div class="container my-auto"></div>
        </footer>
    </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/theme.js"></script>

</body>
</html>