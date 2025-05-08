<?php
  // Enable error reporting for debugging
  session_start();
  include '../src/config.php';   

  error_reporting(-1);
  ini_set('display_errors', 'On');
  set_error_handler("var_dump");

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
    <script src="../../js/searchpage.js"></script>
    <script src="../../js/navMenu.js"></script>
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
        <form id="submission">
            <h3>Please enter your search term: </h3>
            <input id="searchTerm" type="search" placeholder="start typing...">
            <button type="submit">Search</button>
            <div>
                <input type="radio" id="radio" name="games_user" value="games">
                <label for="games">Games</label>
                <input type="radio" id="radio" name="games_user" value="user">
                <label for="user">User</label>
            </div>
        </form>
        <div id="search"></div>
    </div>

</body>
</html>