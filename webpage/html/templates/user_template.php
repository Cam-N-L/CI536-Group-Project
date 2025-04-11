<?php
    if (isset($_SESSION["username"])) {
        $users = $_SESSION["username"];
    }

    $_SESSION["targetUser"] = $user['Username'];
    include ("FriendButton.php") 
   ?> 

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link href="../../css/home.css" rel="stylesheet" type="text/css">
    <script src="../../js/index.js"></script>
    <title>Videogame review webpage</title>
</head>

<body>
<nav class="navbar">
    <div class="profile">
        <a href="../public/profile.php">My Profile</a>
        <div class="dropdown-content">
            <a href="../public/profile.php">view my profile</a>
            <a href="../public/activity.php">view activity</a>
            <a href="../public/editProfile">edit my profile</a>
            <a href="../src/processLogOut.php">log out</a>
        </div>
    </div>
    <a href="../public/home.php">Home</a>
    <a href="../public/log.php">Log</a>
    <a href="../public/search.php">Search</a>
</nav>

    <div class="content">
        <h1><?php echo $user['Username']; ?>'s profile</h1>
        <form id="friendAction" action="processFriendRequests.php" method="POST">
            <?php echo $buttonContents ?>
        </form>
        <p>View and manage <?php echo $user['Username']; ?>'s favorite games and ratings.</p>
    </div>
    <div class="favorites-container">
        <h2><?php echo $user['Username']; ?>'s Favorite Games</h2>
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