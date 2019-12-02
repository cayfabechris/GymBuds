<?php
session_start();

$name = $_SESSION['name'];
$email = $_SESSION['email'];



?>

<!DOCTYPE html>
<html lang="en">
<style>
@import url('https://fonts.googleapis.com/css?family=Baloo+Bhaina&display=swap');
@import url('https://fonts.googleapis.com/css?family=Fjalla+One&display=swap');
@import url('https://fonts.googleapis.com/css?family=M+PLUS+1p&display=swap');
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
                <a href="index.html">GxmBxds</a>
        </h1>

        <div id = "verify-acct-wrapper">
        <h5>Thanks <?php echo $name; ?>,
         please check your email at <?php echo $email; ?>
          to verify your account.
        </div>
        
        
      
        <footer id ="footer">
            Website made by Cayfabe Studios &copy;
</footer>
        </body>
    
</body>
</html>