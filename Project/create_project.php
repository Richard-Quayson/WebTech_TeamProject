<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Project</title>
</head>
<body>

    <?php 
        session_start();

        // establish db connection
        require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/Account/db.php");

        // check if user is logged in, else redirect to user_dashboard
        if (!isset($_SESSION["user_id"])) {
            header("Location: login.php");
            exit();
        }

        // ensure that the request is a POST request
        if (isset($_POST["create"]) && isset($_POST["project-name"]) && isset($_POST["project-description"])) {

            // collect form data
            $name = $_POST["project-name"];
            $description = $_POST["project-description"];
            $project_image = $_FILES["image"]["name"];
            $visibility = $_POST["project-visibility"];
            $member_acquisition = $_POST["member-acquisition"];

            // upload project image to project_images
            $project_image_dir = $_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/images/project_images/";
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
                            // header("Location: view_project.php");
                            // exit();
                        } else {
                            echo '<script>alert("Error 1!")</script>';
                            // header("Location: create_project.php");
                            // exit();
                        }
                    } else {
                        echo '<script>alert("Error 2!")</script>';
                        // header("Location: create_project.php");
                        // exit();
                    }

                } else {
                    echo '<script>alert("Error 3!")</script>';
                    // header("Location: create_project.php");
                    // exit();
                }
            } else {
                echo '<script>alert("Error 4!")</script>';
                // header("Location: create_project.php");
                // exit();
            }

        }
    ?>

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
</body>
</html>