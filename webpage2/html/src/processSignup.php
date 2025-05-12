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
  if (!isset($_POST['TandC'])){
    $error = "please accept the terms and conditions";
  }
  if ($error == ""){
    $pword = $_POST['password'];
    $hashedPword = password_hash($pword, PASSWORD_DEFAULT);
    $sql = "INSERT INTO UserTable
    VALUES ('$uname', '$hashedPword', '$fname', '$sname', null, 0, '$email')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION["username"] = $uname;
        if (isset($_SESSION["previousPage"])) {
          $url = $_SESSION["previousPage"];
          header("Location: " . $url);
        } else{
          header("Location: http://localhost/checkpoint/html/public/profile.php");
        } 
      }
  } else {
    $_SESSION["errorSignup"] = $error;
    $ran = True;
    session_write_close();
    header("Location: http://localhost/checkpoint/html/public/signup.php");
    exit();
  }}
?>