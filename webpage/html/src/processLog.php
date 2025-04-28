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


  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate inputs
    $title = !empty($_POST["livesearchinput"]) ? test_input($_POST["livesearchinput"]) : ($titleErr = "Game title required");
    $review = !empty($_POST["review"]) ? test_input($_POST["review"]) : ($reviewErr = "Please write a review");
    $rating = isset($_POST["rating"]) ? intval($_POST["rating"]) : null;
    $played = isset($_POST['playedOrCompleted']) ? "Completed" : "Played";

    // If there are validation errors
    if ($titleErr || $reviewErr) {
        $_SESSION["errorLog"] = trim("$titleErr $reviewErr");
        header("Location: https://ik346.brighton.domains/groupProjectTests/html/public/log.php");
        exit;
    }

    // Check if game exists
    $query = $conn->prepare("SELECT `Index` FROM GamesInfo WHERE Title = ?");
    $query->bind_param("s", $title);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 0) {
        $_SESSION["errorLog"] = "Game not in database, try the search feature!";
        header("Location: https://ik346.brighton.domains/groupProjectTests/html/public/log.php");
        exit;
    }

    $row = $result->fetch_assoc();
    $index = $row["Index"];
    $likes = 0;
    $date = date("Y-m-d H:i:s");

    // Insert review
    $stmt = $conn->prepare("
        INSERT INTO ReviewTable (GameIndex, Username, Rating, Review, NumOfLikes, DateOfReview)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("isssis", $index, $user, $rating, $review, $likes, $date);

    if (!$stmt->execute()) {
        $_SESSION["errorLog"] = "Failed to insert review.";
        header("Location: https://ik346.brighton.domains/groupProjectTests/html/public/log.php");
        exit;
    }

    $reviewId = $conn->insert_id;

    // Check if user has a played/completed record
    $check = $conn->prepare("SELECT PlayedOrCompleted FROM PlayedGames WHERE Username = ? AND GameIndex = ?");
    $check->bind_param("si", $user, $index);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $existing = $result->fetch_assoc();
        if ($existing['PlayedOrCompleted'] !== $played) {
            // Update PlayedOrCompleted
            $update = $conn->prepare("UPDATE PlayedGames SET PlayedOrCompleted = ? WHERE Username = ? AND GameIndex = ?");
            $update->bind_param("ssi", $played, $user, $index);
            $update->execute();
        }
    } else {
        // Insert new PlayedGames record
        $insert = $conn->prepare("INSERT INTO PlayedGames (Username, GameIndex, PlayedOrCompleted) VALUES (?, ?, ?)");
        $insert->bind_param("sis", $user, $index, $played);
        if ($insert->execute()) {
            // Update user's NumOfPlayed
            $userUpdate = $conn->prepare("UPDATE UserTable SET NumOfPlayed = NumOfPlayed + 1 WHERE Username = ?");
            $userUpdate->bind_param("s", $user);
            $userUpdate->execute();
        }
    }

    // Update GamesInfo stats
    $updateStats = $conn->prepare("UPDATE GamesInfo SET Plays = Plays + 1, Reviews = Reviews + 1 WHERE `Index` = ?");
    $updateStats->bind_param("i", $index);
    $updateStats->execute();

    // Redirect to review page
    header("Location: https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_review.php?id=" . $reviewId);
    exit;
}

// If somehow this script is reached incorrectly:
$_SESSION["errorLog"] = "Invalid request method.";
header("Location: https://ik346.brighton.domains/groupProjectTests/html/public/log.php");
exit;