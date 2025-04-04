<?php
session_start();
include ("config.php");

error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");

$table = 'UserTable';
$buttonContents = "";
if (isset($_SESSION["targetUser"]) && isset($_SESSION["Buttoncontent"]) && isset($_SESSION["username"])){
    $friend = $_SESSION["targetUser"];
    $buttonContents = $_SESSION["Buttoncontent"];
    $users = $_SESSION["username"];
} else {
    header("Refresh:0; url=https://ik346.brighton.domains/groupProjectTests/html/public/home.php");
}

  if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $date = date("Y-m-d H:i:s");
    if ($buttonContents == "<button> remove friend </button>"){
        $friendQuery = $conn->prepare("DELETE FROM `friendsLink` WHERE (`UserOne` = ? AND `UserTwo` = ?) OR (`UserOne` = ? AND `UserTwo` = ?)");
        $friendQuery->bind_param("ssss", $users, $friend, $friend, $users);
        $friendQuery->execute();
    } else if ($buttonContents == "<button> accept friend request? </button>"){
        $friendQuery = $conn->prepare("UPDATE `friendsLink` SET `relationshipType` = \"friends\", `DateAltered` = ? WHERE (`UserOne` = ? AND `UserTwo` = ?) OR (`UserOne` = ? AND `UserTwo` = ?)");
        $friendQuery->bind_param("sssss", $date, $users, $friend, $friend, $users);
        $friendQuery->execute();
    } else if ($buttonContents == "<button> send friend request </button>"){
        $friendQuery = $conn->prepare("INSERT INTO `friendsLink`(`UserOne`, `UserTwo`, `relationshipType`, `DateAltered`) VALUES (?, ?, \"requested\", ?);");
        $friendQuery->bind_param("sss", $users, $friend, $date);
        $friendQuery->execute();
    }
    $url = $_SESSION["previousPage"];
    header("Refresh:0; url=" . $url . "?Username=\"" . $friend . "\"");
  }
?>