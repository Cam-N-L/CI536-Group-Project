<?php
session_start();
include ("config.php");

$table = 'ReviewTable';
$gametable = 'GameInfo';

$_SESSION["previousPage"] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']
    === 'on' ? "https" : "http") . "://" . 
    $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?id=" . $_GET['id'];

if (isset($_GET['id'])) {
    $review_id = $_GET['id'];

    // Prepare SQL query to fetch a specific game by id
    $sql = "SELECT g.`Title`, r.`gameindex`, r.`DateOfReview`, r.`NumOfLikes`, r.`Rating`, r.`Review`, r.`Username` FROM `ReviewTable` r INNER JOIN `GamesInfo` g on `index` = `gameindex` WHERE `ReviewID` = $review_id;";
    $result = $conn->query($sql);

    $sql = "SELECT * FROM `CommentsTable` where `ReviewID` = $review_id;";
    $results = $conn->query($sql);

    if ($result->num_rows > 0) {
        $game = $result->fetch_assoc();
        $comments = [];
        if ($results->num_rows > 0) {
            while($row = $results->fetch_assoc()) {
                $comments[] = $row;
    }}
        include('../templates/review_template.php');
    } else {
        echo "review not found.";
    }
} else {
    echo "No review selected.";
}

$conn->close();
?>