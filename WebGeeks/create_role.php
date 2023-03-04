<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Role</title>
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
        require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/Account/db.php");

        // import helper methods
        require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/Account/helper.php");

        // retrieve user details
        $user_details = get_user_details($_SESSION["user_id"], "user_id");

        // check if request is valid
        if (isset($_POST["create-role"])) {
            
            // collect form data
            $role_name = $_POST["role-name"];
            $role_description = $_POST["role-description"];

            // Goal: check if the same name and role description exist
            // if it does, don't create a new record. Instead reference the id of the existing role

            // select role with same name and description
            $get_sql = $connection->prepare("SELECT * FROM project_role WHERE role_name=? AND role_description=?");
            $get_sql->bind_param("ss", $role_name, $role_description);
            $get_sql->execute();
            $role_details = $get_sql->get_result();

            // check if the query was successful
            // if data unique, insert into db
            if ($role_details->num_rows <= 0) {
                // insert query
                $insert_sql = $connection->prepare("INSERT INTO project_role (role_name, role_description) VALUES (?, ?)");
                $insert_sql->bind_param("ss", $role_name, $role_description);
                $insert_sql->execute();

                // check if the query was successful
                if ($insert_sql->affected_rows > 0) {
                    echo '<script>alert("Role created successfully!")</script>';

                    // retrieve details
                    // select role with same name and description
                    $get_sql = $connection->prepare("SELECT * FROM project_role WHERE role_name=? AND role_description=?");
                    $get_sql->bind_param("ss", $role_name, $role_description);
                    $get_sql->execute();
                    $role_details = $get_sql->get_result();
                } else {
                    header("Location: create_role.php");
                    exit();
                }
            }

            $role_info = $role_details->fetch_assoc();

            // insert the role_id and user_id in user_roles
            $sql = $connection->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
            $sql->bind_param("ii", $_SESSION["user_id"], $role_info["role_id"]);
            $sql->execute();

            // check if the query was successful
            if ($sql->num_rows > 0) {
                echo '<script>alert("User role created successfully!")</script>';
            } else {
                header("Location: create_role.php");
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
                    <h3 style="padding-left: 1em" class="description">Create Role</h3>
                </nav>

                <div class="container-fluid">
                    <div class="content">

                        <form method="POST" action="">
                            <label style="width:100px;" for="role-name"><b>Role name:</b></label>
                            <input style="margin-left:26px;"type="text" placeholder="Enter role name" name="role-name" id="role-name" required>
                            <br><br>

                            <label style="width:130px;" for="role-description"><b>Role Description:</b></label>
                            <input type="role-description" placeholder="Enter role description" name="role-description" id="role-description" required>
                            <br><br>

                            <button id="accept" class="btn btn-primary" type="submit" name="create-role">Create Role</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>