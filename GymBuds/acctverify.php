<?php

//Config file, separate for security reasons
include 'config.php';

//Message that updates when an error occurs i.e Username taken, email taken
$msg = "";
$msgClass = "";
try{
//Submit button has been clicked on the register form
if(filter_has_var(INPUT_POST, 'submit')){

    //Database credentials
    $DB_HOST = $config['DB_HOST'];
    $DB_USER = $config['DB_USERNAME'];
    $DB_PASSWORD = $config['DB_PASSWORD'];
    $DB_NAME = $config['DB_DATABASE'];

    //Connection to MySQL database
    $connection = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

    //Connection failure
    if ($connection->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

// All registration form inputs

$email = $connection->real_escape_string($_POST['email']);
$token = $connection->real_escape_string($_POST['token']);


//Check if any input field is empty
if(!empty($email) && !empty($token)){

    //Is the input email in a proper format i.e abc123@x.com
    if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){

        $msg = 'Please use a valid email';
    }

    else{

    }
}
//Empty input field found
else{
    $msg = 'Please fill in all fields';
    $msgClass = 'alert-danger';
}
    //Check if the given username already exists
    $sql = $connection->query("SELECT email FROM user WHERE email = '$email'");
    
    if($sql->num_rows == 0){
        //Username already exists/taken
        $msg ="Email does not exist";
    }

    else{
        //Check if the given email exists
        $sql = $connection->query("SELECT * FROM user WHERE email = '$email' AND isEmailConfirmed = 1");
        if($sql->num_rows > 0 && strlen($email) > 0){
            //Email already exists/taken
            $msg ="Account already verified";
        }
    
        else{
            
            $sql = $connection->query("SELECT * FROM user WHERE email = '$email' AND token = '$token'");

            if($sql->num_rows == 0 && strlen($email) > 0){
                //Email already exists/taken
                $msg ="Token does not match the one provided in your email, please check your email and try again";
            }
            else{

            //echo "Create account reached!";
            //SQL statement to insert user inputs to the database
            $sql = "UPDATE user SET isEmailConfirmed = 1 WHERE email = '$email' AND token = '$token'";

           if ($connection->query($sql) === TRUE) {
            //SQL insertion successful
            echo "Account verified successfully";

          

  

             //Start a session to send data to the Account Success page
             session_start();

             $_SESSION['email'] = htmlentities($_POST['email']);

             //Redirects the page to account success page
             header('Location: verified.php');

            } else {
                //SQL connection error
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
            $connection->close();
        }
    }
}
}
}

//Error has occurred
catch(PDOException $e){
    echo "Error:".$e->getMessage();
}

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
    <title>GymBuds: Verify Account</title>
    <script src="scripts/script-login.js"></script>
    <link rel="stylesheet" href="styles/style-create_account.css">
</head>
<body>
<header>
        <h1>
                <a href="index.html">GymBuds</a>
        </h1>

        <?php if($msg != ''): ?>
            <div id="create_account-wrapper">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>
        
        
        <div id="create_account-wrapper">
            <h3>
            Verify Your Account
            </h3>
            <form name="verify-account" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="create-account-labels">
            
            <input type="email" ondblClick="this.select();" name = "email" id="email" placeholder="Email" minlength="5" maxlength="50" size = "25" required>
            <br>
            <br>
            <input type="text" ondblClick="this.select();" name = "token" id="token" placeholder="Token (In your signup email)" size = "25"  maxlength="10" required>
         

            </div>
            <br>
            <button type="submit" name="submit" value="submit" onclick="reconvertPW()">Verify Account</button>
   
            
            </form>
            
            </div>
        
        <footer>
            Website made by Cayfabe Studios &copy;
        </footer>
        </body>
    
</body>
</html>