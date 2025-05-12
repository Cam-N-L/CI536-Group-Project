<?php
// MySQLi object oriented connection
$host = "localhost"; 
$dbname = "ik346_group_project"; 
$username = "ik346_issy"; 
$password = "GroupProjectAcess"; 

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>
