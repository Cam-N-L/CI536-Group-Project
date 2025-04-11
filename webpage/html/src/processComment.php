<?php
  session_start();
  include 'config.php';
  include 'testInput.php'; 

if (isset($_SESSION["username"])) {
    $users = $_SESSION["username"];
    } else {
        header("Refresh:0; url=https://ik346.brighton.domains/groupProjectTests/html/public/signin.php");
        exit();
    }

    if (isset($_SESSION["previousPage"])) {
        $url = $_SESSION["previousPage"];
      }

$Err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["comment"])) {
        $Err = "Game title required";
    } else {
        $comment = test_input($_POST["comment"]);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($Err)) { 
    $likes = 0;
    $review_id = $_POST['reviewID'];
    $query = $conn->prepare("INSERT INTO `CommentsTable`(`ReviewID`, `Username`, `Comments`, `Likes`) VALUES (?, ?, ?, ?);");
    $query->bind_param("sssi", $review_id, $users, $comment, $likes);
    if ($query->execute()) { 
    }
    header("Refresh:0; url=" . $url);
}
    ?>