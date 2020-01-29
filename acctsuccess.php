<?php

//Start session
session_start();

//If the session variable, name, along with the session were
//started on the previous page, the user has valid access to this page
//if (isset($_SESSION['name'])) {

    //Hard coded session variables for debugging and testing only, comment out after each use
    $_SESSION['name'] = "Bob";
    $_SESSION['email'] = "bob123@gmail.com";

    $name = $_SESSION['name'];
    $email = $_SESSION['email'];

    //After 30 seconds, return to the login. Done for user privacy
    //header("refresh:30; url=login.php");

    //Get rid of all user credentials
    //session_destroy();
//} else {
    //User is trying to access this page without having proper authorization, send them back to the login page
   // header("Location: login.php");
//}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GymBuds: Account Created!</title>
    <link rel="stylesheet" href="styles/fonts.css">
    <link rel="stylesheet" type="text/css" href="styles/style.css">
</head>

<body>
    <header class="header-wrapper">
        <div id="header-title">
        <h1>
            <a href="login.php">GymBuds</a>
        </h1>
        </div>
    </header>
    <div class="content-text">
        <h2>Thanks <?php echo $name; ?>,
            please check your email at <?php echo $email; ?>
            to verify your account.
        </h2>
    </div>
    <footer class="footer">
        Website made by Christian Rodriguez (<a href="https://github.com/cjrcodes">cjrcodes on GitHub</a>)
    </footer>

</body>

</html>