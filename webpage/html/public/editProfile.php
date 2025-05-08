<?php
  // Enable error reporting for debugging
  session_start();
  include '../src/config.php'; 
  
  error_reporting(-1);
  ini_set('display_errors', 'On');
  set_error_handler("var_dump");

  if (isset($_SESSION["username"])) {
    $usernames = $_SESSION["username"];
    include '../src/gatherProfileInfomation.php';
  } else {
    $_SESSION["previousPage"] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']
    === 'on' ? "https" : "http") . "://" . 
    $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    header("Refresh:0; url=https://ik346.brighton.domains/groupProjectTests/html/public/signin.php");
    exit();
  }

  $Err = $ErrGames = "";
  if (isset($_SESSION["erroredit"])) {
    $Err = $_SESSION["erroredit"];
    unset($_SESSION["erroredit"]);
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link href="../../css/signin.css" rel="stylesheet" type="text/css">
    <link href="../../css/home.css" rel="stylesheet" type="text/css">
    <script src="../../js/passwordVisability.js"></script>
    <script src="../../js/faveOrder.js"></script>
    <script src="../../js/searchFave.js"></script>
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
        <a href="#">Search</a>
        <a href="profile.php">My Profile</a>
        <a href="processLogOut.php">Log Out</a>
    </div>

    <div class="signin-container">
        <h2>Account Infomation</h2>
        <form id="credentials" action="../src/processEditProfile.php" method="POST">
            <label for="uname"> Username </label>
            <input type="text" id="uname" name="uname" placeholder="Username" value="<?php echo $row['Username']; ?>" required>
            <label for="fname"> First name </label>
            <input type="text" id="fname" name="fname" placeholder="Firstname" value="<?php echo $row['Firstname']; ?>" required>
            <label for="sname"> Surname </label>
            <input type="text" id="sname" name="sname" placeholder="Surname" value="<?php echo $row['Surname']; ?>" required>
            <label for="sname"> email </label>
            <input type="text" id="email" name="email" placeholder="email" value="<?php echo $row['Email']; ?>" required>
            <button type="submit" form="credentials">save</button>
            <span class="error"> <?php echo $Err; ?></span>
        </form>
    </div>

    <div class="signin-container">
        <h2>Your top 5 games</h2>
        <p id="hint">Search to add up to five games, drag to order or double click to remove! </p>
        <input id="livesearchinput" form ="favs" name="livesearchinput" type="text" placeholder="Game Title" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" onkeyup="showResult(this.value)">
        <div id="livesearch"></div>
        <form id="favs" action="../src/processFavGamesAdd.php" method="POST">
        <ul class="sortable-list">
            <?php include '../src/fetchFavGames.php' ?>
        </ul>
        <input type="hidden" name="gameList" id="gameNames">
        <button type="submit" form="favs">save</button>
        </form>
    </div>

    <footer class="sign-in footer">
        <div class="navItem">
            <p>Terms</p>
        </div>

        <div class="navItem">
            <p>Privacy Policy</p>
        </div>

        <div class="navItem">
            <p>Contact</p>
        </div>
    </footer>

</body>
</html>