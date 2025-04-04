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
    <title>Videogame review webpage</title>
</head>

<body>
<nav class="navbar">
    <div class="profile">
        <a href="profile.php">My Profile</a>
        <div class="dropdown-content">
            <a href="profile.php">view my profile</a>
            <a href="editProfile.php">edit my profile</a>
            <a href="../src/processLogOut.php">log out</a>
        </div>
    </div>
    <a href="home.php">Home</a>
    <a href="log.php">Log</a>
    <a href="#">Search</a>
</nav>
    <div class="content">
        <form id="submission">
            <h3> please enter your search term </h3>
            <input id="searchTerm" type="search" placeholder="start typing...">
            <button type="submit"> search </button>
            <div>
                <input type="radio" id="radio" name="games_user" value="games">
                <label for="games">games</label>
                <input type="radio" id="radio" name="games_user" value="user">
                <label for="user">user</label>
            </div>
        </form>
        <div id="search"></div>
    </div>
</body>
</html>