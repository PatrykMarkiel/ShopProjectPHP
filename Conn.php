<?php
$host = "localhost"; 
$username = "root"; 
$password = "";
$database = "shop"; 
$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
