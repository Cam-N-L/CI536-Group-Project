<?php

if (isset($_SESSION["username"])) {
    $users = $_SESSION["username"];
}

$query = $conn->prepare("SELECT * FROM `LikesForCommentsTable` WHERE `Username` = ? AND `CommentID` = ?");
        $query->bind_param("ss", $users, $comment_id);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            echo "unlike";
        } else  {
            echo "like";
        }

?>