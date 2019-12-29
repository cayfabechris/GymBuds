<?php

if(isset($_SESSION['firstName']))
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
$password = $connection->real_escape_string($_POST['password']);


//Check if any input field is empty
if(!empty($email) && !empty($password)){

    //Is the input email in a proper format i.e abc123@x.com
    if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){

        $msg = 'Please use a valid email';
    }

    else{

        //Check if the given username already exists
    $sql = "SELECT password FROM user WHERE email = '$email'";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();
    $hashedPassword = $row["password"];


    if($row["password"] == null && strlen($email) > 0){
        //Email already exists/taken
        $msg ="Email does not exist";
    }

    else{
    
        $sql = "SELECT isEmailConfirmed FROM user WHERE email = '$email'";
        $result = $connection->query($sql);
        $row = $result->fetch_assoc();
        $confirmed = $row["isEmailConfirmed"];

    if($confirmed == 0){
        $msg ="Please verify your email";
    }    

    else if (Sodium_crypto_pwhash_scryptsalsa208sha256_str_verify($hashedPassword, $password)) {
        // recommended: wipe the plaintext password from memory
        Sodium_memzero($password);
        Sodium_memzero($hashedPassword);

        echo "Login successful!";
        $msg = "Login successful!";

             //Start a session to send data to the Account Success page
             session_start();

             $_SESSION['name'] = htmlentities($_POST['first-name']);
             $_SESSION['email'] = htmlentities($_POST['email']);

             //Redirects the page to account success page
             header('Location: dashboard.php');
 
            } 
            
            else {
                Sodium_memzero($password);
                Sodium_memzero($hashedPassword);


                //SQL connection error
            $msg = "Password is incorrect";
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
            
            $connection->close();
        }
    }
    }

    //Empty input field found
else{
    $msg = 'Please fill in all fields';
    $msgClass = 'alert-danger';
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
    @import url('https://fonts.googleapis.com/css?family=Fjalla+One&display=swap');
    @import url('https://fonts.googleapis.com/css?family=M+PLUS+1p&display=swap');
    @import url('https://fonts.googleapis.com/css?family=Arimo&display=swap');
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


        <img src="images/dumbbell.png" height="128" width="128" alt="No dumbbell!">
        <h3>Gym together, Gain together!</h3>
    </header>

    <?php if($msg != ''): ?>
    <div id="login-wrapper">
        <?php echo $msg; ?>
    </div>
    <?php endif; ?>

    <div id="login-wrapper">
        <h3>
            Login
        </h3>
        <form name="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="login-labels">
                <input type="email" ondblClick="this.select();" name=email id="email" placeholder="Email" size="25"
                    required>
                <br><br>
                <input type="password" ondblClick="this.select();" name=password id="password" placeholder="Password"
                    minlength="5" maxlength="20" size="25" required>
            </div>
            <br>
            <button name="submit" value="submit" type="submit">Login</button>
            <br><br>
            <a href="register.php" class="links">Sign Up</a>
            <br>
            <a href="forgotpassword.php" class="links">Forgot password</a>

        </form>

    </div>

    <footer>
        Website made by Cayfabe Studios &copy;
    </footer>
</body>

</html>