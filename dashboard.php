<?php
// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'my_data';

// Connect to MySQL
$mysqli = new mysqli($host, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
