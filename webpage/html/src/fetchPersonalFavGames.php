<?php
include 'displayGame.php';

$stmt = $conn->prepare("SELECT `Favourites` FROM `UserTable` WHERE `Username` = ?;");
    $stmt->bind_param("s", $username);
      if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $favs = $row["Favourites"];
        if ($favs != null){
            $favs = trim($favs, "[]");
            $games = explode(',', $favs);
            foreach ($games as $g){
                display_game($g, $conn, $username);
            } 
        }else {
          echo "<div id=\"favourites-section\" style=\"border: 1px solid rgb(165, 172, 178);\"> <p> you have no favourite games, why not add some in edit profile? </p> </div>";
        }
      }
  ?>
