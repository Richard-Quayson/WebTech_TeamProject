<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>Requested Projects</title>
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
            header("Location: login.php");
            exit();
        }

        // establish db connection
        require("db.php");

        // import helper methods
        require("helper.php");

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
                    <h3 style="padding-left: 1em" class="description">Requested Projects</h3>
                </nav>
                

                <div class="container-fluid">
                    <div class="content">
                        <!-- REQUESTED PROJECTS -->
                        
                        <div class="cards">
                            <section id = "projects">
                                <div class="row mb-5 ">
                                    <div class="row g-3">
                                        <?php 
                                            // retrieve and echo requested projects
                                            $get_sql = $connection->prepare("SELECT * FROM requested_project WHERE user_id=?");
                                            $get_sql->bind_param("i", $_SESSION["user_id"]);
                                            $get_sql->execute();
                                            $row = $get_sql->get_result();

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
                                                        echo '<div class="col-lg-4 col-sm-6">' .
                                                                '<div class="project">' .
                                                                    '<a href="/WebTech_TeamProject/WebGeeks/view_project.php?project_id=' . $project_details["project_id"] . '">' . 
                                                                        '<img src="/WebTech_TeamProject/WebGeeks/assets/images/project_images/' . $project_details["project_name"] . "-" . $project_details["project_image"] . '" width="100%" alt="">' .
                                                                    '</a>' .
                                                                '</div>' .
                                                        
                                                                $project_details["project_name"] .
                                                                '</div>';

                                                        // echo '<a href="/WebTech_TeamProject/Project/view_project.php?project_id=' . $project_details["project_id"] . '">' . $project_details["project_name"] . '</a>'; 
                                                        // '<a style="padding-left: 20px" href="/WebTech_TeamProject/Project/delete_request.php?project_id=' . $project_details["project_id"] . '&action=delete-request">Delete Request</a><br>';                
                                                    } 
                                                }
                                            } else {
                                                echo '<br><br><strong>You have not requested to join any project yet.</strong>';
                                            }
                                                                    
                                        ?>
                                    </div>
                                </div>
                            </section>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white sticky-footer">
        <div class="container my-auto"></div>
    </footer>
    </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>