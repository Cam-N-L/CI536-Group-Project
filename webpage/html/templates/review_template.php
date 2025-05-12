<?php 
  include '../src/sortArrays.php';

  error_reporting(-1);
  ini_set('display_errors', 'On');
  set_error_handler("var_dump");

    function sortDate($Data){
    $date = date_create($Data);
    $date = date_format($date,"l jS F Y g:i");
    return $date;
    }

    $_SESSION["previousPage"] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']
        === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?id=" . $review_id;
   ?> 

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link href="../../css/home.css" rel="stylesheet" type="text/css">
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
        <h1> <?php echo $game['Title'] . " reviewed by " . $game['Username'];?> </h1>
        <p> <?php echo " reviewed on " . sortDate($game['DateOfReview']) . " " . (include "../src/likeCount.php") . " • rated " . str_repeat("⭐", $game['Rating']); ?> </p>
        <p> <?php echo $game['Review']; ?> </p>
        <form action="../src/processLike.php" method="POST">
            <button> <?php include '../src/likeButton.php'?> </button>
            <input type="hidden" name="reviewID" value="<?php echo $review_id ?>">
        </form>
        <hr>
        <h1>Comments</h1>
        <?php 
        if (empty($comments)){
            echo "no comments yet";
        } else {
            foreach ($comments as $x) {
                $comment_id = $x['CommentID'];
                echo $x['Username'] . ": " . $x['Comments'] . "";
                echo '<form action="../src/processLikesComments.php" method="POST">';
                include "../src/likeCountComments.php";
                echo '<button>';
                include '../src/likeButtonComments.php';
                echo '</button>';
                echo '<input type="hidden" name="commentID" value="' . htmlspecialchars($x['CommentID']) . '">';
                echo '</form><br>';
            }  
        }
        ?>
        <hr>
        <form action="../src/processComment.php" method="POST">
            <input type="text" placeholder="comment..." id="comment" name="comment" required>
            <input type="submit" value="comment">
            <input type="hidden" name="reviewID" value="<?php echo $review_id ?>">
        </form>
    </div>

</body>
</html>
