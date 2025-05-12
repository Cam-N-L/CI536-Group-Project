<?php
session_start();
include 'config.php';  
include 'testInput.php';
include 'checkAccountInfomation.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $uname = test_input($_POST["Username"]);
  $fname = test_input($_POST["Firstname"]);
  $sname = test_input($_POST["Surname"]);
  $email = test_input($_POST["Email"]);
  $usernames = $_SESSION["username"];

  $error = checkInfomation($conn);
  if ($error == "") {
    $query = $conn->prepare("UPDATE `UserTable` SET `Username` = ?, `Firstname` = ?, `Surname` = ?, `Email` = ? WHERE `Username` = ?");
    $query->bind_param("sssss", $uname, $fname, $sname, $email, $usernames);

    if ($query->execute() === TRUE) {
      $_SESSION["username"] = $uname;
      header("Location: http://localhost/checkpoint/html/public/signin.php");
      exit();
    } else {
      $_SESSION["erroredit"] = "Database update failed.";
      header("Location: http://localhost/checkpoint/html/public/editprofile.php");
      exit();
    }
  } else {
    $_SESSION["erroredit"] = $error;
    header("Location: http://localhost/checkpoint/html/public/editprofile.php");
    exit();
  }
}
?>
