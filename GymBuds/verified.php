<?php

//Config file, separate for security reasons
include 'config.php';

session_start();
if(isset($_SESSION['email'])){
$email = $_SESSION['email'];

try{

        //Database credentials
        $DB_HOST = $config['DB_HOST'];
        $DB_USER = $config['DB_USERNAME'];
        $DB_PASSWORD = $config['DB_PASSWORD'];
        $DB_NAME = $config['DB_DATABASE'];
    
        //Connection to MySQL database
        $connection = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);
    
        //Connection failure
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }
    
    
                //SQL statement to insert user inputs to the database
                $sql = "SELECT first_name FROM user WHERE email = '$email'";
                $result = $connection->query($sql);
                $row = $result->fetch_assoc();
                $firstName = $row["first_name"];
            
                $connection->close();
                session_destroy();

            }
    //Error has occurred
    catch(PDOException $e){
        echo "Error:".$e->getMessage();
        $connection->close();

    }
}

else{
    header('Location: login.php');
}
?>


<!DOCTYPE html>
<html lang="en">
<style>
@import url('https://fonts.googleapis.com/css?family=Baloo+Bhaina&display=swap');
@import url('https://fonts.googleapis.com/css?family=Fjalla+One&display=swap');
@import url('https://fonts.googleapis.com/css?family=M+PLUS+1p&display=swap');
@import url('https://fonts.googleapis.com/css?family=Arimo&display=swap');

</style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GymBuds: Verified!</title>
    <script src="scripts/script-login.js"></script>
    <link rel="stylesheet" href="styles/style-create_account.css">
</head>
<body>
<header>
        <h1>
                <a href="login.php">GymBuds</a>
        </h1>
            <h3>
            Your Account Has Been Verified!
            </h3>
           
            Thanks <?php echo $firstName?>, you now have full access to your GymBuds account! 

        <footer>
            Website made by Cayfabe Studios &copy;
        </footer>
        </body>
    
</body>
</html>