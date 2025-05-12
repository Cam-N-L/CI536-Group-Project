<?php
  session_start();
  include '../src/config.php'; 
  include '../src/displayFeatured.php'; 
  include '../recommendations/getFeatured.php';
  include '../recommendations/recommendation.php';

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
    <script src="../../js/openReview.js"></script>
    <script src="../../js/openGame.js"></script>
    <script src="../../js/navMenu.js"></script>
    <script src="../../js/rating.js"></script>
    <title>CheckPoint</title>
</head>

<body onload="showResult()"></body>
    <!-- Navigation -->
    <nav class="navbar">
        
        <div class="nav-left">
            <div class="profile">
                <a href="#">My Profile</a>
                <div class="dropdown-content">
                    <a href="profile.php">View My Profile</a>
                    <a href="editProfile.php">Edit My Profile</a>
                    <a href="../src/processLogOut.php">Log Out</a>
                </div>
            </div>
            <div class="nav-links">
                <a href="../public/activity.php">Activity</a>
            </div>
        </div>

        <div class="logo">
            <a href="home.php"><img src="../../images/checkpoint-logo.PNG" alt="CheckPoint Logo"></a>
        </div>

        <div class="nav-right">
            <div class="nav-links">
                <a href="log.php">Log</a>
                <a href="search.php">Search</a>
            </div>
            <div class="hamburger" onclick="toggleMenu()">☰</div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="mobile-menu">
        <a href="log.php">Log</a>
        <a href="search.php">Search</a>
        <a href="activity.php">Activity</a>
        <a href="profile.php">My Profile</a>
        <a href="editProfile.php">Edit Profile</a>
        <a href="../src/processLogOut.php">Log Out</a>
    </div>

    <!-- Page Content -->
    <div class="content">
        <h1>Welcome to CheckPoint!</h1>
        <p>Discover the latest reviews and insights on your favourite games.</p>
    </div>

    <!-- Featured Games -->
    <div class="games-container">
        <h2>Featured Games</h2>
        <div class="games-section">
            <?php $featured = findFeatured($conn);
            foreach ($featured as $g){
                display_game($g, $conn);
            }?>
        </div>
    </div>

    <div class="games-container">
        <h2>Recommended for you</h2>
        <div class="games-section">
            <?php $recs = new getRecs($username, $conn);
            $recommended = $recs->getRandom8();
            if ($recommended!=null){
                foreach ($recommended as $g){
                display_game($g, $conn);
                }
            } else {
                echo "<p>Please log atleast five games to see some recommendations! </p>";
            }
            ?>
        </div>
    </div>

    <div class="activity-container">
        <h2>Recent friend activity</h2>
        <div id="activity-section">
        </div>
        <form action="activity.php" method="POST">
            <button type="submit">See all Activity</button>
        </form>
    </div>

</body>
</html>
