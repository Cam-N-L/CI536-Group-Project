<?php
    if (isset($_SESSION["username"])) {
        $users = $_SESSION["username"];
    }

    $_SESSION["targetUser"] = $user['Username'];
    include ("FriendButton.php");
    $username = $user['Username'];
   ?> 

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link href="../../css/home.css" rel="stylesheet" type="text/css">
    <script src="../../js/index.js"></script>
    <script src="../../js/openGame.js"></script>
    <script src="../../js/personalActivity.js"></script>
    <script src="../../js/navMenu.js"></script>
    <title>CheckPoint</title>
</head>

<body onload="showResult()">
    <!-- Navigation -->
    <nav class="navbar">
        
        <div class="nav-left">
            <div class="profile">
                <a href="#">My Profile</a>
                <div class="dropdown-content">
                    <a href="../public/profile.php">View My Profile</a>
                    <a href="../public/editProfile.php">Edit My Profile</a>
                    <a href="../src/processLogOut.php">Log Out</a>
                </div>
            </div>
            <div class="nav-links">
                <a href="../public/activity.php">Activity</a>
            </div>
        </div>

        <div class="logo">
            <a href="../public/home.php"><img src="../../images/checkpoint-logo.PNG" alt="CheckPoint Logo"></a>
        </div>

        <div class="nav-right">
            <div class="nav-links">
                <a href="../public/log.php">Log</a>
                <a href="../public/search.php">Search</a>
            </div>
            <div class="hamburger" onclick="toggleMenu()">☰</div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="mobile-menu">
        <a href="../public/log.php">Log</a>
        <a href="../public/search.php">Search</a>
        <a href="../public/activity.php">Activity</a>
        <a href="../public/profile.php">My Profile</a>
        <a href="../public/editProfile.php">Edit Profile</a>
        <a href="../src/processLogOut.php">Log Out</a>
    </div>

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
            <?php include '../src/fetchFavGames.php'?>
        </div>
        </div>
    </div>
        <div class="activity-container">
        <h2>Recent Activity</h2>
        <div id="activity-section">
        </div>
    </div>

</body>
</html>
