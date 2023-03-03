<?php

    // database details
    $servername = "localhost";
    $database = "test_PMS";
    $username = "root";
    $password = "";

    // creating a connection 
    $connection = new mysqli($servername, $username, $password, $database);
    
    // check if connection was successful
    if ($connection->connect_error) {
        // stop execution if server connection fails
        die("Connection to server: " . $connection->connect_error);
    }
?>