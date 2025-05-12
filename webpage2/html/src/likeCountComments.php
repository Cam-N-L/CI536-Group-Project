<?php

if (isset($_SESSION["username"])) {
    $users = $_SESSION["username"];
}

$query = $conn->prepare("SELECT COUNT(*) AS likes FROM `LikesForCommentsTable` WHERE `CommentID` = ?");
$query->bind_param("i", $comment_id);
$query->execute();
$result = $query->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['likes'] != "0"){
        echo $row['likes'] . " likes • ";
    }
}

?>