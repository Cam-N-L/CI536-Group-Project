<?php
session_start();
include ("config.php");

$table = 'GamesInfo';

$_SESSION["previousPage"] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']
    === 'on' ? "https" : "http") . "://" . 
    $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?id=" . $_GET['id'];

if (isset($_GET['id'])) {
    $game_id = $_GET['id'];

    // Prepare SQL query to fetch a specific game by id
    $sql = "SELECT * FROM $table WHERE `Index` = $game_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $game = $result->fetch_assoc();
        include('../templates/game_template.php');
    } else {
        echo "Game not found.";
    }
} else {
    echo "No game selected.";
}

$conn->close();
?>

