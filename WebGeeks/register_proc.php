<?php
    // establish connection with the database
    require("db.php");

    // check if the form was submitted from the register page
    // if it was, continue else redirect to register page
    if (isset($_POST['register'])) {

        // collect the values from the form
        $firstname = $_POST["firstname"];
        $lastname = $_POST["lastname"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm_password = $_POST["password-repeat"];
        $account_type = $_POST["account-type"];

        if ($password != $confirm_password) {
            header("Location: SignUp.php");
            exit();
        } 

        // encrypt the password before storing it in db
        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
        
        // insert into db
        // by default, is_active is True
        // by default, start_date and last_login is the current timestamp
        // using the prepare method of the server connection to prevent sql
        // injections to some extent
        $sql = $connection->prepare("INSERT INTO account (firstname, lastname, email, 
            user_password, account_type) VALUES (?, ?, ?, ?, ?)");
        $sql->bind_param("ssssi", $firstname, $lastname, $email, $encrypted_password, 
            $account_type);

         // execute query
         $sql->execute();

         // if successful, redirect to login page
         // else redirect to register page
         if ($sql->affected_rows > 0) {
            header("Location: Login.php");
            exit();
         } else {
            header("Location: SignUp.php");
            exit();
         }
    } else {
        header("Location: SignUp.php");
        exit();
    }
?>