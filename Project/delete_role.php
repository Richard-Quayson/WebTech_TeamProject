<?php 
    // start a session
    session_start();

    // check if the user is logged in
    if (!isset($_SESSION["user_id"])) {
        header("Location: /WebTech_TeamProject/Account/user_dashboard.php");
        exit();
    }

    // establish db connection
    require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/Account/db.php");

    // import helper methods
    require($_SERVER['DOCUMENT_ROOT'] . "/WebTech_TeamProject/Account/helper.php");

    // ensure the delete request is valid
    if (isset($_GET["role_id"]) && isset($_GET["action"]) == "delete") {
        // ensure the user requesting the delete created the role
        $get_sql = $connection->prepare("SELECT * FROM user_roles WHERE role_id=?");
        $get_sql->bind_param("i", $_GET["role_id"]);
        $get_sql->execute();
        $row = $get_sql->get_result();

        // check if the request was successful
        if ($row->num_rows > 0) {
            // fetch associated result
            $user_role = $row->fetch_assoc();

            // compare user_ids
            if ($user_role["user_id"] == $_SESSION["user_id"]) {

                // delete query
                $delete_sql = $connection->prepare("DELETE FROM user_roles WHERE user_id=? AND role_id=?");
                $delete_sql->bind_param("ii", $_SESSION["user_id"], $user_role["role_id"]);
                $delete_sql->execute();

                // check if the query was successful
                if ($delete_sql->affected_rows > 0) {
                    echo '<script>alert("Role deleted successfully!")</script>';

                    header("Location: /WebTech_TeamProject/Account/user_dashboard.php");
                    exit();
                } else {
                    echo '<script>alert("Delete request failed!")</script>';
                }
            } else {
                echo '<script>alert("Delete request rejected!")</script>';
            }
        } else {
            header("Location: /WebTech_TeamProject/Account/user_dashboard.php");
            exit();
        }
    }
?>