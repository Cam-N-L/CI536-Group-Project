<?php
  // Enable error reporting for debugging
  session_start();
  include 'testInput.php'; 
  include '../src/config.php'; 

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

  // Display submitted data after successful form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($unameErr) && empty($pwordErr)) {
    $query = $conn->prepare("SELECT `Password`, `Username` FROM UserTable WHERE Username = ?");
    $query->bind_param("s", $uname);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
      // Fetch the first row
      $row = $result->fetch_assoc();
      if(password_verify($pword, $row['Password'])){
        $_SESSION["username"] = $row['Username'];
        if (isset($_SESSION["previousPage"])) {
          $url = $_SESSION["previousPage"];
          header("Location: " . $url);
          exit();
        } else{
          header("Location: http://localhost/checkpoint/html/public/profile.php");
        }}}
    $_SESSION["errorLogin"] = "incorrect username or password";
    header("Location: http://localhost/checkpoint/html/public/signin.php");
  }
?>
