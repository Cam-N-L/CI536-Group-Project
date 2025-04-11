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

   ?> 

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link href="../../css/home.css" rel="stylesheet" type="text/css">
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
    <div class="game-container">
        <h1> <?php echo $game['Title']; ?> </h1>
        <p> created by<?php echo sortArrays($game['Developers']); ?> • <?php echo sortArrays($game['Genres']); ?> • <?php echo sortDate($game['Release_date']); ?></p>
        <p> <?php echo $game['Summary']; ?> </p>
        <p> Released on </strong> <?php echo sortArrays($game['Platforms'])?> with an average rating of <?php echo $game['Rating']; ?></p>
    </div>
</body>
</html>
