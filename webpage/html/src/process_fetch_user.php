<?php
session_start();
include ("config.php");

error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");

$table = 'UserTable';
$user = "";
if (isset($_SESSION["username"])) {
    $user = $_SESSION["username"];
}
$_SESSION["previousPage"] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']
    === 'on' ? "https" : "http") . "://" . 
    $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?Username=" . ($_GET['Username']);
    
if (isset($_GET['Username'])) {
    $username = $_GET['Username'];
    if ($user == trim($username, "\"")){
        header("Refresh:0; url=https://ik346.brighton.domains/groupProjectTests/html/public/profile.php");
    }
    // Prepare SQL query to fetch a specific user by name
    $sql = "SELECT * FROM $table WHERE `Username` = $username";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        include('../templates/user_template.php');
    } else {
        echo "user not found.";
    }
} else {
    echo "No user selected.";
}

$conn->close();
?>