<?php
  include '../src/config.php'; 
  include '../src/processSignup.php';

  // print all tables
  //$listdbtables = array_column($conn->query('SHOW TABLES')->fetch_all(), 0);  
  //echo implode(", ", $listdbtables);

  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  if (isset($_SESSION["username"])) {
    header("Location: http://localhost/checkpoint/html/public/profile.php");
    exit();
  }

  $Err = "";
  if (isset($_SESSION["errorSignup"])) {
    $Err = $_SESSION["errorSignup"];
  }
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link href="../../css/signin.css" rel="stylesheet" type="text/css">
    <script src="../../js/passwordVisability.js"></script>
    <title>CheckPoint Sign Up</title>
</head>

<body>
<img src="../../images/checkpoint-logo.PNG" alt="CheckPoint" class="logo">

    <div class="signin-container">
        <h2>Sign Up</h2>
        <form action="../src/processSignup.php" method="POST">
        `    <input type="text" placeholder="first name" id="fname" name="fname" value="<?php echo $fname; ?>" required>
            <input type="text" placeholder="surname" id="sname" name="sname" value="<?php echo $sname; ?>" required>
            <input type="text" placeholder="unique username" id="uname" name="uname" value="<?php echo $uname; ?>" required>
            <input type="email" placeholder="email" id="email" name="email" value="<?php echo $email; ?>" required>
            <input type="password" placeholder="password" id="password" name="password" required>
            <input type="checkbox" id="TandC" name="TandC">
            <label for="TandC" id="TandCLabel">Tick to accept you have read the Terms and Conditions</label><br>
            <input type="checkbox" id="showPass" onclick="passwordButton()">
            <label for="showPass" id="showPasswordLabel">Show Password</label>
            <a href="signin.php">Already have an account? </a>
            <button type="submit">Sign Up</button>
            <span class="error"> <?php echo $Err; ?></span>`
        </form>
    </div>

    <footer class="sign-in footer">
        <div class="navItem">
            <p><a href="tsandcs.html">Terms and Conditions</a></p>
        </div>

        
        <div class="navItem">
            <p>Contact</p>
        </div>
    </footer>

</body>
</html>
