<?php
  // Enable error reporting for debugging
  session_start();
  include 'config.php';  

  if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
  } else {
    header("Refresh:0; url=https://ik346.brighton.domains/groupProjectTests/html/signin.php");
    exit();
  }
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link href="../css/home.css" rel="stylesheet" type="text/css">
    <script src="../js/index.js"></script>
    <title>Videogame review webpage</title>
</head>

<body>
<nav class="navbar">
    <div class="profile">
        <a href="#">my profile</a>
        <div class="dropdown-content">
            <a href="#">view my profile</a>
            <a href="#">edit my profile</a>
            <a href="https://ik346.brighton.domains/groupProjectTests/html/processLogOut.php">log out</a>
        </div>
    </div>
    <a href="home.html">Home</a>
    <a href="log.html">Log</a>
</nav>

    <div class="content">
        <h1><?php echo $username; ?>'s profile</h1>
        <p>View and manage your favorite games and ratings.</p>
    </div>
    <div class="favorites-container">
        <h2>Your Favorite Games</h2>
        <div class="favorites-section">
            <div class="favorite-game">
                <img src="game1.jpg">
                <h3>Game Title 1</h3>
                <p>Rating: ⭐⭐⭐⭐</p>
            </div>
            <div class="favorite-game">
                <img src="game2.jpg">
                <h3>Game Title 2</h3>
                <p>Rating: ⭐⭐⭐⭐⭐</p>
            </div>
            <div class="favorite-game">
                <img src="game3.jpg">
                <h3>Game Title 3</h3>
                <p>Rating: ⭐⭐⭐</p>
            </div>
            <div class="favorite-game">
                <img src="game4.jpg">
                <h3>Game Title 4</h3>
                <p>Rating: ⭐⭐⭐⭐⭐</p>
            </div>
            <div class="favorite-game">
                <img src="game4.jpg">
                <h3>Game Title 5</h3>
                <p>Rating: ⭐⭐⭐⭐</p>
            </div>
        </div>
    </div>
</body>
</html>
