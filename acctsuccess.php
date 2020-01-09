<?php

//Start session
session_start();

//If the session variable, name, along with the session were
//started on the previous page, the user has valid access to this page
if(isset($_SESSION['name'])){

//Hard coded session variables for debugging and testing only, comment out after each use
//$_SESSION['name'] = "Bob";
//$_SESSION['email'] = "bob123@gmail.com";

$name = $_SESSION['name'];
$email = $_SESSION['email'];

//After 30 seconds, return to the login. Done for user privacy
header("refresh:30; url=login.php");

//Get rid of all user credentials
session_destroy();
}

else{
//User is trying to access this page without having proper authorization, send them back to the login page
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<style>
@import url('https://fonts.googleapis.com/css?family=Baloo+Bhaina&display=swap');
@import url('https://fonts.googleapis.com/css?family=Fjalla+One&display=swap');
@import url('https://fonts.googleapis.com/css?family=M+PLUS+1p&display=swap');
@import url('https://fonts.googleapis.com/css?family=Arimo&display=swap');
@import url('https://fonts.googleapis.com/css?family=Signika+Negative&display=swap');

</style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GymBuds: Account Created!</title>
    <script src="scripts/script-login.js"></script>
    <link rel="stylesheet" type="text/css" href="styles/style-success.css">
</head>
<body>
<header>
        <h1>
                <a href="login.php">GymBuds</a>
        </h1>
</header>
        <div id = "successTextWrapper">
        <h2>Thanks <?php echo $name; ?>,
         please check your email at <?php echo $email; ?>
          to verify your account.
        </h2> 
        </div>
        
        <footer>
            Website made by Christian Rodriguez 
</footer>
    
</body>
</html>