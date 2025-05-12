<?php
include 'config.php'; 

error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");

$q = $_GET["q"];
$q = str_replace("%", "\%", $q);

$response = "no results";

$hint = "";
if (strlen(trim($q)) > 0) {
    $hint = "";

    $sql = "SELECT Title FROM GamesInfo WHERE Title LIKE ?";
    $stmt = $conn->prepare($sql);

    $searchTerm = "%" . $q . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $stmt->bind_result($title);

    $i = 0;
    while ($stmt->fetch() && $i < 5) {
        if (!str_contains($hint, $title)){
            if ($hint == "") {
                $hint = '<p onclick="titleClicked(\'' . htmlspecialchars(str_replace("'", "\'", $title), ENT_QUOTES) . '\')">' . htmlspecialchars($title, ENT_QUOTES) . '</p>';
            } else {
                $hint = $hint . '<p onclick="titleClicked(\'' . htmlspecialchars(str_replace("'", "\'", $title), ENT_QUOTES) . '\')">' . htmlspecialchars($title, ENT_QUOTES) . '</p>';
            }
        }
        $i++;
    }

    $stmt->close();
}

echo $hint ? $hint : $response;
$conn->close();
?>