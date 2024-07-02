<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role</title>
</head>
<body>

    <?php
        // start a session
        session_start();

        // establish db connection
        require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/Account/db.php");

        // check if user is logged in
        if (!isset($_SESSION["user_id"])) {
            header("Location: user_dashboard.php");
            exit();
        }

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

    <form method="POST" action="">
        <label for="role-name"><b>Role name:</b></label>
        <input type="text" placeholder="Enter role name" name="role-name" id="role-name" required>
        <br><br>

        <label for="role-description"><b>Role Description:</b></label>
        <input type="role-description" placeholder="Enter role description" name="role-description" id="role-description" required>
        <br><br>

        <button type="submit" id="create-role" name="create-role">Create Role</button>
    </form>
</body>
</html>