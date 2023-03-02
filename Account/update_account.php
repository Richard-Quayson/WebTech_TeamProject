<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Account Details</title>
</head>
<body>

    <?php 
        // start a session
        session_start();

        // establish database connection
        require("db.php");

        // import helper methods
        require("helper.php");

        // profile images directory
        $profile_image_dir = $_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/images/profile_images/";

        // check if the user is logged in
        // if not, redirect to login page
        if (!isset($_SESSION["user_id"])) {
            header("Location: login.php");
            exit();
        }

        // if the request is a GET request, load the information of the user
        if (isset($_GET["user_id"])) {
            // get the user's id from url
            $id = $_GET["user_id"];

            // retrieve user details
            $row = get_user_details($id, "user_id");

            // check if the query was successful
            // if unsuccessful, redirect to dashboard
            if ($row == -1) {
                header("Location: user_dashboard.php");
                exit();
            } 
        } 

        // if the request is a POST request, update data in db
        if (isset($_POST["update"])) {

            // collect form data
            $firstname = $_POST["firstname"];
            $lastname = $_POST["lastname"];
            $email = $_POST["email"];
            $image = $_FILES["image"]["name"];

            // Goal: delete an old profile picture once a new one is uploaded
            // retrieve user details
            $user_details = get_user_details($_SESSION["user_id"], "user_id");

            if ($user_details == -1) {
                header("Location: user_dashboard.php");
                exit();
            }

            // store the image in profile_images folder
            if (!is_uploaded_file($_FILES["image"]["tmp_name"])) {
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $profile_image_dir . $_SESSION["user_id"] . "-" . $image)) {
                    echo '<script>alert("Failed to upload image!")</script>';
                }
            }
            
            // get the old profile picture
            $old_profile = $user_details["profile_image"];

            // check if user has a profile
            // if user has a profile, go ahead and check if a new profile was provided
            if ($old_profile != NULL) {
                
                // if no new file was provided, skip deleting old profile image
                // else, go ahead and delete old profile image before new one is inserted
                if (!is_uploaded_file($_FILES["image"]["tmp_name"])) {
                    $image = $old_profile;
                    echo '<script>alert("No file chosen!")</script>';
                } else {
                    // create image directory link
                    $old_profile_link = $profile_image_dir . $user_details["user_id"] . "-" . $old_profile;
                    
                    // delete the old image before new image is stored
                    unlink($old_profile_link);

                    echo '<script>alert("Old profile deleted!")</script>';
                }
            } 

            // write update query
            $sql = $connection->prepare("UPDATE account SET firstname=?, lastname=?, 
            email=?, profile_image=? WHERE user_id=?");
            $sql->bind_param("ssssi", $firstname, $lastname, $email, $image, $_SESSION["user_id"]);
            $sql->execute();

            // check if the query was successful
            if ($sql->affected_rows > 0) {
                // alert user on update
                echo '<script>alert("Update successful!")</script>';

                // redirect user to update page
                header("Location: update_account.php?user_id=" . $_SESSION["user_id"]);
                exit();
            } else {
                echo '<script>alert("No new info provided!")</script>';
            }
        }
    ?>

    <h1>Update</h1>
    <p>Please fill this form to update account details</p>
    <hr>

    <form method="POST" enctype="multipart/form-data">
        <label for="user-id"><b>User ID</b></label>
        <input type="text" name="user_id" id="User_id" 
            readonly="readonly" value='<?php echo $row["user_id"] ?>' required>
        <br><br>
        
        <label for="firstname"><b>Firstname</b></label>
        <input type="text" name="firstname" id="firstname" 
            value='<?php echo $row["firstname"] ?>' required>
        <br><br>

        <label for="lastname"><b>Lastname</b></label>
        <input type="text" name="lastname" id="lastname" 
            value='<?php echo $row["lastname"] ?>' required>
        <br><br>

        <label for="email"><b>Email</b></label>
        <input type="email" name="email" id="email" 
            value='<?php echo $row["email"] ?>' required>
        <br><br>

        <label for="image"><b>Profile Image</b></label>
        <img width="20px" height="20px" src='<?php echo "/WebTech_TeamProject/images/profile_images/" . $row["user_id"] . "-" . $row["profile_image"] ?>'>
        <input type="file" name="image" id="image">
        <br><br>

        <label for="password"><b>Password</b></label>
        <input type="password" name="password" id="password" 
            readonly="readonly" value='<?php echo $row["user_password"] ?>' required>
        <br><br>

        <label for="start-date"><b>Start Date</b></label>
        <input type="datetime-local" name="start_date" id="start_date" 
            readonly="readonly" value='<?php echo $row["start_date"] ?>' required>
        <br><br>

        <label for="last-login"><b>Last Login</b></label>
        <input type="datetime-local" name="last_login" id="last_login" 
            readonly="readonly" value='<?php echo $row["last_login"] ?>' required>
        <br><br>
        
        <hr>

        <button type="submit" class="update-btn" name="update" value="update">Update</button>
    </form>

    <br>
    <a href="add_social.php?user_id=<?php echo $_SESSION["user_id"]; ?>">Add social</a>

    <br>
    <a href="user_dashboard.php">Back</a>
</body>
</html>