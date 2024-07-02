<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Member</title>
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

        // ensure that the get request is valid
        if (isset($_GET["user_id"]) && isset($_GET["project_id"])) {
            // collect the user_id and project_id
            $user_id = $_GET["user_id"];
            $project_id = $_GET["project_id"];

            // retrieve user details
            $member_details = get_user_details($user_id, "user_id");
        } else {
            header("Location: /WebTech_TeamProject/Account/user_dashboard.php");
            exit();
        }

        // ensure that the POST request is valid
        if (isset($_POST["accept-member"]) && isset($_POST["role"])) {
            // ensure that the person making the request is an admin
            $is_admin = is_project_admin($_SESSION["user_id"], $project_id);

            if ($is_admin) {
                // collect form data
                $role_id = $_POST["role"];

                echo "/WebTech_TeamProject/Project/delete_request.php/project_id=" . $project_id . "&action=delete-request&user_id=" . $user_id;

                // insert new member into project_members
                $insert_sql = $connection->prepare("INSERT INTO project_members (user_id, project_id, role_id) VALUES (?, ?, ?)");
                $insert_sql->bind_param("iii", $user_id, $project_id, $role_id);
                $insert_sql->execute();

                // check if the query was successful
                if ($insert_sql->affected_rows > 0) {
                    // delete the user's request since it has been accepted
                    header("Location: /WebTech_TeamProject/Project/delete_request.php?user_id=" . $user_id . "&project_id=" . $project_id . "&action=delete-request");
                    exit();
                } else {
                    echo '<script>alert("Failed to accept member.")</script>';
                }
            } else {
                header("Location:  /WebTech_TeamProject/Account/user_dashboard.php");
                exit();
            }
            
        }
    ?>

    <form method="POST" >
        <label for="username"><b>Name:</b></label>
        <input type="username" name="username" id="username" readonly
            value='<?php echo $member_details["firstname"] . ' ' . $member_details["lastname"] ?>' required>
        <br><br>

        <label for="role"><b>Role:</b></label>
        <select name="role" id="role" required>
            <?php 
                // retrieve user defined roles
                $user_roles = get_user_defined_roles($_SESSION["user_id"]);

                // user_roles attributes and corresponding indexes
                $USER_ID_INDEX = 0;
                $ROLE_ID_INDEX = 1;

                // echo returned results
                echo '<option disabled selected value> -- select an option -- </option>';

                foreach ($user_roles as $role) {
                    // retrieve role details
                    $role_details = get_role_details($role[$ROLE_ID_INDEX]);

                    echo '<option value="' . $role_details["role_id"] . '">' . $role_details["role_name"] . ' </option></option>';
                }
            
            ?>
        </select>
        <br><br>

        <button type="submit" name="accept-member" value="accept-member">Accept</button>

    </form>
    
</body>
</html>