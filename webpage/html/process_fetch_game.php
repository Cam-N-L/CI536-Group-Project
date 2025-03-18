<?php
include ("config.php");

$table = 'GamesInfo';

if (isset($_GET['id'])) {
    $game_id = $_GET['id'];

    // Prepare SQL query to fetch a specific game by id
    $sql = "SELECT * FROM $table WHERE `Index` = $game_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $game = $result->fetch_assoc();
        include('game_template.php');
    } else {
        echo "Game not found.";
    }
} else {
    echo "No game selected.";
}

$conn->close();
?>

