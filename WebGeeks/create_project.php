<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>Create Project</title>
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

        // establish db connection
        require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/WebGeeks/db.php");

        // import helper methods
        require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/WebGeeks/helper.php");

        // check if user is logged in, else redirect to user_dashboard
        if (!isset($_SESSION["user_id"])) {
            header("Location: login.php");
            exit();
        }

        // retrieve user details
        $user_details = get_user_details($_SESSION["user_id"], "user_id");

        // ensure that the request is a POST request
        if (isset($_POST["create"]) && isset($_POST["project-name"])) {

            // collect form data
            $name = $_POST["project-name"];
            $description = $_POST["project-description"];
            $project_image = $_FILES["image"]["name"];
            $visibility = $_POST["project-visibility"];
            $member_acquisition = $_POST["member-acquisition"];

            // upload project image to project_images
            $project_image_dir = $_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/WebGeeks/assets/images/project_images/";
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $project_image_dir . $name . "-" . $project_image)) {
                echo '<script>alert("Failed to upload image!")</script>';
                exit();
            }

            // insert query
            $insert_query = $connection->prepare("INSERT INTO project (user_id, project_name, project_description,
                project_image, visibility, member_acquisition) VALUES (?, ?, ?, ?, ?, ?)");
            $insert_query->bind_param("isssss", $_SESSION["user_id"], $name, $description, $project_image, $visibility, $member_acquisition);
            $insert_query->execute();

            // check if the query was successful
            if ($insert_query->affected_rows > 0) {

                // retrieve newly created project
                $get_sql = $connection->prepare("SELECT * FROM project WHERE project_name=?");
                $get_sql->bind_param("s", $name);
                $get_sql->execute();
                $row = $get_sql->get_result();

                // check if the query was successful
                if ($row->num_rows > 0) {
                    // fetch associated data
                    $project_details = $row->fetch_assoc();
                            
                    $admin_name = "Admin";
                    $admin_description = "The admin of the project.";
                    // retrieve the admin role details
                    $get_admin_role = $connection->prepare("SELECT * FROM project_role WHERE role_name=? AND role_description=?");
                    $get_admin_role->bind_param("ss", $admin_name, $admin_description);
                    $get_admin_role->execute();
                    $result = $get_admin_role->get_result();

                    // check if the query was successful
                    if ($result->num_rows > 0) {
                        // fetch associated data
                        $admin_details = $result->fetch_assoc();

                        // create the project member
                        $project_member = $connection->prepare("INSERT INTO project_members (user_id, project_id, role_id) VALUES (?, ?, ?)");
                        $project_member->bind_param("iii", $_SESSION["user_id"], $project_details["project_id"], $admin_details["role_id"]);
                        $project_member->execute();

                        // check if the query was successful
                        if ($project_member->affected_rows > 0) {
                            echo '<script>alert("Project member created successfully!")</script>';
                            header("Location: view_project.php"); 
                            exit();
                        } else {
                            // echo '<script>alert("Error 1!")</script>';
                            header("Location: create_project.php");
                            exit();
                        }
                    } else {
                        // echo '<script>alert("Error 2!")</script>';
                        header("Location: create_project.php");
                        exit();
                    }

                } else {
                    // echo '<script>alert("Error 3!")</script>';
                    header("Location: create_project.php");
                    exit();
                }
            } else {
                // echo '<script>alert("Error 4!")</script>';
                header("Location: create_project.php");
                exit();
            }

        }
    ?>

    
    <div id="wrapper">
        <!-- <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0 " style="background: rgba(235, 174, 142, 1);"> -->
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
        <!-- </nav> -->

        <div class="d-flex flex-column" id="content-wrapper">
            <nav id = "mybar" class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                <h3 style="padding-left: 1em" class="description">New Project</h3>
            </nav>                

            <div class="container-fluid">
                <div class="content">

                        <form method="POST" enctype="multipart/form-data">
                        <label for="project-name"><b>Project Name:</b></label>
                        <input type="text" placeholder="Enter Project Name" name="project-name" id="project-name" required>
                        <br><br>

                        <label for="project-description"><b>Project Description:</b></label>
                        <input type="text" placeholder="Enter Description" name="project-description" id="project-description" required>
                        <br><br>

                        <label for="image"><b>Project Image:</b></label>
                        <input type="file" name="image" id="image">
                        <br><br>

                        <label for="project-visibility"><b>Project visibility:</b></label>
                        <select name="project-visibility" id="project-visibility">
                            <?php 
                                $visibility_choices = array("Public", "Private");

                                foreach ($visibility_choices as $vis_choice) {
                                    echo '<option value="' . $vis_choice . '">' . $vis_choice . ' </option>';
                                }
                            ?>
                        </select>
                        <br><br>

                        <label for="member-acquisition"><b>Can new members join the project?</b></label>
                        <select name="member-acquisition" id="member-acquisition">
                            <?php 
                                $acquisition_choices = array("Open", "Closed");

                                foreach ($acquisition_choices as $acquisition_choice) {
                                    echo '<option value="' . $acquisition_choice . '">' . $acquisition_choice . ' </option>';
                                }
                            ?>
                        </select>
                        <br><br>

                        <button type="submit" id="accept" class="btn btn-primary" name="create" value="create">Create Project</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
                

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/theme.js"></script>
               
    <footer class="bg-white sticky-footer">
        <div class="container my-auto"></div>
    </footer>
    </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    
</body>

</html>