<?php
  session_start();
  include '../src/config.php'; 

  if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
  } else {
    $_SESSION["previousPage"] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']
    === 'on' ? "https" : "http") . "://" . 
    $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    header("Refresh:0; url=https://ik346.brighton.domains/groupProjectTests/html/public/signin.php");
    exit();
  }
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link href="../../css/home.css" rel="stylesheet" type="text/css">
    <script src="../../js/activity.js"></script>
    <title>Videogame review webpage</title>
</head>

<body onload="showResult()">
<nav class="navbar">
    <div class="profile">
        <a href="profile.php">My Profile</a>
        <div class="dropdown-content">
            <a href="profile.php">view my profile</a>
            <a href="activity.php">view activity</a>
            <a href="editProfile.php">edit my profile</a>
            <a href="../src/processLogOut.php">log out</a>
        </div>
    </div>
    <a href="#">Home</a>
    <a href="log.php">Log</a>
    <a href="search.php">Search</a>
</nav>

    <div class="content">
        <h1>Welcome to Our Videogame Review Website</h1>
        <p>Discover the latest reviews and insights on your favourite games.</p>
    </div>

    <div class="games-container">
        <h2>Featured Games</h2>
        <div class="games-section">
            <div class="game-card">
                <img src="/webpage/images/">
                <h3>Game Title 1</h3>
                <p>Short description of the game.</p>
            </div>
            <div class="game-card">
                <img src="/webpage/images/">
                <h3>Game Title 2</h3>
                <p>Short description of the game.</p>
            </div>
            <div class="game-card">
                <img src="/webpage/images/">
                <h3>Game Title 3</h3>
                <p>Short description of the game.</p>
            </div>
            <div class="game-card">
                <img src="/webpage/images/">
                <h3>Game Title 4</h3>
                <p>Short description of the game.</p>
            </div>
            <div class="game-card">
                <img src="/webpage/images/">
                <h3>Game Title 5</h3>
                <p>Short description of the game.</p>
            </div>
            <div class="game-card">
                <img src="/webpage/images/">
                <h3>Game Title 6</h3>
                <p>Short description of the game.</p>
            </div>
            <div class="game-card">
                <img src="/webpage/images/">
                <h3>Game Title 7</h3>
                <p>Short description of the game.</p>
            </div>
            <div class="game-card">
                <img src="/webpage/images/">
                <h3>Game Title 8</h3>
                <p>Short description of the game.</p>
            </div>
        </div>
    </div>

    <div class="activity-container">
        <h2> recent friend activity </h2>
        <div id="activity-section">
        </div>
    </div>
</body>
</html>