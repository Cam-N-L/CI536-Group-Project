<?php
  session_start();
  include '../src/config.php'; 

  error_reporting(-1);
  ini_set('display_errors', 'On');
  set_error_handler("var_dump");

  $_SESSION["previousPage"] = "/checkpoint/html/public/activity.php";

  if (isset($_SESSION["targetUser"])) {
    unset($_SESSION["targetUser"]);
  }
  if (isset($_SESSION["username"])) {
    $user = $_SESSION["username"];
  } else {
    header("Location: http://localhost/checkpoint/html/public/signin.php");
    exit();
  }
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link href="../../css/home.css" rel="stylesheet" type="text/css">
    <script src="../../js/pagnation.js"></script>
    <script src="../../js/openReview.js"></script>
    <script src="../../js/navMenu.js"></script>
    <title>CheckPoint</title>
</head>

<body onload="loadPage()">
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

    <div class="content">
        <h1>Activity menu</h1>
    </div>
    <div class="activity-container">
        <h2>Recent friend activity</h2>
        <div id="activity-section-page">
        </div>
        <div id="pagination">
            <?php include '../src/getPagnationMaxPages.php'?>
    </div>
    </div>

</body>
</html>
