<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>Profile</title>
    <link rel="stylesheet" href="assets/css/new_project_style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
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
            <!-- <button type="submit" class="create-btn" name="create" value="create" 
                    id="accept" class="btn btn-primary" style="margin-right: 2vw;">Save</button> -->
            <div class="header-icons">
            
                <button 
                    type="submit" id="accept" class="btn btn-primary" role="button" name="create" value="create"
                    style="margin-right: 2vw;"
                >Save</button>

                <a href="user_dashboard.php">
                    <button 
                        id="decline"
                        class="btn btn-primary"
                        role="button"
                    >Cancel</button>
                </a>
                
                
                <span class="las la-bars"></span>
                <span class="las la-add"></span>
               
            </div>
        </header>
        <main>
            <div class="page-header">
                <div>
                    <h1>New Project</h1>
                </div>
            </div>
            <form method="POST" enctype="multipart/form-data">
                
                <div class="cards">

                    <div class="container">
                        <div class="row">
                            <div class="col-lg-4" 
                                style="background-color: white;
                                border: 2px solid rgba(250, 176, 135, 0.88); 
                                    height: 5vh;">
                                    <input style="margin-top: 5%" type="file" name="image" id="image">
                            </div>
                            
                            <div class="col-lg-4"
                                style="margin-left: 5vw;">
                                <textarea rows="1" cols="10" placeholder="Description..." name="project-description" form="usrform">
                                </textarea>
                            </div>
                            
                        </div>  

                        <input type="text" style=" width: 18vw;" placeholder="Project Name" name="project-name" required>

                        <div style="margin-top: 3vh; width: 15vw;">
                            <label for="project-visibility">Project visibility:</label>
                            <select name="project-visibility" id="project-visibility">
                                <?php 
                                    $visibility_choices = array("Public", "Private");

                                    foreach ($visibility_choices as $vis_choice) {
                                        echo '<option value="' . $vis_choice . '">' . $vis_choice . ' </option>';
                                    }
                                ?>
                            </select>
                        </div>

                        <div style="margin-top: 3vh; width: 15vw;">
                            <label for="member-acquisition">Can new members join the project?</label>
                            <select name="member-acquisition" id="member-acquisition">
                                <?php 
                                    $acquisition_choices = array("Open", "Closed");

                                    foreach ($acquisition_choices as $acquisition_choice) {
                                        echo '<option value="' . $acquisition_choice . '">' . $acquisition_choice . ' </option>';
                                    }
                                ?>
                            </select>
                        </div>
                        
                    </div>
                </div>
            </form>

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

                <button type="submit" class="create-btn" name="create" value="create">Register</button>
            </form>
        </main>
    </div>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/theme.js"></script>
</body>
               
        <footer class="bg-white sticky-footer">
            <div class="container my-auto"></div>
        </footer>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>

</html>