<?php
  if (isset($_SESSION["username"])) {
    $usernames = $_SESSION["username"];
  }

  error_reporting(-1);
  ini_set('display_errors', 'On');
  set_error_handler("var_dump");

    $stmt = $conn->prepare("SELECT `Email`,`Firstname`,`Surname`,`Favourites`, `Username` FROM UserTable WHERE Username = ?");
    $stmt->bind_param("s",$usernames);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      // Fetch the first row
      $row = $result->fetch_assoc();
    }
    ?>