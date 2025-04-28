<?php
  session_start();
  include '../src/config.php'; 

  error_reporting(-1);
  ini_set('display_errors', 'On');
  set_error_handler("var_dump");

  $_SESSION["previousPage"] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']
    === 'on' ? "https" : "http") . "://" . 
    $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

  if (isset($_SESSION["username"])) {
    $user = $_SESSION["username"];
  } else {
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
    <script src="../../js/activityPage.js"></script>
    <script src="../../js/openReview.js"></script>
    <title>Videogame review webpage</title>
</head>

<body onload="showResult()">
<nav class="navbar">
    <div class="profile">
        <a href="profile.php">My Profile</a>
        <div class="dropdown-content">
            <a href="profile.php">view my profile</a>
            <a href="#">view activity</a>
            <a href="editProfile.php">edit my profile</a>
            <a href="../src/processLogOut.php">log out</a>
        </div>
    </div>
    <a href="home.php">Home</a>
    <a href="log.php">Log</a>
    <a href="search.php">Search</a>
</nav>

    <div class="content">
        <h1>Activity menu</h1>
    </div>
    <div class="activity-container">
        <h2> recent friend activity </h2>
        <div id="activity-section-page">
        </div>
    </div>