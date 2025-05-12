<?php
include 'config.php'; 
include 'sortArrays.php';

error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");

$q = $_GET["term"];
$response = "no results";
$hint = "";

if (strlen(trim($q)) > 0) {
    $hint = "";

    $sql = "SELECT Title, `index`, Developers FROM GamesInfo WHERE Title LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $q . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $stmt->bind_result($title, $index, $developers);

    while ($stmt->fetch()) {
        if (!str_contains($hint, $title)){
            if ($hint == "") {
                $hint = "<ul> <li> <a href=\"http://localhost/checkpoint/html/src/process_fetch_game.php?id=" . htmlspecialchars($index, ENT_QUOTES) . "\"> " . htmlspecialchars($title, ENT_QUOTES) . " • Developed by" . htmlspecialchars(sortArrays($developers), ENT_QUOTES) . "</a> </li>";
            } else {
                $hint .= "<li> <a href=\"http://localhost/checkpoint/html/src/process_fetch_game.php?id=" . htmlspecialchars($index, ENT_QUOTES) . "\"> " . htmlspecialchars($title, ENT_QUOTES) . " • Developed by" . htmlspecialchars(sortArrays($developers), ENT_QUOTES) . "</a> </li>";
            }
        }
    }

    $hint .= "</ul>";
    $stmt->close();
}

// Echo the results
echo $hint ? $hint : $response;
$conn->close();
?>
