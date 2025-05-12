<?php
session_start();
include ("config.php");

error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");

$users = $_SESSION["username"] ?? "";
$friend = $_POST["reviewer"] ?? "";
if (isset($_SESSION["targetUser"])){
    $friend = $_SESSION["targetUser"];
}
$buttonContents = $_POST["buttonContent"] ?? "";
$date = date("Y-m-d H:i:s");

if ($_SERVER["REQUEST_METHOD"] == "POST" && $users && $friend) {
    if ($buttonContents === "remove") {
        $friendQuery = $conn->prepare("DELETE FROM `friendsLink` WHERE (`UserOne` = ? AND `UserTwo` = ?) OR (`UserOne` = ? AND `UserTwo` = ?)");
        $friendQuery->bind_param("ssss", $users, $friend, $friend, $users);
        $friendQuery->execute();
    } elseif ($buttonContents === "accept") {
        $friendQuery = $conn->prepare("UPDATE `friendsLink` SET `relationshipType` = 'friends', `DateAltered` = ? WHERE (`UserOne` = ? AND `UserTwo` = ?) OR (`UserOne` = ? AND `UserTwo` = ?)");
        $friendQuery->bind_param("sssss", $date, $users, $friend, $friend, $users);
        $friendQuery->execute();
    } elseif ($buttonContents === "send") {
        $friendQuery = $conn->prepare("INSERT INTO `friendsLink` (`UserOne`, `UserTwo`, `relationshipType`, `DateAltered`) VALUES (?, ?, 'requested', ?)");
        $friendQuery->bind_param("sss", $users, $friend, $date);
        $friendQuery->execute();
    }

    // Redirect back to previous page
    $url = $_SESSION["previousPage"] ?? "http://localhost/checkpoint/html/public/activity.php";
    header("Location: $url");
    exit;
}

?>