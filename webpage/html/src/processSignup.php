<?php
  session_start();
  include 'config.php';  
  include 'testInput.php';
  include 'checkAccountInfomation.php';

  $ran = True;
  $process = "signup";
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

// Display submitted data after successful form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $error = checkInfomation($conn);
  if ($error == ""){
    $sql = "INSERT INTO UserTable
    VALUES ('$uname', '$pword', '$fname', '$sname', 0, 0, '$email')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION["username"] = $uname;
        if (isset($_SESSION["previousPage"])) {
          $url = $_SESSION["previousPage"];
          header("Refresh:0; url=" . $url);
        } else{
          header("Refresh:0; url=https://ik346.brighton.domains/groupProjectTests/html/public/profile.php");
        } 
      }
  } else {
    $_SESSION["errorSignup"] = $error;
    $ran = True;
    session_write_close();
    header("Location: https://ik346.brighton.domains/groupProjectTests/html/public/signup.php");
    exit();
  }}
?>