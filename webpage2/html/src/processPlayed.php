<?php
  session_start();
  include 'config.php';
  include 'testInput.php'; 


  error_reporting(-1);
  ini_set('display_errors', 'On');
  set_error_handler("var_dump");

  $titleErr = null;
  $title = $played = "";
  $user = $_SESSION["username"];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["livesearchinputHidden"])) {
        $titleErr = "Game title required";
    } else {
        $title = test_input($_POST["livesearchinputHidden"]);
    }
    $played = isset($_POST['playedOrCompletedPlay']) ? "Completed" : "Played";
}

  if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($titleErr)) { 
    $query = "SELECT `Index` FROM GamesInfo WHERE Title = \"" . html_entity_decode($title) . "\"";
    $query = $conn->prepare($query);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $index = $row["Index"];
        $query = $conn->prepare("SELECT * FROM PlayedGames WHERE Username = ? AND GameIndex = ?");
        $query->bind_param("si", $user, $index);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          if ($row['PlayedOrCompleted'] == $played) {
          } else {
              // If it's different, update the value
              $stmt = $conn->prepare("UPDATE PlayedGames SET PlayedOrCompleted = ? WHERE Username = ? AND GameIndex = ?");
              $stmt->bind_param("ssi", $played, $user, $index);
              if ($stmt->execute()) {
              }
          }
      } else {
          // If the record doesn't exist, insert a new one
          $stmt = $conn->prepare("INSERT INTO PlayedGames (Username, GameIndex, PlayedOrCompleted) VALUES (?, ?, ?)");
          $stmt->bind_param("sis", $user, $index, $played);
          if ($stmt->execute()) {
            $stmt = $conn->prepare("UPDATE UserTable SET NumOfPlayed = NumOfPlayed + 1 WHERE Username = ?");
            $stmt->bind_param("s", $user);
            if ($stmt->execute()) {
            }
          }
      }
      $stmt = $conn->prepare("UPDATE GamesInfo SET Plays = Plays + 1, Reviews = Reviews + 1 WHERE `Index` = ?");
      $stmt->bind_param("i", $index);
      if ($stmt->execute()) {
      }
      header("Location: http://localhost/checkpoint/html/src/process_fetch_game.php?id=" . $index);
    }
  } else if (isset($titleErr) && isset($reviewErr)) {
    $_SESSION["errorLogPlayed"] = "please enter a title & review";
    header("Location: http://localhost/checkpoint/html/public/log.php");
  } else {
    $_SESSION["errorLogPlayed"] = "game not in database, try the search feature!";
    header("Location: http://localhost/checkpoint/html/public/log.php");
  }

?>