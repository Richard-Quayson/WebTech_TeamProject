<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Member Request</title>
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

        // ensure that the get request is valid
        if (isset($_GET["user_id"]) && isset($_GET["project_id"])) {
            // collect the user_id and project_id
            $user_id = $_GET["user_id"];
            $project_id = $_GET["project_id"];

            // retrieve user details
            $member_details = get_user_details($user_id, "user_id");
        } else {
            header("Location: user_dashboard.php");
            exit();
        }

        // ensure that the POST request is valid
        if (isset($_POST["accept-member"]) && isset($_POST["role"])) {
            // ensure that the person making the request is an admin
            $is_admin = is_project_admin($_SESSION["user_id"], $project_id);

            if ($is_admin) {
                // collect form data
                $role_id = $_POST["role"];

                echo "delete_request.php/project_id=" . $project_id . "&action=delete-request&user_id=" . $user_id;

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
                header("Location: user_dashboard.php");
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
            <div id="content">
                <nav id = "mybar" class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <h3 style="padding-left: 1em" class="description">Accept Request</h3>
                </nav>
                

                <div class="container-fluid">
                    <div class="content">

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

                            <button id="accept" class="btn btn-primary" type="submit" name="accept-member" value="accept-member">Accept</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>