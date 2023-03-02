<?php

    /**
     * returns the owner of the current php script file
     */
    function get_owner() {
        $username = get_current_user(); 
        // split user's name on .
        $name_split = explode(".", $username);

        // store the firstname and lastname and capitalize it
        $firstname = ucfirst($name_split[0]);
        $lastname = ucfirst($name_split[1]);

        // return the result
        return [$firstname, $lastname];
    }

    /**
     * returns the details (entire tuple) of a user
     * @param value the value of the specified attribute (e.g 1, r@project.com)
     * @param attribute the attribute to be used for the filter (e.g. user_id, email)
     * @return -1 when unsuccessful and a dictionary of user details when successful
     */
    function get_user_details($value, $attribute) {
        // establish db connection
        require('db.php');
    
        // write query to retrieve user details
        if ($attribute == "user_id") {
            $sql = $connection->prepare("SELECT * FROM account WHERE $attribute=?");
            $sql->bind_param("i", $value);
        } else if ($attribute == "email") {
            $sql = $connection->prepare("SELECT * FROM account WHERE $attribute=?");
            $sql->bind_param("s", $value);
        } else {
            return -1;
        }

        // execute the query
        $sql->execute();
        $result = $sql->get_result();

        // return the dictionary if successful and -1 otherwise
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            return -1;
        }
    }

    /**
     * 
     */
    function get_all_users() {
        // establish db connection
        require("db.php");

        // write query to retrieve all users
        $sql = $connection->prepare("SELECT * FROM account");
        $sql->execute();
        $result = $sql->get_result();

        // check if the query worked
        if ($result->num_rows > 0) {
            $counter = 0;  // counter
            // loop through returned data and fetch the records
            while ($row = $result->fetch_assoc()) {
                echo ++$counter . '. ' . ucfirst($row["firstname"]) . ' ' . ucfirst($row["lastname"]) .
                ' ' . $row["email"] . ' <a href=delete_account.php?user_id=' . $row["user_id"] . '>Delete</a><br>';
            }
        } else {
            return -1;
        }

        return 0;
    }


    function get_project_details($value, $attribute) {
        // establish db connection
        require('db.php');
    
        // write query to retrieve project details
        if ($attribute == "project_id") {
            $sql = $connection->prepare("SELECT * FROM project WHERE $attribute=?");
            $sql->bind_param("i", $value);
        } else if ($attribute == "project_name") {
            $sql = $connection->prepare("SELECT * FROM project WHERE $attribute=?");
            $sql->bind_param("s", $value);
        } else if ($attribute == "user_id") {
            $sql = $connection->prepare("SELECT * FROM project WHERE $attribute=?");
            $sql->bind_param("i", $value);
        } else {
            return -1;
        }

        // execute the query
        $sql->execute();
        $result = $sql->get_result();

        // return the dictionary if successful and -1 otherwise
        if ($result->num_rows > 0) {
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
            } else {
                $row = $result->fetch_all();
            }
            return $row;
        } else {
            return -1;
        }
    }


    function get_admin_role() {
        // establish db connection
        require("db.php");

        // default role details
        $role_name = "Admin";
        $role_description = "The admin of the project.";

        // select query to retrieve admin role details
        $get_sql = $connection->prepare("SELECT * FROM project_role WHERE role_name=? AND role_description=?");
        $get_sql->bind_param("ss", $role_name, $role_description);
        $get_sql->execute();

        $result = $get_sql->get_result();

        if ($result->num_rows > 0) {
            $role_details = $result->fetch_assoc();
            return $role_details;
        } else {
            return -1;
        }
    }
    
    function get_role_details($value) {
        // establish db connection
        require("db.php");

        // select query to retrieve admin role details
        $get_sql = $connection->prepare("SELECT * FROM project_role WHERE role_id=?");
        $get_sql->bind_param("i", $value);
        $get_sql->execute();
        $result = $get_sql->get_result();

        if ($result->num_rows > 0) {
            $role_details = $result->fetch_assoc();
            return $role_details;
        } else {
            return -1;
        }
    }


    function get_deleted_projects($user_id) {
        // establish db connection
        require("db.php");

        // deleted project status is 1
        $IS_DELETED = 1;

        // select query 
        $get_sql = $connection->prepare("SELECT * FROM project WHERE is_deleted=? AND user_id=?");
        $get_sql->bind_param("ii", $IS_DELETED, $user_id);
        $get_sql->execute();
        $result = $get_sql->get_result();

        // check if the query was successful
        if ($result->num_rows > 0) {
            $deleted_projects = $result->fetch_all();
            return $deleted_projects;
        } else {
            return -1;
        }
    }
?>