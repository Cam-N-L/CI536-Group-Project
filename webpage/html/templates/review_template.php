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
    <title>CheckPoint</title>
</head>

<body onload="showResult()">
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
                <a href="public/search.php">Search</a>
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

    <div class="game-container">
        <h1> <?php echo $game['Title'] . " reviewed by " . $game['Username'];?> </h1>
        <p> <?php echo "reviewed on " . sortDate($game['DateOfReview']) . " " . (include "../src/likeCount.php") . " • rated " . str_repeat("⭐", $game['Rating']); ?> </p>
        <p> <?php echo $game['Review']; ?> </p>
        <form action="../src/processLike.php" method="POST">
            <button> <?php include '../src/likeButton.php'?> </button>
            <input type="hidden" name="reviewID" value="<?php echo $review_id ?>">
        </form>
        <hr>
        <h1>Comments </h1>
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