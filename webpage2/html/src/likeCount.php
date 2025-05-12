<?php

if (isset($_SESSION["username"])) {
    $users = $_SESSION["username"];
}

$query = $conn->prepare("SELECT COUNT(*) AS likes FROM `LikesForReviewsTable` WHERE `ReviewID` = ?");
$query->bind_param("i", $review_id);
$query->execute();
$result = $query->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['likes'] != "0"){
        if ($row['likes'] == "1"){
        return "• " . $row['likes'] . " like";
    } else {
        return "• " . $row['likes'] . " likes";
    }}
}
return "";

?>