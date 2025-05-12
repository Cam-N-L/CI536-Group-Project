<?php
  session_start();
  include 'config.php';
  include 'testInput.php'; 

if (isset($_SESSION["username"])) {
    $users = $_SESSION["username"];
    } else {
        header("Location: http://localhost/checkpoint/html/public/signin.php");
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
    $date = date("Y-m-d H:i:s");
    $review_id = $_POST['reviewID'];
    $query = $conn->prepare("INSERT INTO `CommentsTable`(`ReviewID`, `Username`, `Comments`, `Likes`, `DateCommented`) VALUES (?, ?, ?, ?, ?);");
    $query->bind_param("sssis", $review_id, $users, $comment, $likes, $date);
    if ($query->execute()) { 
    }
    header("Refresh:0; url=" . $url);
}
    ?>