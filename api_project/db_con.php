<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// $servername = "tinnawong.bowlab.net";
// $username = "u583789277_tinnawong";
// $password = "SCbooking2567";
// $dbname = "u583789277_tinnawong";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}