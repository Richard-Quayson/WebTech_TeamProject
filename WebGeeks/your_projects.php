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
                <!-- <div class="menu-head"> 
                    <span>Dashboard</span>
                </div> -->
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
            <!-- YOUR PROJECTS -->
            <div class="page-header">
                <div>
                    <h3>Your Projects</h3>
                </div>
            </div>

            <div class="cards">
                <section id = "projects">
                    <div class="row mb-5 ">
                        <div class="row g-3">
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
                    </div>
                </section>
            </div> 
        
        </main>
    </div>
</body>
 
            <footer class="bg-white sticky-footer">
                <div class="container my-auto"></div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>