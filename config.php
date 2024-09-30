<?php
    // config.php

    // Database connection settings
    $db_host = 'localhost';
    $db_username = 'root';
    $db_password = '';
    $db_name = 'cadastros';

    // Create a PDO connection to the database
    $dsn = "mysql:host=$db_host;dbname=$db_name";
    $conn = new PDO($dsn, $db_username, $db_password);

    // Set the PDO error mode to exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>