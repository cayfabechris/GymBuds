<?php

//Config file, separate for security reasons
include 'resources/config.php';

//Message that updates when an error occurs i.e Username taken, email taken
$msg = "";
$msgClass = "";
try{
//Submit button has been clicked
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

// All form inputs

$email = $connection->real_escape_string($_POST['email']);
$fpToken = $connection->real_escape_string($_POST['fpToken']);

//Check if any input field is empty
if(!empty($email) && !empty($fpToken)){

    //Is the input email in a proper format i.e abc123@x.com
    if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){

        $msg = 'Please use a valid email';
    }

    //Email in proper format
    else{

        //Check if given email exists
    $sql = $connection->query("SELECT * FROM user WHERE email = '$email'");
    
    //Email does not exist
    if($sql->num_rows == 0 && strlen($email) > 0){
        
        $msg ="Email does not exist";
    }

    //Email exists
    else if($sql->num_rows == 1){


        $row = $sql->fetch_assoc();
        //Check if forgotten password token matches the unique one for the user
        if($row['FPToken'] == $fpToken){

            //Create a new forgotten password token for the user, done for security
            $fpToken = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789!/()";
            $fpToken = str_shuffle($fpToken);
            $fpToken = substr($fpToken, 0, 10);
    
            //Update FPToken
            $sqlUpdate = $connection->query("UPDATE user SET FPToken = '$fpToken' WHERE email = '$email'");
            
            //Session start for next page
            session_start();

            //Personalized credentials for the next page
            $firstName = $row['first_name'];
            $email = $row['email'];

            $_SESSION['firstName'] = $firstName;
            $_SESSION['email'] = $email;

            //Redirect to new password page
            header("location: newpassword.php");
            $connection->close();
        }

        //Incorrect FP Token
        else{
            $msg = "Forget Password Token incorrect, please refer to forgotten password email";
        }


    }

    else{
                //SQL connection error
            $msg = "Error";
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
            
        }
    }

    else{
        $msg = "Please fill in all fields";
    }
    }
}
//Error has occurred
catch(PDOException $e){
    $msg = "Error:".$e->getMessage();
    echo "Error:".$e->getMessage();
    $connection->close();
}

?>

<!DOCTYPE html>
<html>
<style>
    @import url('https://fonts.googleapis.com/css?family=Baloo+Bhaina&display=swap');
    @import url('https://fonts.googleapis.com/css?family=M+PLUS+1p&display=swap');
    @import url('https://fonts.googleapis.com/css?family=Arimo&display=swap');
    @import url('https://fonts.googleapis.com/css?family=Signika+Negative&display=swap');
    @import url('https://fonts.googleapis.com/css?family=Scada&display=swap');

</style>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<head>
    <script src="scripts/script-login.js"></script>
    <link rel="stylesheet" href="styles/style-login.css">
    <title>
        GymBuds
    </title>
</head>

<body>
    <header>
        <h1>
            <a href="login.php">GymBuds</a>

        </h1>

        <?php if($msg != ''): ?>
        <div id="login-wrapper">
            <?php echo $msg; ?>
        </div>
        <?php endif; ?>

        <div id="login-wrapper">
            <h3>
            Verify Password Token
            </h3>
            <form name="verifyPasswordToken" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="login-labels">
                <input type="email" ondblClick="this.select();" name=email id="email" placeholder="Email" size="30" maxlength = 50 required>
                    <br>
                    <br>
                    <input type="text" ondblClick="this.select();" name=fpToken id="fpToken" placeholder="Token (Included in your email)" size="30" maxlength = 10 required>
                    <br>
                    <br>
                </div>
                <button name="submit" value="submit" type="submit">Verify Password Token</button>
                <br><br>

            </form>

        </div>

        <footer>
            Website made by Christian Rodriguez 
        </footer>
</body>

</html>