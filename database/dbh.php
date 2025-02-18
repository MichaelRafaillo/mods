<?php
$servername = "sql312.infinityfree.com";
$username = "if0_36185835";
$password = "hU9KNXekGp";
$dbname = "if0_36185835_marlyods";
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
