<?php
  // Enable error reporting for debugging
  session_start();

  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  unset($_SESSION["username"]);
  header("Refresh:0; url=https://ik346.brighton.domains/groupProjectTests/html/public/signin.php");
  ?>