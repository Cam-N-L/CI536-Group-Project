<?php
  session_start();
  include 'config.php';

  $_SESSION["Buttoncontent"] = "<button> accept friend request? </button>";
  $response = "no recent activity, add more friends to see what they're up too!";

  $hint = "";
    // Prepare the SQL query to search titles and URLs that match the query
  $sql = $conn->prepare("SELECT r.`GameIndex`, r.`Username`, r.`ReviewID`, r.`Rating`, r.`Review`, r.`NumOfLikes`, r.`DateOfReview`, g.`Title`, p.`PlayedOrCompleted`, `relationshipType`\n"

    . "FROM `ReviewTable` r \n"

    . "INNER JOIN `friendsLink` f ON f.`UserTwo` = r.`Username` OR f.`UserOne` = r.`Username` \n"

    . "INNER JOIN `GamesInfo` g ON g.`Index` = r.`GameIndex` \n"

    . "INNER JOIN `PlayedGames` p ON r.`Username` = p.`Username` AND r.`GameIndex` = p.`GameIndex` \n"

    . "WHERE (f.`UserTwo` = ? OR f.`UserOne` = ?) \n"

    . "AND f.`relationshipType` = \"friends\" \n"

    . "AND r.`Username` != ?\n"

    . "\n"

    . "UNION\n"

    . "\n"

    . "SELECT NULL AS `GameIndex`, `UserTwo` AS `Username`, NULL AS `ReviewID`, NULL AS `Rating`, NULL AS `Review`, NULL AS `NumOfLikes`, `DateAltered` AS `DateOfReview`, NULL AS `Title`, NULL AS `PlayedOrCompleted`, `relationshipType`\n"

    . "FROM `friendsLink`\n"

    . "WHERE `UserOne` = ?\n"

    . "AND `relationshipType` = \"friends\"\n"

    . "\n"

    . "UNION\n"

    . "\n"

    . "SELECT NULL AS `GameIndex`, `UserOne` AS `Username`, NULL AS `ReviewID`, NULL AS `Rating`, NULL AS `Review`, NULL AS `NumOfLikes`, `DateAltered` AS `DateOfReview`, NULL AS `Title`, NULL AS `PlayedOrCompleted`, `relationshipType`\n"

    . "FROM `friendsLink`\n"

    . "WHERE `UserTwo` = ?\n"

    . "AND `relationshipType` = \"friends\"\n"

    . "\n"

    . "UNION\n"

    . "\n"

    . "SELECT NULL AS `GameIndex`, `UserOne` AS `Username`, NULL AS `ReviewID`, NULL AS `Rating`, NULL AS `Review`, NULL AS `NumOfLikes`, `DateAltered` AS `DateOfReview`, NULL AS `Title`, NULL AS `PlayedOrCompleted`, `relationshipType`\n"

    . "FROM `friendsLink`\n"

    . "WHERE `UserTwo` = ?\n"

    . "AND `relationshipType` = \"requested\"\n"

    . "\n"

    . "ORDER BY `DateOfReview` DESC;");

  $sql->bind_param("ssssss", $_SESSION["username"], $_SESSION["username"], $_SESSION["username"], $_SESSION["username"], $_SESSION["username"], $_SESSION["username"]);

    // Execute the query
  $sql->execute();
  $sql->bind_result($index, $reviewer, $reviewID, $rating, $review, $likes, $date, $title, $playedOrCompleted, $relationshipType);

    // Fetch results and build the response
  $i = 0;
  while ($sql->fetch() && $i < 15) {
    $date = date_create($date);
    $date = date_format($date,"l jS F Y g:i");
    if ($index != Null){
        $rating = str_repeat("⭐", $rating);
          if ($hint == "") {
              $hint = "<div class=\"activity-card\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_game.php?id=" . $index . "\">" . $title . "</a> - review by <a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> </h3> <p>" . $playedOrCompleted . " on "  . $date . "</p> <p>" . htmlspecialchars_decode($review, ENT_QUOTES) . "</p> <p> rated " . $rating . "</p></div>";
          } else {
              $hint = $hint . "<div class=\"activity-card\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_game.php?id=" . $index . "\">" . $title . "</a> - review by <a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> </h3> <p>" . $playedOrCompleted . " on "  . $date . "</p> <p>" . htmlspecialchars_decode($review, ENT_QUOTES) . "</p> <p> rated " . $rating . "</p></div>";
          }
    } else {
        if ($relationshipType == "friends"){
            if ($hint == "") {
                $hint = "<div class=\"activity-card\"> <h3> <a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> and you became friends on "  . $date . "</p></div>";
            } else {
                $hint = $hint . "<div class=\"activity-card\"> <h3> <a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> and you became friends on "  . $date . "</p></div>";
            }
        } else {
            if ($hint == "") {
                $hint = "<div class=\"activity-card\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> requested to be your friend on "  . $date . "</p><form id=\"friendAction\" action=\"../src/processFriendRequests.php\" method=\"POST\"><button> accept friend request? </button><input type=\"hidden\" name=\"reviewer\" value=\"" . $reviewer . "\"></div>";
            } else {
                $hint = $hint . "<div class=\"activity-card\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> requested to be your friend on "  . $date . "</p><form id=\"friendAction\" action=\"../src/processFriendRequests.php\" method=\"POST\"><button> accept friend request? </button><input type=\"hidden\" name=\"reviewer\" value=\"" . $reviewer . "\"></div>";
            }
        }
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