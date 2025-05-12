<?php
  session_start();
  include 'config.php';

  error_reporting(-1);
  ini_set('display_errors', 'On');
  set_error_handler("var_dump");

  $commentID = $_POST['commentID'];

  if (isset($_SESSION["previousPage"])) {
    $url = $_SESSION["previousPage"];
  }

  if (isset($_SESSION["username"])) {
    $users = $_SESSION["username"];
    } else {
        header("Location: http://localhost/checkpoint/html/public/signin.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $query = $conn->prepare("SELECT * FROM `LikesForCommentsTable` WHERE `Username` = ? AND `CommentID` = ?");
        $query->bind_param("si", $users, $commentID);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            $query = $conn->prepare("DELETE FROM `LikesForCommentsTable` WHERE `Username` = ? AND `CommentID` = ?;");
            $query->bind_param("si", $users, $commentID);
            if ($query->execute()) {
                $stmt = $conn->prepare("UPDATE `CommentsTable` SET `Likes` = `Likes` - 1 WHERE `CommentID` = ?;");
                $stmt->bind_param("i", $commentID);
                if ($stmt->execute()) {
              }
          }
        } else {
            $date = date("Y-m-d H:i:s");
            $stmt = $conn->prepare("INSERT INTO `LikesForCommentsTable` (CommentID, Username, DateLiked) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $commentID, $users, $date);
            if ($stmt->execute()) {
                $stmt = $conn->prepare("UPDATE `CommentsTable` SET `Likes` = `Likes` + 1 WHERE `CommentID` = ?;");
                $stmt->bind_param("i", $commentID);
                if ($stmt->execute()) {
              }
        } 
      }
    header("Refresh:0; url=" . $url);
}

  ?>