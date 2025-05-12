<?php
  session_start();
  include 'config.php';

  error_reporting(-1);
  ini_set('display_errors', 'On');
  set_error_handler("var_dump");

  $reviewID = $_POST['reviewID'];

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
        $query = $conn->prepare("SELECT * FROM `LikesForReviewsTable` WHERE `Username` = ? AND `ReviewID` = ?");
        $query->bind_param("ss", $users, $reviewID);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            $query = $conn->prepare("DELETE FROM `LikesForReviewsTable` WHERE `Username` = ? AND `ReviewID` = ?;");
            $query->bind_param("ss", $users, $reviewID);
            if ($query->execute()) {
              $stmt = $conn->prepare("UPDATE `ReviewTable` SET `NumOfLikes` = `NumOfLikes` - 1 WHERE `ReviewID` = ?;");
              $stmt->bind_param("i", $reviewID);
              if ($stmt->execute()) {
            }
          }
        } else {
            $date = date("Y-m-d H:i:s");
            $stmt = $conn->prepare("INSERT INTO `LikesForReviewsTable` (ReviewID, Username, DateLiked) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $reviewID, $users, $date);
            if ($stmt->execute()) {
              $stmt = $conn->prepare("UPDATE `ReviewTable` SET `NumOfLikes` = `NumOfLikes` + 1 WHERE `ReviewID` = ?;");
              $stmt->bind_param("i", $reviewID);
              if ($stmt->execute()) {
              }
        }
        }
    header("Refresh:0; url=" . $url);
}

  ?>