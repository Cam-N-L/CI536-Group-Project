<?php
  session_start();
  include 'config.php';
  include 'testInput.php'; 


  error_reporting(-1);
  ini_set('display_errors', 'On');
  set_error_handler("var_dump");

  $titleErr = $reviewErr = null;
  $title = $review = $rating = $played = "";
  $user = $_SESSION["username"];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["livesearchinput"])) {
        $titleErr = "Game title required";
    } else {
        $title = test_input($_POST["livesearchinput"]);
    }
  

    if (empty($_POST["review"])) {
        $reviewErr = "please write a review";
    } else {
        $review = test_input($_POST["review"]);
    }

    if (isset($_POST['rating']) && !empty($_POST['rating'])) {
        $rating = $_POST['rating'];
  }

  if (isset($_POST['playedOrCompleted'])) {
    $played = "Completed";
} else {
    $played = "Played";
}
}

  if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($titleErr) && empty($reviewErr)) { 
    $query = "SELECT `Index` FROM GamesInfo WHERE Title = \"" . html_entity_decode($title) . "\"";
    $query = $conn->prepare($query);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $index = $row["Index"];
      $likes = 0;
      $date = date("Y-m-d H:i:s");
      $stmt = $conn->prepare("INSERT INTO ReviewTable (GameIndex, Username, Rating, Review, NumOfLikes, DateOfReview) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("isssis", $index, $user, $rating, $review, $likes, $date);
      if ($stmt->execute()) {
        $id = $conn->insert_id;
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
    }
  } else if (isset($titleErr) && isset($reviewErr)) {
    $_SESSION["errorLog"] = "please enter a title & review";
  } else {
    $_SESSION["errorLog"] = "game not in database, try the search feature!";
  }
  header("Refresh:0; url=https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_review.php?id=" . $id);
}
?>