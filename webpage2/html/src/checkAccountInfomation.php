<?php

    $fname = $sname = $uname = $email = $pword = "";
    $process = "";
  // database values
  function checkInfomation($conn){
  global $fname, $sname, $uname, $email, $pword, $process, $usernames;

    $table = 'UserTable';
  $usercolumn = 'Username';
  $emailcolumn = 'Email';

  $error = "";

  if ($process == "editing"){
    include 'gatherProfileInfomation.php';
    $pastEmail = $row['Email'];
    $usernames = $row['Username'];
  } else {
    $pastEmail = "";
  }

  // Check if form is submitted
    $ran = False;
    if (empty($_POST["fname"])) {
    $error = "First name is required";
    } else {
      $fname = test_input($_POST["fname"]);
    }

    if (empty($_POST["sname"])) {
        $error = "Last name is required";
    } else {
      $sname = test_input($_POST["sname"]);
    }

    if (empty($_POST["email"])) {
        $error = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if ($email != $pastEmail){
            $query = $conn->prepare("SELECT 1 FROM `$table` WHERE `$emailcolumn` = ? LIMIT 1");
            $query->bind_param("s", $email);
            $query->execute();
            $query->store_result();
            if ($query->num_rows > 0) {
                $error = "email already in use";
            }else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email format";
            }
        }
    }

    if (empty($_POST["uname"])) {
        $error = "Username is required";
    } else {
        $uname = test_input($_POST["uname"]);
        if (isset($_SESSION["username"]) && $uname == $_SESSION["username"]){
        } else {
            $query = $conn->prepare("SELECT 1 FROM `$table` WHERE `$usercolumn` = ? LIMIT 1");
            $query->bind_param("s", $uname);
            $query->execute();
            $query->store_result();
            if ($query->num_rows > 0) {
                $error = "Username is already taken";
            }
        }
        }

    if ($process == "signup"){
        if (empty($_POST["password"])) {
            $error = "Password is required";
        } else {
          $pword = test_input($_POST["password"]);
        }
    }
    return $error;
  }

?>