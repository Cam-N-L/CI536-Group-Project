<?php
  session_start();
  include 'config.php';  
  // print all tables
  //$listdbtables = array_column($conn->query('SHOW TABLES')->fetch_all(), 0);  
  //echo implode(", ", $listdbtables);

  $ran = True;
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  // database values
  $table = 'UserTable';
  $usercolumn = 'Username';
  $emailcolumn = 'Email';

  $fnameErr = $snameErr = $unameErr = $emailErr = $pwordErr = "";
  $fname = $sname = $uname = $email = $pword = "";

  // Check if form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    unset($_SESSION["errorSignup"]);
    $ran = False;
    if (empty($_POST["fname"])) {
    $_SESSION["errorSignup"] = "First name is required";
    } else {
      $fname = test_input($_POST["fname"]);
    }

    if (empty($_POST["sname"])) {
    $_SESSION["errorSignup"] = "Last name is required";
    } else {
      $sname = test_input($_POST["sname"]);
    }

    if (empty($_POST["email"])) {
    $_SESSION["errorSignup"] = "Email is required";
    } else {
      $email = test_input($_POST["email"]);
      $query = $conn->prepare("SELECT 1 FROM `$table` WHERE `$emailcolumn` = ? LIMIT 1");
      $query->bind_param("s", $email);
      $query->execute();
      $query->store_result();
      if ($query->num_rows > 0) {
        $_SESSION["errorSignup"] = "email already in use";
      }else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["errorSignup"] = "Invalid email format";
      }
    }

    if (empty($_POST["uname"])) {
    $_SESSION["errorSignup"] = "Username is required";
    } else {
      $uname = test_input($_POST["uname"]);
      $query = $conn->prepare("SELECT 1 FROM `$table` WHERE `$usercolumn` = ? LIMIT 1");
      $query->bind_param("s", $uname);
      $query->execute();
      $query->store_result();
      if ($query->num_rows > 0) {
        $_SESSION["errorSignup"] = "Username is already taken";
      }
    }

    if (empty($_POST["password"])) {
     $_SESSION["errorSignup"] = "Password is required";
    } else {
      $pword = test_input($_POST["password"]);
    }
  }

  // Function to sanitize input
  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

// Display submitted data after successful form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_SESSION["errorSignup"])) {
    $sql = "INSERT INTO UserTable
    VALUES ('$uname', '$pword', '$fname', '$sname', 0, 0, '$email')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION["username"] = strtolower($uname);
        if (isset($_SESSION["previousPage"])) {
          $url = $_SESSION["previousPage"];
          header("Refresh:0; url=" . $url);
        } else{
          header("Refresh:0; url=https://ik346.brighton.domains/groupProjectTests/html/profile.php");
        } 
  }} else if (isset($_SESSION["errorSignup"]) && $ran == False) {
    $ran = True;
    session_write_close();
    header("Location: https://ik346.brighton.domains/groupProjectTests/html/signup.php");
    exit();
  }
?>