<?php
  // Enable error reporting for debugging
  session_start();
  include '../src/config.php'; 
  include '../src/processLogin.php';

  // print all tables
  //$listdbtables = array_column($conn->query('SHOW TABLES')->fetch_all(), 0);  
  //echo implode(", ", $listdbtables);

  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  if (isset($_SESSION["username"])) {
    header("Refresh:0; url=https://ik346.brighton.domains/groupProjectTests/html/public/profile.php");
    exit();
  }

  $Err = "";
  if (isset($_SESSION["errorLogin"])) {
    $Err = $_SESSION["errorLogin"];
    unset($_SESSION["errorLogin"]);
  }
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link href="../../css/signin.css" rel="stylesheet" type="text/css">
    <script src="../../js/passwordVisability.js"></script>
    <title>CheckPoint</title>
</head>

<body>
    <h1>CheckPoint</h1>

    <div class="signin-container">
        <h2>Sign In</h2>
        <form id="credentials" action="../src/processLogin.php" method="POST">
            <input type="text" id="uname" name="uname" placeholder="Username" value="<?php echo $uname; ?>" required>
            <input type="password" placeholder="Password" id="password" name="password" required>
        </form>
            <input type="checkbox" onclick="passwordButton()">Show Password         
            <a href="signup.php"> create an account </a>
            <form action = "forgottenPassword.php" method="POST" id="pwordreset">
            <button id="passwordReset"> forgotten password?</button>
            </form>
            <button type="submit" form="credentials">Sign In</button>
            <span class="error"> <?php echo $Err; ?></span>
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