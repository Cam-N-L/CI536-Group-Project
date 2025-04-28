<?php
  session_start();
  include 'config.php';

  error_reporting(-1);
  ini_set('display_errors', 'On');
  set_error_handler("var_dump");

  $user = $_SESSION["username"];

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["gameList"])){
        header("Refresh:0; url=https://ik346.brighton.domains/groupProjectTests/html/public/editProfile.php");
    } else {
        $games = explode(',', $_POST["gameList"]);
        $indexes = "[";
        foreach ($games as $g){
            $stmt = $conn->prepare("SELECT `Index` FROM `GamesInfo` WHERE `Title` = ?");
            $stmt->bind_param("s", $g);
              if ($stmt->execute()) {
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $indexes .= $row["Index"] . ",";
              }
        }
        $indexes = rtrim($indexes, ',');
        $indexes .= "]";
        $stmt = $conn->prepare("UPDATE `UserTable` SET `Favourites`=? WHERE `Username` = ?;");
        $stmt->bind_param("ss", $indexes, $user);
        if ($stmt->execute()) {
            header("Refresh:0; url=https://ik346.brighton.domains/groupProjectTests/html/public/editProfile.php");
        }
    }
  }

  ?>