<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>Profile</title>
    <link rel="stylesheet" href="assets/css/all-projects.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
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

<div class="sidebar">
        <div class="sidebar-main">
            <div class="sidebar-user">
                <?php if ($user_details["profile_image"] == NULL) {
                    echo '<img src="/WebTech_TeamProject/WebGeeks/assets/img/default_profile.png" width="100%" alt="">';
                } else {
                    echo '<img src="/WebTech_TeamProject/WebGeeks/assets/images/profile_images/' . $user_details["user_id"]  . '-' . 
                    $user_details["profile_image"] . '" width="100% alt="">';
                }
                    
                ?>
                
                <div>
                    <h3><?php echo $user_details["firstname"] . " " . $user_details["lastname"] ?></h3>
                </div>
            </div>
        </div>


        <div class="sidebar-menu">

                <ul>
                    <li>
                        <a href="user_dashboard.php">
                        <span><img src="https://img.icons8.com/ios/50/null/project-management.png"height="25px"/></span>
                       <span><strong>Projects</strong></span>
                       
                        </a>
                    </li>
                    <li>
                        <a href="your_projects.php">
                        <span><img src="https://img.icons8.com/ios/50/null/teacher.png" height="25px"/></span>
                        <span width="100%">Your Projects</span>
                        </a>
                    </li>
                    <li>
                        <a href="requested_projects.php">
                        <span><img src="https://img.icons8.com/windows/32/null/how-quest.png"height="25px"/></span>
                        <span>Requested Projects</span>
                        </a>
                    </li>
                    <li>
                        <a href="invited_projects.php">
                        <span><img src="https://img.icons8.com/ios/50/null/send-hot-list.png"height="25px"/></span>
                        <span>Invited Projects</span>
                        </a>
                    </li>
                    <li>
                        <a href="trash_page.php">
                        <span><img src="https://img.icons8.com/ios/50/null/empty-trash.png" height="25px"/></span>
                        <span>Trash</span>
                        </a>
                    </li>
                    <li>
                        <a
                        id="btn"
                        class="btn btn-primary ms-lg-3"
                        href="logout.php"
                        role="button"
                        >Log out
                        <img src="https://img.icons8.com/ios-glyphs/30/null/logout-rounded-left.png"height="23px"/>
                        </a>
                    </li>
                    
                </ul>
                
        </div>
    </div>

    <div class="main-content">
        <header>
            <span></span>
            <div class="header-icons">
                <span class="las la-bars"></span>
                <span class="las la-add"></span>
            </div>
        </header>
        <main>
            <div class="page-header">
                <div>
                    <h1>Trash</h1>
                </div>
            </div>

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
        </main>
    </div>
</body>

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