<?php

//Config file, separate for security reasons
include 'config.php';
session_start();
if(isset($_SESSION["firstName"]) && isset($_SESSION["email"])){
//Message that updates when an error occurs i.e Username taken, email taken
$firstName = $_SESSION["firstName"];
$email = $_SESSION['email'];
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

$password = $connection->real_escape_string($_POST['password']);
$cpassword = $connection->real_escape_string($_POST['Cpassword']);


//Check if any input field is empty
if(!empty($password) && !empty($cpassword)){

    //Is the input email in a proper format i.e abc123@x.com
    if($password != $cpassword){
        $msg = "Passwords do not match. Please re-enter passwords to ensure correctness.";
    }

    else{

        //Check if the given username already exists
        $hashedPassword = sodium_crypto_pwhash_scryptsalsa208sha256_str($password, SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_OPSLIMIT_INTERACTIVE, SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_MEMLIMIT_INTERACTIVE);

        $sqlUpdate = $connection->query("UPDATE user SET password = '$hashedPassword' WHERE email = '$email'");

        Sodium_memzero($password);
        Sodium_memzero($hashedPassword);
        header("location: passwordsuccess.php");
        $connection->close();

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

}

else{
    header("location: login.php");
    $msg = "Session not started";
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
                 
            <input type="password" ondblClick="this.select();" name = "password" id="password" placeholder="New Password (Max Length 20)" size = "25" minlength="5" maxlength="20" required>
            <br>
            <br>
            <input type="password" ondblClick="this.select();" name = "Cpassword" id="Cpassword" placeholder="Repeat Password" size = "25" minlength="5" maxlength="20" required>

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
            Website made by Cayfabe Studios &copy;
        </footer>
</body>

</html>
                