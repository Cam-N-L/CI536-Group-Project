<?php
session_start();
include 'config.php';

$num_results_on_page = 14;
$page = intval($_POST['page'] ?? 1);
$page = max(1, $page);
$offset = ($page - 1) * $num_results_on_page;

$_SESSION["Buttoncontent"] = "<button> accept friend request? </button>";
$response = "no recent activity, add more friends to see what they're up too!";

$hint = "";
$suggestedFriendsArr = [];

$friends = $conn->prepare("
    SELECT DISTINCT 
        CASE 
            WHEN fl.UserOne = f.friend THEN fl.UserTwo
            ELSE fl.UserOne
        END AS suggestedFriend
    FROM `friendsLink` fl
    JOIN (
        SELECT 
            CASE 
                WHEN UserOne = ? THEN UserTwo
                ELSE UserOne
            END AS friend
        FROM `friendsLink`
        WHERE (UserOne = ? OR UserTwo = ?)
            AND relationshipType = 'friends'
    ) f ON (fl.UserOne = f.friend OR fl.UserTwo = f.friend)
    WHERE fl.relationshipType = 'friends'
        AND ? NOT IN (fl.UserOne, fl.UserTwo)
        AND NOT EXISTS (
            SELECT 1
            FROM `friendsLink` existing
            WHERE (existing.UserOne = ? AND existing.UserTwo = 
                CASE 
                    WHEN fl.UserOne = f.friend THEN fl.UserTwo
                    ELSE fl.UserOne
                END)
                OR (existing.UserTwo = ? AND existing.UserOne = 
                CASE 
                    WHEN fl.UserOne = f.friend THEN fl.UserTwo
                    ELSE fl.UserOne
                END)
            AND existing.relationshipType = 'friends'
        )
");

$friends->bind_param("ssssss", 
    $_SESSION["username"], $_SESSION["username"], $_SESSION["username"], 
    $_SESSION["username"], $_SESSION["username"], $_SESSION["username"]);
$friends->execute();
$friends->bind_result($suggestedFriend);

while ($friends->fetch()) {
    $suggestedFriendsArr[] = $suggestedFriend;
}
$friends->close();

$sql = $conn->prepare("SELECT
    r.`GameIndex`, r.`Username`, r.`ReviewID`, r.`Rating`, r.`Review`,
    r.`NumOfLikes`, r.`DateOfReview`, g.`Title`, p.`PlayedOrCompleted`,
    `relationshipType`, 'review' as `activityType`
FROM `ReviewTable` r
INNER JOIN `friendsLink` f ON f.`UserTwo` = r.`Username` OR f.`UserOne` = r.`Username`
INNER JOIN `GamesInfo` g ON g.`Index` = r.`GameIndex`
INNER JOIN `PlayedGames` p ON r.`Username` = p.`Username` AND r.`GameIndex` = p.`GameIndex`
WHERE (f.`UserTwo` = ? OR f.`UserOne` = ?) AND f.`relationshipType` = 'friends' AND r.`Username` != ?
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
ORDER BY `DateOfReview` DESC LIMIT ? OFFSET ?");

$sql->bind_param("sssssssssii",
    $_SESSION["username"], $_SESSION["username"], $_SESSION["username"],
    $_SESSION["username"], $_SESSION["username"], $_SESSION["username"],
    $_SESSION["username"], $_SESSION["username"], $_SESSION["username"],
    $num_results_on_page, $offset);

$sql->execute();
$sql->bind_result($index, $reviewer, $reviewID, $rating, $review, $likes, $date, $title, $playedOrCompleted, $relationshipType, $activityType);

$i = 0;
$reviewsLiked = "";
$commentsLiked = "";

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    while ($sql->fetch()) {
        $date = date_create($date);
        $date = date_format($date, "l jS F Y g:i");

        if (!empty($suggestedFriendsArr)) {
            $randomFriend = $suggestedFriendsArr[array_rand($suggestedFriendsArr)];
            $hint .= "<div class=\"activity-card\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22{$randomFriend}%22\">{$randomFriend}</a> has mutual friends, do you know them?</h3></div>";
            $suggestedFriendsArr = [];
        }


        switch ($activityType) {
            case "review":
                $ratingStars = str_repeat("⭐", $rating);
                $hint .= "<div class=\"activity-card\" onclick=\"openReview($reviewID)\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_game.php?id=$index\">$title</a> - review by <a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22$reviewer%22\">$reviewer</a></h3> <p>$playedOrCompleted on $date</p> <p>" . htmlspecialchars_decode($review, ENT_QUOTES) . "</p> <p> rated $ratingStars</p></div>";
                break;
            case "friends":
                $message = ($relationshipType == "friends") ? "$reviewer and you became friends on $date" : "$reviewer requested to be your friend on $date";
                $form = ($relationshipType == "requested") ? "<form id=\"friendAction\" action=\"../src/processFriendRequests.php\" method=\"POST\"><button> accept friend request? </button><input type=\"hidden\" name=\"reviewer\" value=\"$reviewer\"></form>" : "";
                $hint .= "<div class=\"activity-card\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22$reviewer%22\">$reviewer</a> $message</h3>$form</div>";
                break;
            case "comments":
                $hint .= "<div class=\"activity-card\" onclick=\"openReview($reviewID)\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22$reviewer%22\">$reviewer</a> commented on your post;</h3> <p>$review<br><br> commented on $date</p></div>";
                break;
            case "likes":
                if (!str_contains($reviewsLiked, "$reviewID,")) {
                    $likers = ($likes > 1) ? "$reviewer and " . ($likes - 1) . " others" : "$reviewer";
                    $hint .= "<div class=\"activity-card\" onclick=\"openReview($reviewID)\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22$reviewer%22\">$likers</a> liked your review of <a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_game.php?id=$index\">$title</a></h3></div>";
                    $reviewsLiked .= "$reviewID,";
                }
                break;
            case "likesComments":
                if (!str_contains($commentsLiked, "$reviewID,")) {
                    $likers = ($likes > 1) ? "$reviewer and " . ($likes - 1) . " others" : "$reviewer";
                    $hint .= "<div class=\"activity-card\" onclick=\"openReview($index)\"> <h3><a href=\"https://ik346.brighton.domains/groupProjectTests/html/src/process_fetch_user.php?Username=%22$reviewer%22\">$likers</a> liked your comment;</h3><br><p>$review</p></div>";
                    $commentsLiked .= "$reviewID,";
                }
                break;
        }
    }
}

$sql->close();
echo $hint ? $hint : $response;
$conn->close();
?>