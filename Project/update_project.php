<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Project</title>
</head>
<body>

    <?php
        // start a session
        session_start();

        // establish db connection
        require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/Account/db.php");

        // import helper methods
        require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/Account/helper.php");

        // profile images directory
        $project_image_dir = $_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/images/project_images/";

        // check if the user is logged in
        if (!isset($_SESSION["user_id"])) {
            header("Location: login.php");
            exit();
        }

        // if the request is a GET request
        if (isset($_GET["project_id"])) {
            // get the project id
            $project_id = $_GET["project_id"];

            // retrieve project details
            $project_details = get_project_details($project_id, "project_id");

            // check if the query was successful
            if ($project_details == -1) {
                header("Location: user_dashboard.php");
                exit();
            }
        }

        // if the request is a POST request
        if (isset($_POST["update-project"]) && isset($_POST["project-name"]) && isset($_POST["project-description"])) {

            // collect form data
            $project_name = $_POST["project-name"];
            $project_description = $_POST["project-description"];
            $project_image = $_FILES["image"]["name"];
            $project_visibility = $_POST["project-visibility"];
            $member_acquisition = $_POST["member-acquisition"];

            // retrieve project details
            $project_info = get_project_details($_GET["project_id"], "project_id");

            // check if the query was successful, else redirect to dashboard
            if ($project_info == -1) {
                header("Location: user_dashboard.php");
                exit();
            }

            // check if a new image was uploaded, if yes, store image
            if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $project_image_dir . $project_name . "-" . $project_image)) {
                    echo '<script>alert("Failed to upload image!")</script>';
                }
            }
            

            // check if there is an image
            if ($project_info["project_image"] != NULL) {
                
                // check if new file was uploaded
                if (!is_uploaded_file($_FILES["image"]["tmp_name"])) {
                    // if no file was uploaded, maintain old image
                    $project_image = $project_info["project_image"];
                    echo '<script>alert("No image was chosen!")</script>';
                } else {
                    // create old image directory link
                    $old_project_image_dir = $project_image_dir . $project_info["project_name"] . "-" . $project_info["project_image"];
                    
                    // delete image from directory
                    unlink($old_project_image_dir);

                    echo '<script>alert("Old project image deleted!")</script>';
                }
            }

            // write update query
            $update_sql = $connection->prepare("UPDATE project SET project_name=?, project_description=?, project_image=?, visibility=?,
                member_acquisition=? WHERE project_id=?");
            $update_sql->bind_param("sssssi", $project_name, $project_description, $project_image, $project_visibility, $member_acquisition, $_GET["project_id"]);
            $update_sql->execute();

            // check if the query was successful
            if ($update_sql->affected_rows > 0) {
                // alert user on project update
                echo '<script>alert("Project was update successful!")</script>';

                // redirect user to update page
                header("Location: /WebTech_TeamProject/Project/update_project.php?project_id=" . $project_info["project_id"]);
            } else {
                echo '<script>alert("No change detected!")</script>';
            }
        }
    ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="project-name"><b>Project Name:</b></label>
        <input type="text" placeholder="Enter Project Name" name="project-name" id="project-name" 
            value='<?php echo $project_details["project_name"] ?>' required>
        <br><br>

        <label for="project-description"><b>Project Description:</b></label>
        <input type="text" placeholder="Enter Description" name="project-description" id="project-description" 
            value='<?php echo $project_details["project_description"] ?>' required>
        <br><br>

        <label for="image"><b>Project Image:</b></label>
        <img width="30px" height="30px" src='<?php echo "/WebTech_TeamProject/images/project_images/" . $project_details["project_name"] . 
            "-" . $project_details["project_image"] ?>'>
        <input type="file" name="image" id="image">
        <br><br>

        <?php echo "/WebTech_TeamProject/images/project_images/" . $project_details["project_name"] . 
            "-" . $project_details["project_image"] ?>
            <br>

        <label for="project-visibility"><b>Project visibility:</b></label>
        <select name="project-visibility" id="project-visibility">
            <?php 
                $visibility_choices = array("Public", "Private");

                foreach ($visibility_choices as $vis_choice) {
                    // if vis_choice matches user choice, select that choice
                    if ($project_details["visibility"] == $vis_choice) {
                        echo '<option value="' . $vis_choice . '" selected>' . $vis_choice . ' </option>';
                    } else {
                        echo '<option value="' . $vis_choice . '">' . $vis_choice . ' </option>';
                    }
                }
            ?>
        </select>
        <br><br>

        <label for="member-acquisition"><b>Can new members join the project?</b></label>
        <select name="member-acquisition" id="member-acquisition">
            <?php 
                $acquisition_choices = array("Open", "Closed");

                foreach ($acquisition_choices as $acquisition_choice) {
                    // if acquisition_choice matches user choice, select that choice
                    if ($project_details["member_acquisition"] == $acquisition_choice) {
                        echo '<option value="' . $acquisition_choice . '" selected>' . $acquisition_choice . ' </option>';
                    } else {
                        echo '<option value="' . $acquisition_choice . '">' . $acquisition_choice . ' </option>';
                    }
                }
            ?>
        </select>
        <br><br>

        <button type="submit" value="update-project" name="update-project">Update</button>
    </form>

    <br>
    <a href="/WebTech_TeamProject/Account/user_dashboard.php">Back</a>
</body>
</html>