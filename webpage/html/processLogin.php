<?php
  // Enable error reporting for debugging
  session_start();
  include 'config.php';  

  // database values
  $table = 'UserTable';
  $usercolumn = 'Username';

  // Initialize error and data variables
  $unameErr = $pwordErr = "";
  $uname = $pword = "";

  // Check if form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["uname"])) {
      $unameErr = "Username is required";
    } else {
      $uname = test_input($_POST["uname"]);
    }
  

    if (empty($_POST["password"])) {
      $pwordErr = "Password is required";
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
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($unameErr) && empty($pwordErr)) {
    $query = $conn->prepare("SELECT Firstname FROM UserTable WHERE Username = ? AND Password = ?");
    $query->bind_param("ss", $uname, $pword);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
      // Fetch the first row
      $row = $result->fetch_assoc();
      $_SESSION["username"] = $uname;
      header("Refresh:0; url=https://ik346.brighton.domains/groupProjectTests/html/profile.php");
  } else {
    $_SESSION["errorLogin"] = "incorrect username or password";
    header("Refresh:0; url=https://ik346.brighton.domains/groupProjectTests/html/signin.php");
  }
  }
?>
