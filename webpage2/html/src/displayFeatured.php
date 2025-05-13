<?php
function display_game($g, $conn){
    $stmt = $conn->prepare("SELECT `Title`, `Rating` FROM `GamesInfo` WHERE `index` = ?");
    $stmt->bind_param("i", $g);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $roundedRating = round($row["Rating"]);
    $starsImagePath = "../../images/stars-$roundedRating.PNG";

    $display = "<div class=\"favorite-game\" onclick=\"openGame($g)\">
                    <img src=\"game1.jpg\">
                    <h3>{$row['Title']}</h3>
                    <div class=\"custom-star-rating\" data-game-id=\"$g\">
                        <div class=\"rating-label\" style=\"margin-bottom: 5px; font-weight: bold;\">Rating:</div>
                        <img class=\"ratingDisplay\" src=\"$starsImagePath\" alt=\"Star Rating\" data-game-id=\"$g\" />
                        <input type=\"hidden\" name=\"rating_$g\" class=\"ratingInput\" data-game-id=\"$g\" value=\"$roundedRating\" required>
                    </div>
                </div>";
    
    echo $display;
}

?>
