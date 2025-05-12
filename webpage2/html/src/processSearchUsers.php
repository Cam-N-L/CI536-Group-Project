<?php
include 'config.php'; 

error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");

$q = $_GET["term"];
$response = "no results";
$hint = "";

if (strlen(trim($q)) > 0) {
    $hint = "";

    $sql = "SELECT Username, Firstname, Surname FROM UserTable WHERE Username LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $q . "%"; // Use wildcards to match the query
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $stmt->bind_result($title, $firstname, $surname);

    $i = 0;
    while ($stmt->fetch() && $i < 5) {
        if (!str_contains($hint, $title)){
            if ($hint == "") {
                $hint = "<ul> <li> <a href=\"http://localhost/checkpoint/html/src/process_fetch_user.php?Username=%22" . urlencode($title) . "%22\">" . htmlspecialchars($title, ENT_QUOTES) . " • " .  htmlspecialchars($firstname, ENT_QUOTES) . " " .  htmlspecialchars($surname, ENT_QUOTES) ."</a> </li>";
            } else {
                $hint = $hint . "<li> <a href=\"http://localhost/checkpoint/html/src/process_fetch_user.php?Username=%22" . urlencode($title) . "%22\">" . htmlspecialchars($title, ENT_QUOTES) . " • " .  htmlspecialchars($firstname, ENT_QUOTES) . " " .  htmlspecialchars($surname, ENT_QUOTES) ."</a> </li>";
            }
        }
        $i++;
    }

    $hint .= "</ul>";
    $stmt->close();
}

echo $hint ? $hint : $response;
$conn->close();
?>