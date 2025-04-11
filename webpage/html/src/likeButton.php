<?php

if (isset($_SESSION["username"])) {
    $users = $_SESSION["username"];
}

$query = $conn->prepare("SELECT * FROM `LikesForReviewsTable` WHERE `Username` = ? AND `ReviewID` = ?");
        $query->bind_param("ss", $users, $review_id);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            echo "unlike";
        } else  {
            echo "like";
        }

?>