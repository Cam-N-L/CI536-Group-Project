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
            $buttonContents = "<button> remove friend </button>";
        } else{
            $query = $conn->prepare("SELECT `relationshipType` FROM `friendsLink` WHERE (`UserOne` = ? AND `UserTwo` = ?)");
            $query->bind_param("ss", $users, $user['Username']);
            $query->execute();
            $result = $query->get_result();
            if ($result->num_rows > 0) {
                $buttonContents = "<button> friend request sent </button>";
            } else {
                $buttonContents = "<button> accept friend request? </button>";
            }
    }} else {
        $buttonContents = "<button> send friend request </button>";
    }
  } else {
  }

  $_SESSION["Buttoncontent"] = $buttonContents;
  ?>