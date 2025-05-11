<?php
    $stmt = $conn->prepare("SELECT `Favourites` FROM `UserTable` WHERE `Username` = ?;");
    $stmt->bind_param("s", $usernames);
      if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $favs = $row["Favourites"];
        if ($favs != null){
            $favs = trim($favs, "[]");
            $games = explode(',', $favs);
            foreach ($games as $g){
                $stmt = $conn->prepare("SELECT `Title` FROM `GamesInfo` WHERE `Index` = ?;");
                $stmt->bind_param("i", $g);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                echo "<li class=\"sortable-item\" draggable=\"true\">" . $row["Title"] . "</li>";
            }
        }
      }
  ?>