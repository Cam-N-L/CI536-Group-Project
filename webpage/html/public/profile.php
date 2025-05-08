<?php
  // Enable error reporting for debugging
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
    <script src="../../js/openGame.js"></script>
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
                    <a href="processLogOut.php">Log Out</a>
                </div>
            </div>
        </div>

        <div class="logo">
            <a href="home.php"><img src="../images/checkpoint-logo.PNG" alt="CheckPoint Logo"></a>
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
        <a href="#">Search</a>
        <a href="profile.php">My Profile</a>
        <a href="processLogOut.php">Log Out</a>
    </div>

    <div class="content">
        <h1><?php echo $username; ?>'s profile</h1>
        <p>View and manage your favorite games and ratings.</p>
    </div>
    <div class="favorites-container">
        <h2>Your Favorite Games</h2>
        <div class="favorites-section">
            <?php include '../src/fetchPersonalFavGames.php'?>
        </div>
        </div>
    </div>

    <!-- js, needs to be moved-->
    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobileMenu');
            if (menu.style.display === 'flex') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'flex';
            }
        }

        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobileMenu');
            const hamburger = document.querySelector('.hamburger');
            if (!menu.contains(event.target) && !hamburger.contains(event.target)) {
                menu.style.display = 'none';
            }
        });
    </script>

</body>
</html>