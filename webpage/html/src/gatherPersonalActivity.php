<?php
session_start();

include '../src/config.php';
$user = $_SESSION["targetUser"]; 

  $response = "no recent activity";

  $hint = "";
    // Prepare the SQL query to search titles and URLs that match the query
  $sql = $conn->prepare($sql = "SELECT\n"

  . "    r.`GameIndex`,\n"

  . "    r.`Username`,\n"

  . "    r.`ReviewID`,\n"

  . "    r.`Rating`,\n"

  . "    r.`Review`,\n"

  . "    r.`NumOfLikes`,\n"

  . "    r.`DateOfReview`,\n"

  . "    `Title`,\n"

  . "    p.`PlayedOrCompleted`\n"

  . "FROM\n"

  . "    `ReviewTable` r\n"

  . "INNER JOIN `GamesInfo` g ON\n"

  . "    `Index` = `GameIndex`\n"

  . "INNER JOIN `PlayedGames` p ON\n"

  . "    r.`Username` = p.`Username` AND r.`GameIndex` = p.`GameIndex`\n"

  . "WHERE\n"

  . "    r.`username` = ?\n"

  . "ORDER BY\n"

  . "    r.`DateOfReview`\n"

  . "DESC;");

  $sql->bind_param("s", $user);

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
            $hint = "<div class=\"activity-card\" onclick=\"openReview($reviewID)\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_game.php?id=" . $index . "\">" . $title . "</a> - review by <a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> </h3> <p>" . $playedOrCompleted . " on "  . $date . "</p> <p>" . htmlspecialchars_decode($review, ENT_QUOTES) . "</p> <p> rated " . $rating . "</p></div>";
        } else {
            $hint = $hint . "<div class=\"activity-card\" onclick=\"openReview($reviewID)\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_game.php?id=" . $index . "\">" . $title . "</a> - review by <a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> </h3> <p>" . $playedOrCompleted . " on "  . $date . "</p> <p>" . htmlspecialchars_decode($review, ENT_QUOTES) . "</p> <p> rated " . $rating . "</p></div>";
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