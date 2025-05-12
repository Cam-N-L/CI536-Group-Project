<?php
include ("config.php");

$table = 'UserTable';
$buttonContents = "";
if (isset($_SESSION["username"])) {
    $users = $_SESSION["username"];
    $query = $conn->prepare("SELECT `relationshipType` FROM `friendsLink` WHERE (`UserOne` = ? OR `UserTwo` = ?) AND (`UserOne` = ? OR `UserTwo` = ?)");
    $query->bind_param("ssss", $users, $users, $user['Username'], $user['Username']);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $row = implode($row);
        if ($row == "friends"){
            $buttonContents = "<button type=\"submit\" name=\"buttonContent\" value=\"remove\"> Remove friend </button>";
        } else{
            $query = $conn->prepare("SELECT `relationshipType` FROM `friendsLink` WHERE (`UserOne` = ? AND `UserTwo` = ?)");
            $query->bind_param("ss", $users, $user['Username']);
            $query->execute();
            $result = $query->get_result();
            if ($result->num_rows > 0) {
                $buttonContents = "<button type=\"submit\" name=\"buttonContent\" value=\"remove\"> Friend request sent! </button>";
            } else {
                $buttonContents = "<button type=\"submit\" name=\"buttonContent\" value=\"accept\"> Accept friend request? </button>";
            }
    }} else {
        $buttonContents = "<button type=\"submit\" name=\"buttonContent\" value=\"send\"> Send friend request </button>";
    }
  } else {
  }

  $_SESSION["Buttoncontent"] = $buttonContents;
  ?>
