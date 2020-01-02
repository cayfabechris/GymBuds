<?php
session_start();
//If the session variables, name, along with the session were started 
//on the previous page, the user has valid access to this page
if(isset($_SESSION['firstName'])){

//Session variables for debugging and testing only, comment out after each use
//$_SESSION['firstName'] = "Bob";

$name = $_SESSION['firstName'];

//Redirect back to login after 30 seconds, done for user security and privacy
header("refresh:30; url=login.php");

//Get rid of all user credentials and variables
session_destroy();
}

//User does not have valid authorization to use this page, redirect to login page.

else{
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
@import url('https://fonts.googleapis.com/css?family=Scada&display=swap');


</style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GymBuds: Account Created!</title>
    <script src="scripts/script-login.js"></script>
    <link rel="stylesheet" href="styles/style-success.css">
</head>
<body>
<header>
        <h1>
                <a href="login.php">GymBuds</a>
        </h1>

        <div id = "successTextWrapper">
        <h2>Thanks <?php echo $name; ?>,
         your password has been reset. You may <a href="login.php">login here</a>.
        </h2> 
        </div>
        
        <footer>
            Website made by Cayfabe Studios &copy;
</footer>
    
</body>
</html>