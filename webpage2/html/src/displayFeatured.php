<?php
function display_game($g, $conn){
    $stmt = $conn->prepare("SELECT `Title`, `Rating` FROM `GamesInfo` WHERE `index` = ?");
    $stmt->bind_param("i", $g);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $display =  "<div class=\"favorite-game\" onclick=\"openGame(". $g . ")\">
                <img src=\"game1.jpg\">
                <h3>" . $row["Title"] . "</h3>
                <p>Rating: " . str_repeat("⭐", round($row["Rating"])) ."</p></div>";
    echo $display;
}

?>