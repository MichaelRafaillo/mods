<?php
$servername = "localhost";
$username = "marlysilver_marlysilver";
$password = "WELRNWEJ833";
$dbname = "marlysilver_ods";
$conn = new mysqli($servername, $username, $password, $dbname);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the character set to UTF-8
if (!$conn->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $conn->error);
    exit();
}
