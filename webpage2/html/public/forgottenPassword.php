<?php 
session_start();
include '../src/config.php'; 
include '../src/processForgottenPassword.php'; 

error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");

$Err = "";
if (isset($_SESSION["errorpassword"])) {
    $Err = $_SESSION["errorpassword"];
    unset($_SESSION["errorpassword"]);
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
<img src="../../images/checkpoint-logo.PNG" alt="CheckPoint" class="logo">

    <div class="signin-container">
        <h2>Sign In</h2>
        <form id="credentials" action="../src/processForgottenPassword.php" method="POST">
            <input type="text" id="uname" name="uname" placeholder="Username" value="<?php echo $uname; ?>" required>
            <input type="password" placeholder="Password" id="password" name="password" required>
            <input type="password" placeholder="confirm Password" id="confirmPassword" name="confirmPassword" required>
        </form>
        <input type="checkbox" id="showPass" onclick="passwordButton()">
        <label for="showPass" id="showPasswordLabel">Show Password</label>      
            <a href="signin.php">Login instead</a>
            <button type="submit" form="credentials">Submit</button>
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
