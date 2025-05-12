<?php

$num_results_on_page = 15;
$countQuery = $conn->prepare("
           SELECT COUNT(*) FROM
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

            SELECT COUNT(*)
            FROM `friendsLink`
            WHERE `UserOne` = ? AND `relationshipType` = 'friends'

            UNION

            SELECT COUNT(*)
            FROM `friendsLink`
            WHERE `UserTwo` = ? AND `relationshipType` = 'friends'

            UNION

            SELECT COUNT(*)
            FROM `friendsLink`
            WHERE `UserTwo` = ? AND `relationshipType` = 'requested'

            UNION

            SELECT COUNT(*)
            FROM `CommentsTable` c
            INNER JOIN `ReviewTable` r ON c.`ReviewID` = r.`ReviewID`
            WHERE r.`Username` = ?

            UNION

            SELECT COUNT(*)
            FROM `LikesForReviewsTable` l
            INNER JOIN `ReviewTable` r ON r.`ReviewID` = l.`ReviewID`
            INNER JOIN `GamesInfo` g ON g.`Index` = r.`GameIndex`
            WHERE r.Username = ?

            UNION

            SELECT COUNT(*)
            FROM `LikesForCommentsTable` l
            INNER JOIN `CommentsTable` c ON c.`CommentID` = l.`CommentID`
            INNER JOIN `ReviewTable` r ON r.`ReviewID` = c.`ReviewID`
            INNER JOIN `GamesInfo` g ON g.`Index` = r.`GameIndex`
            WHERE c.Username = ?");
  

  $countQuery->bind_param("sssssssss", 
    $_SESSION["username"], $_SESSION["username"], $_SESSION["username"], 
    $_SESSION["username"], $_SESSION["username"], $_SESSION["username"], 
    $_SESSION["username"], $_SESSION["username"], $_SESSION["username"]);

    $countQuery->execute();
    $countQuery->bind_result($total_rows);
    $countQuery->fetch();
    $countQuery->close();

    $total_pages = ceil($total_rows / $num_results_on_page);

    $pagination = '';
    for ($i = 1; $i <= $total_pages; $i++) {
        $pagination .= "<button onclick='loadPage($i)'>$i</button> ";
    }
    echo $pagination;

    ?>