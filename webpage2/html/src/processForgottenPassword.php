<?php
  session_start();
  include 'config.php';  
  include 'testInput.php';

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
        $uname = test_input($_POST["uname"]);
    }

    if (empty($_POST["password"])) {
        $_SESSION["errorpassword"] = "Password is required";
    } else {
        $pword = test_input($_POST["password"]);
    }

    if (empty($_POST["confirmPassword"])) {
        $_SESSION["errorpassword"] = "Password is required";
    } else {
        $confirmpword = test_input($_POST["confirmPassword"]);
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
            $hashedPword = password_hash($pword, PASSWORD_DEFAULT);
            $updateQuery = $conn->prepare("UPDATE UserTable SET Password = ? WHERE Username = ?");
            $updateQuery->bind_param("ss", $hashedPword, $uname);
            if ($updateQuery->execute()) {
                header("Location: http://localhost/checkpoint/html/public/signin.php");
                exit();
            } else {
                $_SESSION["errorpassword"] = "Error updating password. Please try again.";
            }
        } else {
            $_SESSION["errorpassword"] = "Username doesn't exist.";
        }
    }

    header("Location: http://localhost/checkpoint/html/public/forgottenPassword.php");
    exit();
  }
?>
