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


    // initial function
    // function get_project_details($value, $attribute) {
    //     // establish db connection
    //     require('db.php');
    
    //     // write query to retrieve project details
    //     if ($attribute == "project_id") {
    //         $sql = $connection->prepare("SELECT * FROM project WHERE $attribute=?");
    //         $sql->bind_param("i", $value);
    //     } else if ($attribute == "project_name") {
    //         $sql = $connection->prepare("SELECT * FROM project WHERE $attribute=?");
    //         $sql->bind_param("s", $value);
    //     } else {
    //         return -1;
    //     }

    //     // execute the query
    //     $sql->execute();
    //     $result = $sql->get_result();

    //     // return the dictionary if successful and -1 otherwise
    //     if ($result->num_rows > 0) {
    //         if ($result->num_rows == 1) {
    //             $row = $result->fetch_assoc();
    //         } else {
    //             $row = $result->fetch_all();
    //         }
    //         return $row;
    //     } else {
    //         return -1;
    //     }
    // }



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


    function get_user_projects($user_id) {
        // establish db connection
        require("db.php");

        // select query to retrieve all user projects
        $get_sql = $connection->prepare("SELECT * FROM project_members WHERE user_id=?");
        $get_sql->bind_param("i", $user_id);
        $get_sql->execute();
        $result = $get_sql->get_result();

        if ($result->num_rows > 0) {
            $user_projects = $result->fetch_all();
            return $user_projects;
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

    function get_project_member($user_id, $project_id) {
        // establish db connection
        require("db.php");

        // check if user is a project member
        $select_sql = $connection->prepare("SELECT * FROM project_members WHERE user_id=? AND project_id=?");
        $select_sql->bind_param("ii", $user_id, $project_id);
        $select_sql->execute();
        $result = $select_sql->get_result();

        if ($result->num_rows > 0) {
            $project_member = $result->fetch_assoc();
            return $project_member;
        } else {
            return -1;
        }
    }

    function is_project_admin($user_id, $project_id) {
        // establish db connection
        require("db.php");

        // retrieve admin role id
        $admin_role_details = get_admin_role();

        // retrieve project member
        $project_member = get_project_member($user_id, $project_id);

        // if not project member, return false
        if ($project_member != -1) {
            // if project member, check if the member is an admin
            // check if member role is admin
            if ($project_member["role_id"] == $admin_role_details["role_id"]) {
                return true;
            } else {
                return false;
            }
        } else {
            return -1;
        }
    }

    function get_project_members($project_id) {
        // establish db connection
        require("db.php");

        // retrieve all project members
        $get_members = $connection->prepare("SELECT * FROM project_members WHERE project_id=?");
        $get_members->bind_param("i", $project_id);
        $get_members->execute();
        $members = $get_members->get_result();

        // check if the query was successful
        if ($members->num_rows > 0) {
            // fetch associated data
            $project_members = $members->fetch_all();
            return $project_members;
        } else {
            return -1;
        }
    }

    function get_requested_project_members($project_id) {
        // establish db connection
        require("db.php");

        // retrieve all people who have requested to join the project
        $get_requested_members = $connection->prepare("SELECT * FROM requested_project WHERE project_id=?");
        $get_requested_members->bind_param("i", $project_id);
        $get_requested_members->execute();
        $members = $get_requested_members->get_result();

        // check if the query was successful
        if ($members->num_rows > 0) {
            // fetch associated data
            $requested_members = $members->fetch_all();
            return $requested_members;
        } else {
            return -1;
        }
    }

    function get_user_defined_roles($user_id) {
        // establish db connection
        require("db.php");

        // retrieve all roles belonging to the specified user
        $get_roles = $connection->prepare("SELECT * FROM user_roles WHERE user_id=?");
        $get_roles->bind_param("i", $user_id);
        $get_roles->execute();
        $roles = $get_roles->get_result();

        // check if the query was successful
        if ($roles->num_rows > 0) {
            // fetch associated data
            $user_roles = $roles->fetch_all();
            return $user_roles;
        } else {
            return -1;
        }
    }

    function get_invited_member($email, $project_id) {
        // establish db connection
        require("db.php");

        // retrieve invited member details
        $get_invited_member = $connection->prepare("SELECT * FROM invited_members WHERE email=? AND project_id=?");
        $get_invited_member->bind_param("si", $email, $project_id);
        $get_invited_member->execute();
        $member = $get_invited_member->get_result();

        // check if the query was successful
        if ($member->num_rows > 0) {
            // fetch associated data
            $invited_member = $member->fetch_assoc();
            return $invited_member;
        } else {
            return -1;
        }
    }
?>