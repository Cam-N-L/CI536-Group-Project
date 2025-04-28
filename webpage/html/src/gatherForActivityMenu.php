<?php
  session_start();
  include 'config.php';

  $_SESSION["Buttoncontent"] = "<button> accept friend request? </button>";
  $response = "no recent activity, add more friends to see what they're up too!";

  $hint = "";
    $sql = $conn->prepare("
            SELECT
                r.`GameIndex`,
                r.`Username`,
                r.`ReviewID`,
                r.`Rating`,
                r.`Review`,
                r.`NumOfLikes`,
                r.`DateOfReview`,
                g.`Title`,
                p.`PlayedOrCompleted`,
                `relationshipType`,
                'review' as `activityType`
            FROM
                `ReviewTable` r
            INNER JOIN `friendsLink` f ON
                f.`UserTwo` = r.`Username` OR f.`UserOne` = r.`Username`
            INNER JOIN `GamesInfo` g ON
                g.`Index` = r.`GameIndex`
            INNER JOIN `PlayedGames` p ON
                r.`Username` = p.`Username` AND r.`GameIndex` = p.`GameIndex`
            WHERE
                (
                    f.`UserTwo` = ? OR f.`UserOne` = ?
                ) AND f.`relationshipType` = 'friends' AND r.`Username` != ?

            UNION

            SELECT NULL, `UserTwo`, NULL, NULL, NULL, NULL, `DateAltered`, NULL, NULL, `relationshipType`, 'friends'
            FROM `friendsLink`
            WHERE `UserOne` = ? AND `relationshipType` = 'friends'

            UNION

            SELECT NULL, `UserOne`, NULL, NULL, NULL, NULL, `DateAltered`, NULL, NULL, `relationshipType`, 'friends'
            FROM `friendsLink`
            WHERE `UserTwo` = ? AND `relationshipType` = 'friends'

            UNION

            SELECT NULL, `UserOne`, NULL, NULL, NULL, NULL, `DateAltered`, NULL, NULL, `relationshipType`, 'friends'
            FROM `friendsLink`
            WHERE `UserTwo` = ? AND `relationshipType` = 'requested'

            UNION

            SELECT NULL, c.`Username`, r.`ReviewID`, NULL, c.`Comments`, NULL, `DateCommented`, NULL, NULL, NULL, 'comments'
            FROM `CommentsTable` c
            INNER JOIN `ReviewTable` r ON c.`ReviewID` = r.`ReviewID`
            WHERE r.`Username` = ?

            UNION

            SELECT g.`Index`, l.`Username`, r.`ReviewID`, NULL, NULL, r.`NumOfLikes`, `DateLiked`, g.`Title`, NULL, NULL, 'likes'
            FROM `LikesForReviewsTable` l
            INNER JOIN `ReviewTable` r ON r.`ReviewID` = l.`ReviewID`
            INNER JOIN `GamesInfo` g ON g.`Index` = r.`GameIndex`
            WHERE r.Username = ?

            UNION

            SELECT r.`ReviewID`, l.`Username`, c.`CommentID`, NULL, c.`Comments`, c.`Likes`, l.`DateLiked`, g.`Title`, NULL, NULL, 'likesComments'
            FROM `LikesForCommentsTable` l
            INNER JOIN `CommentsTable` c ON c.`CommentID` = l.`CommentID`
            INNER JOIN `ReviewTable` r ON r.`ReviewID` = c.`ReviewID`
            INNER JOIN `GamesInfo` g ON g.`Index` = r.`GameIndex`
            WHERE c.Username = ?

            ORDER BY `DateOfReview` DESC"  );
  

  $sql->bind_param("sssssssss", $_SESSION["username"], $_SESSION["username"], $_SESSION["username"], $_SESSION["username"], $_SESSION["username"], $_SESSION["username"], $_SESSION["username"], $_SESSION["username"], $_SESSION["username"]);

    // Execute the query
  $sql->execute();
  $sql->bind_result($index, $reviewer, $reviewID, $rating, $review, $likes, $date, $title, $playedOrCompleted, $relationshipType, $activityType);

    // Fetch results and build the response
  $i = 0;
  $reviewsLiked = "";
  $commentsLiked = "";
  while ($sql->fetch() && $i < 15) {
    $date = date_create($date);
    $date = date_format($date,"l jS F Y g:i");
    if ($activityType == "review"){
        $rating = str_repeat("⭐", $rating);
          if ($hint == "") {
              $hint = "<div class=\"activity-card\" onclick=\"openReview($reviewID)\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_game.php?id=" . $index . "\">" . $title . "</a> - review by <a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> </h3> <p>" . $playedOrCompleted . " on "  . $date . "</p> <p>" . htmlspecialchars_decode($review, ENT_QUOTES) . "</p> <p> rated " . $rating . "</p></div>";
          } else {
              $hint = $hint . "<div class=\"activity-card\" onclick=\"openReview($reviewID)\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_game.php?id=" . $index . "\">" . $title . "</a> - review by <a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> </h3> <p>" . $playedOrCompleted . " on "  . $date . "</p> <p>" . htmlspecialchars_decode($review, ENT_QUOTES) . "</p> <p> rated " . $rating . "</p></div>";
          }
    } else if ($activityType == "friends"){
        if ($relationshipType == "friends"){
            if ($hint == "") {
                $hint = "<div class=\"activity-card\"> <h3> <a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> and you became friends on "  . $date . "</p></div>";
            } else {
                $hint = $hint . "<div class=\"activity-card\"> <h3> <a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> and you became friends on "  . $date . "</p></div>";
            }
        } else if ($relationshipType == "requested"){
            if ($hint == "") {
                $hint = "<div class=\"activity-card\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> requested to be your friend on "  . $date . "</p><form id=\"friendAction\" action=\"../src/processFriendRequests.php\" method=\"POST\"><button> accept friend request? </button><input type=\"hidden\" name=\"reviewer\" value=\"" . $reviewer . "\"></div>";
            } else {
                $hint = $hint . "<div class=\"activity-card\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> requested to be your friend on "  . $date . "</p><form id=\"friendAction\" action=\"../src/processFriendRequests.php\" method=\"POST\"><button> accept friend request? </button><input type=\"hidden\" name=\"reviewer\" value=\"" . $reviewer . "\"></div>";
            }
    }}else if ($activityType == "comments"){
          if ($hint == "") {
              $hint = "<div class=\"activity-card\" onclick=\"openReview($reviewID)\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> commented on your post;</h3> <p>" . $review ."<br><br> commented on " . $date . "</div>";
          } else {
              $hint = $hint . "<div class=\"activity-card\" onclick=\"openReview($reviewID)\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> commented on your post;</h3> <p>" . $review . "<br><br> commented on " . $date . "</div>";
        }
      } else if ($activityType == "likes") {
        if (!str_contains($reviewsLiked, ($reviewID . ","))){
          if ($likes > 1){
            if ($hint == "") {
              $hint = "<div class=\"activity-card\" onclick=\"openReview($reviewID)\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> and " . $likes - 1 . " </a> others liked your review of <a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_game.php?id=" . $index . "\">" . $title . "</h3></a></div>";
          } else {
              $hint = $hint . "<div class=\"activity-card\" onclick=\"openReview($reviewID)\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> and " . $likes - 1 . " </a> others liked your review of <a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_game.php?id=" . $index . "\">" . $title . "</h3></a></div>";
         }} else {
          if ($hint == "") {
            $hint = "<div class=\"activity-card\" onclick=\"openReview($reviewID)\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> liked your review of <a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_game.php?id=" . $index . "\">" . $title . "</h3></a></div>";
        } else {
            $hint = $hint . "<div class=\"activity-card\" onclick=\"openReview($reviewID)\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> liked your review of <a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_game.php?id=" . $index . "\">" . $title . "</h3></a></div>";
        }}
        $reviewsLiked = $reviewsLiked . $reviewID . ",";
      }
    } else if ($activityType == "likesComments") {
      if (!str_contains($commentsLiked, ($reviewID . ","))){
        if ($likes > 1){
          if ($hint == "") {
            $hint = "<div class=\"activity-card\" onclick=\"openReview($index)\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> and " . $likes - 1 . " </a> others liked your comment; </h3><br><p>" . $review . "</p></div>";
        } else {
            $hint = $hint . "<div class=\"activity-card\" onclick=\"openReview($index)\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> and " . $likes - 1 . " </a> others liked your comment; </h3><br><p>" . $review . "</p></div>";
       }} else {
        if ($hint == "") {
          $hint = "<div class=\"activity-card\" onclick=\"openReview($index)\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> liked your comment; </h3><br><p>" . $review . "</p></div>";
      } else {
          $hint = $hint . "<div class=\"activity-card\" onclick=\"openReview($index)\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22" . $reviewer . "%22\">" . $reviewer . " </a> liked your comment; </h3><br><p>" . $review . "</p></div>";
      }}
      $commentsLiked = $commentsLiked . $reviewID . ",";
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