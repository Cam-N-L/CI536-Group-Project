<?php
  // Enable error reporting for debugging
  session_start();
  
    if (isset($_SESSION["errorLogin"])) {
    $Err = $_SESSION["errorLogin"];
    unset($_SESSION["errorLogin"]);
  }

  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  unset($_SESSION["username"]);
  header("Location: http://localhost/checkpoint/html/public/signin.php");
  ?>