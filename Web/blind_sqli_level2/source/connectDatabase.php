<?php
    // Use environment variables for Docker, fallback to localhost for local development
    $db_host = getenv('DB_HOST') ?: 'localhost';
    $db_username = getenv('DB_USER') ?: 'root';
    $db_password = getenv('DB_PASS') ?: '';
    $mysqli = new mysqli($db_host, $db_username, $db_password);
    if($mysqli->connect_errno){
        printf("Connect failed: %s\n", $mysqli->connect_error);
        exit();
    }