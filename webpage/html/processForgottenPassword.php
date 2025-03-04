<?php
  session_start();
  include 'config.php';  

  error_reporting(-1);
  ini_set('display_errors', 'On');
  set_error_handler("var_dump");

  $table = 'UserTable';
  $usercolumn = 'Username';
  $passcolumn = 'Password';

  $unameErr = $pwordErr = "";
  $uname = $pword = $confirmpword = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    unset($_SESSION["errorpassword"]);

    if (empty($_POST["uname"])) {
        $_SESSION["errorpassword"] = "Username is required";
    } else {
        $uname = test_inputs($_POST["uname"]);
    }

    if (empty($_POST["password"])) {
        $_SESSION["errorpassword"] = "Password is required";
    } else {
        $pword = test_inputs($_POST["password"]);
    }

    if (empty($_POST["confirmPassword"])) {
        $_SESSION["errorpassword"] = "Password is required";
    } else {
        $confirmpword = test_inputs($_POST["confirmPassword"]);
    }

    if ($pword !== $confirmpword) {
        $_SESSION["errorpassword"] = "Passwords must match";
    }

    if (!isset($_SESSION["errorpassword"])) {
        $query = $conn->prepare("SELECT Firstname FROM UserTable WHERE Username = ?");
        $query->bind_param("s", $uname);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $updateQuery = $conn->prepare("UPDATE UserTable SET Password = ? WHERE Username = ?");
            $updateQuery->bind_param("ss", $pword, $uname);
            if ($updateQuery->execute()) {
                header("Location: https://ik346.brighton.domains/groupProjectTests/html/signin.php");
                exit();
            } else {
                $_SESSION["errorpassword"] = "Error updating password. Please try again.";
            }
        } else {
            $_SESSION["errorpassword"] = "Username doesn't exist.";
        }
    }

    header("Refresh:0; url=https://ik346.brighton.domains/groupProjectTests/html/forgottenPassword.php");
    exit();
  }

  function test_inputs($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>
