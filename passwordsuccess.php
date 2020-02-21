<?php
session_start();
//If the session variables, name, along with the session were started 
//on the previous page, the user has valid access to this page
if (isset($_SESSION['firstName'])) {

    //Session variables for debugging and testing only, comment out after each use
    //$_SESSION['firstName'] = "Bob";

    $name = $_SESSION['firstName'];

    //Redirect back to login after 30 seconds, done for user security and privacy
    //header("refresh:30; url=login.php");

    //Get rid of all user credentials and variables
    session_destroy();
}

//User does not have valid authorization to use this page, redirect to login page.

else {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GymBuds: Password Reset</title>
    <script src="scripts/script.js"></script>

    <link rel="stylesheet" href="styles/fonts.css">\
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <div id="main-wrapper">
        <header class="header-wrapper">
            <div id="header-title">
                <h1>
                    <a href="login.php">GymBuds</a>
                </h1>
            </div>
            <div class="content-text">
                <h2>Thanks <?php echo $name; ?>,
                    your password has been reset. You may <a href="login.php">login here</a>.
                </h2>
            </div>
    </div>
    <footer class="footer">
        Website made by Christian Rodriguez (<a href="https://github.com/cjrcodes">cjrcodes on GitHub</a>)
    </footer>

</body>

</html>