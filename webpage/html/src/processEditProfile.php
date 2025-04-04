<?php
  session_start();
  include 'config.php';  
  include 'testInput.php';
  include 'checkAccountInfomation.php';

  $ran = True;
  $process = "editing";
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

// Display submitted data after successful form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $error = checkInfomation($conn);
  if ($error == ""){
    $query = $conn->prepare("UPDATE `UserTable` SET `Username` = ?, `Firstname` = ?, `Surname` = ?, `Email` = ? WHERE `Username` = ?");
    $query->bind_param("sssss", $uname, $fname, $sname, $email, $usernames);
    if ($query->execute() === TRUE) {
      $_SESSION["username"] = $uname;
  }} else {
    $_SESSION["erroredit"] = $error;
    $ran = True;
    session_write_close();
    exit();
  }
  header("Refresh:0; url=https://ik346.brighton.domains/groupProjectTests/html/public/profile.php");
}
?>