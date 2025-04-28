<?php
  session_start();
  include '../src/config.php'; 

  error_reporting(-1);
  ini_set('display_errors', 'On');
  set_error_handler("var_dump");

  if (isset($_SESSION["username"])) {
    $user = $_SESSION["username"];
  } else {
    $_SESSION["previousPage"] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']
    === 'on' ? "https" : "http") . "://" . 
    $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    header("Refresh:0; url=https://ik346.brighton.domains/groupProjectTests/html/public/signin.php");
    exit();
  }

$error = $errorLog = "";
  if (isset($_SESSION["errorLog"])) {
    $error = $_SESSION["errorLog"];
    unset($_SESSION["errorLog"]);
  } else if (isset($_SESSION["errorLogPlayed"])) {
    $errorLog = $_SESSION["errorLogPlayed"];
    unset($_SESSION["errorLogPlayed"]);
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["gameName"])) {
      $title = $_POST["gameName"];
    }
  }
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link href="../../css/home.css" rel="stylesheet" type="text/css">
    <script src="../../js/search.js"></script>
    <title>Videogame review webpage</title>
</head>

<body>
<nav class="navbar">
    <div class="profile">
        <a href="profile.php">My Profile</a>
        <div class="dropdown-content">
            <a href="profile.php">view my profile</a>
            <a href="activity.php">view activity</a>
            <a href="editProfile.php">edit my profile</a>
            <a href="../src/processLogOut.php">log out</a>
        </div>
    </div>
    <a href="home.php">Home</a>
    <a href="#">Log</a>
    <a href="search.php">Search</a>
</nav>

    <div class="content">
        <h1>Log Review</h1>
        <p>Log and review your favourite games.</p>
    </div>
    <div class="review-container">
      <h2>Game</h2>
        <input id="livesearchinput" form ="review" name="livesearchinput" type="text" placeholder="Game Title" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" onkeyup="showResult(this.value)" required>
          <div id="livesearch"></div>
          <div id="titleElement" style="visibility: hidden;"> <input id="livesearchinputHidden" form ="log" name="livesearchinputHidden" type="text" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>"> </div>
    </div>
    <div class="review-container">
    <form class="review-form" id="review" action="../src/processLog.php" method="POST">
        <h2>Game Review</h2>
            <textarea id="review" name="review" placeholder="Write your review here..." rows="5" value="<?php echo isset($review) ? htmlspecialchars($review) : ''; ?>" required></textarea>
            <label for="rating">Rating:</label>
            <select id="rating" name="rating" required>
                <option value="5">⭐⭐⭐⭐⭐</option>
                <option value="4">⭐⭐⭐⭐</option>
                <option value="3">⭐⭐⭐</option>
                <option value="2">⭐⭐</option>
                <option value="1">⭐</option>
            </select>
            <label for="playedOrComplted"> Completed? </label>
            <input type="checkbox" id="playedOrCompleted" name="playedOrCompleted" value="1">
            <button type="submit">Submit Review</button>
            <p> <?php echo $error; ?></p>
        </form>
    </div>
    <p> or... </p>
    <div class="review-container">
    <form class="review-form" id="log" action="../src/processPlayed.php" method="POST">
      <h2> Log Played Game </h2>
        <label for="playedOrCompltedPlay"> Completed? </label>
        <input type="checkbox" id="playedOrCompletedPlay" name="playedOrCompletedPlay" value="1">
        <button type="submit">Submit Log</button>
        <p> <?php echo $errorLog; ?></p>
      </form>
    </div>
</body>
</html>