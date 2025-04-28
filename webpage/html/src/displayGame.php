<?php
function display_game($g, $conn, $username){
    $stmt = $conn->prepare("SELECT `Title` FROM `GamesInfo` WHERE `index` = ?");
    $stmt->bind_param("i", $g);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $title = $row["Title"];
    $stmt = $conn->prepare("SELECT `Rating` FROM `ReviewTable` WHERE `GameIndex` = ? AND `Username` = ?;");
    $stmt->bind_param("is", $g, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $display =  "<div class=\"favorite-game\" onclick=\"openGame(". $g . ")\">
                <img src=\"game1.jpg\">
                <h3>" . $title . "</h3>";
    if (isset($row["Rating"])){
        $display .= "<p>Rating: " . $rating = str_repeat("⭐", $row["Rating"]) ."</p></div>";
    } else {
        $display .= "</div>";
    }
    echo $display;
}

?>