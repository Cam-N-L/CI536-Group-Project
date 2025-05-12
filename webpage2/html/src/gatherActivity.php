<?php
  session_start();
  include 'config.php';

  $response = "No recent activity. Add more friends to see what they're up to!";

  $hint = "";
    // Prepare the SQL query to search titles and URLs that match the query
  $sql = $conn->prepare("SELECT r.`GameIndex`, r.`Username`, r.`ReviewID`, r.`Rating`, r.`Review`, r.`NumOfLikes`, r.`DateOfReview`, `Title`, p.`PlayedOrCompleted` FROM `ReviewTable` r INNER JOIN `friendsLink` ON `UserTwo` = `Username` OR `UserOne` = `Username` INNER JOIN `GamesInfo` ON `Index` = `GameIndex` INNER JOIN `PlayedGames` p ON r.`Username` = p.`Username` AND r.`GameIndex` = p.`GameIndex` WHERE (`UserTwo`= ? OR `UserOne` = ?) AND `relationshipType` = \"friends\" AND r.`Username` != ? ORDER BY r.`DateOfReview` DESC;");
  $sql->bind_param("sss", $_SESSION["username"], $_SESSION["username"], $_SESSION["username"]);

    // Execute the query
  $sql->execute();
  $sql->bind_result($index, $reviewer, $reviewID, $rating, $review, $likes, $date, $title, $playedOrCompleted);

    // Fetch results and build the response
  $i = 0;
  while ($sql->fetch() && $i < 15) {
    $rating = str_repeat("⭐", $rating);
    $date = date_create($date);
    $date = date_format($date,"l jS F Y g:i");
          if ($hint == "") {
            $hint = "<div class=\"activity-card\" onclick=\"openReview($reviewID)\"> <h3><a href=\"http://localhost/checkpoint/html/src/process_fetch_game.php?id=" . $index . "\">" . $title . "</a> - review by <a href=\"http://localhost/checkpoint/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> </h3> <p>" . $playedOrCompleted . " on "  . $date . "</p> <p>" . htmlspecialchars_decode($review, ENT_QUOTES) . "</p> <p> rated " . $rating . "</p></div>";
        } else {
            $hint = $hint . "<div class=\"activity-card\" onclick=\"openReview($reviewID)\"> <h3><a href=\"http://localhost/checkpoint/html/src/process_fetch_game.php?id=" . $index . "\">" . $title . "</a> - review by <a href=\"http://localhost/checkpoint/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> </h3> <p>" . $playedOrCompleted . " on "  . $date . "</p> <p>" . htmlspecialchars_decode($review, ENT_QUOTES) . "</p> <p> rated " . $rating . "</p></div>";
        }
        $i++;
  }

    // Close the statement
    $sql->close();

// Output the response
echo $hint ? $hint : $response;


// Close the database connection
$conn->close();
?>
