<?php

//Config file, separate for security reasons
include 'resources/config.php';
session_start();
//If the session variables, name, email, along with the session were started 
//on the previous page, the user has valid access to this page

if(isset($_SESSION["firstName"]) && isset($_SESSION["email"])){

//Session variables for debugging and testing only, comment out after each use
//$_SESSION['firstName'] = "Bob";
//$_SESSION['email'] = "bob123@gmail.com";

$firstName = $_SESSION["firstName"];
$email = $_SESSION['email'];

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

$password = $connection->real_escape_string($_POST['password']);
$cpassword = $connection->real_escape_string($_POST['Cpassword']);


//Check if any input field is empty
if(!empty($password) && !empty($cpassword)){

    //Do the passwords match?
    if($password != $cpassword){
        //Passwords do not match
        $msg = "Passwords do not match. Please re-enter passwords to ensure correctness.";
    }

    //Passwords match
    else{

        //Encrypt the new password and pass it back to the database
        $hashedPassword = sodium_crypto_pwhash_scryptsalsa208sha256_str($password, SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_OPSLIMIT_INTERACTIVE, SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_MEMLIMIT_INTERACTIVE);

        $sqlUpdate = $connection->query("UPDATE user SET password = '$hashedPassword' WHERE email = '$email'");

        //Get rid of the passwords from memory
        Sodium_memzero($password);
        Sodium_memzero($hashedPassword);

        //Redirect to password success page
        header("location: passwordsuccess.php");
        $connection->close();

    }
        }
    
    //Field found empty
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

}

//User does not have valid authorization to use this page, redirect to login page.
else{
    header("location: login.php");
    $msg = "Session not started";
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

<h3> Hi <?php echo $firstName; ?>, please create a new password </h3>

        <?php if($msg != ''): ?>
        <div id="login-wrapper">
            <?php echo $msg; ?>
        </div>
        <?php endif; ?>

        <div id="login-wrapper">
            <h3>
            Create New Password
        </h3>
            <form name="createPassword" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="login-labels">
                 
            <input type="password" ondblClick="this.select();" name = "password" id="password" placeholder="New Password (Max Length 20)" size = "30" minlength="5" maxlength="20" required>
            <br>
            <br>
            <input type="password" ondblClick="this.select();" name = "Cpassword" id="Cpassword" placeholder="Repeat Password" size = "30" minlength="5" maxlength="20" required>

            <script>
                function unhidePW() {
                var x = document.getElementById("password");
                var y = document.getElementById("Cpassword");
                    if (x.type === "password") {
                    x.type = "text";
                    y.type = "text";
                    } 
                    
                    else {
                    x.type = "password";
                    y.type = "password";
                    }
                }
                </script>

                <script>
                function reconvertPW() {
                var x = document.getElementById("password");
                var y = document.getElementById("Cpassword");
                 
                    x.type = "password";
                    y.type = "password";
                    
                }
                </script>

            <br>
            <br>

            <input type="checkbox" onclick="unhidePW()">Show Password

                </div>
                <br>
                <button name="submit" value="submit" type="submit">Create New Password</button>
                <br>
                <br>

            </form>

        </div>

        <footer>
            Website made by Christian Rodriguez 
        </footer>
</body>

</html>
                