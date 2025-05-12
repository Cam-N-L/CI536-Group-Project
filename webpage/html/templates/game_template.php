<?php 
  include '../src/sortArrays.php';

  error_reporting(-1);
  ini_set('display_errors', 'On');
  set_error_handler("var_dump");

    function sortDate($Data){
        if ($Data == "TBD") {
            return "Release date to be announced";
    } else {
        return "Released on " . $Data;
    }}
    $_SESSION["viewingGame"] = $game['Index'];
   ?> 

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link href="../../css/home.css" rel="stylesheet" type="text/css">
    <script src="../../js/gameActivity.js"></script>
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

    <div class="game-container">
        <h1> <?php echo $game['Title']; ?> </h1>
        <p>Created by<?php echo sortArrays($game['Developers']); ?> • <?php echo sortArrays($game['Genres']); ?> • <?php echo sortDate($game['Release_date']); ?></p>
        <p> <?php echo $game['Summary']; ?> </p>
        <p>Released on </strong> <?php echo sortArrays($game['Platforms'])?> with an average rating of <?php echo $game['Rating']; ?></p>
    </div>

    <div>
        <h3>Recent reviews</h3>
        <form action="../public/log.php" method="POST">
            <input type="hidden" value="<?php echo $game['Title'];?>" id="gameName" name="gameName">
            <p>Have you recently played this game?</p>
            <button href="../public/log.php">Log</button>
        </form>
    </div>
    <div>
        <div class="activity-container">
        <h2>Recent Activity</h2>
        <div id="activity-section">
        </div>
    </div>
    </div>

</body>
</html>
