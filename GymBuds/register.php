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
$firstname = $connection->real_escape_string($_POST['first-name']);
$lastname = $connection->real_escape_string($_POST['last-name']);
$username = $connection->real_escape_string($_POST['username']);
$email = $connection->real_escape_string($_POST['email']);
$password = $connection->real_escape_string($_POST['password']);
$cpassword = $connection->real_escape_string($_POST['Cpassword']);

//Check if any input field is empty
if(!empty($firstname) && !empty($lastname) && !empty($username) && !empty($email) && !empty($password) && !empty($cpassword)){

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

//Passwords don't match
if($password != $cpassword){
    $msg = "Passwords do not match. Please re-enter passwords to ensure correctness.";
}



else{
    //Check if the given username already exists
    $sql = $connection->query("SELECT user_id FROM user WHERE username = '$username'");
    
    if($sql->num_rows > 0){
        //Username already exists/taken
        $msg ="Username taken, please use another username.";
    }

    else{
        //Check if the given email exists
        $sql = $connection->query("SELECT user_id FROM user WHERE email = '$email'");
        if($sql->num_rows > 0 && strlen($email) > 0){
            //Email already exists/taken
            $msg ="Email taken, please use another email.";
        }
    
        else{
            $token = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789!/()";
            $token = str_shuffle($token);
            $token = substr($token, 0, 10);

            //Encrypt password using sodium library
            $hashedPassword = sodium_crypto_pwhash_scryptsalsa208sha256_str($password, SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_OPSLIMIT_INTERACTIVE, SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_MEMLIMIT_INTERACTIVE);
            
            //echo "Create account reached!";
            //SQL statement to insert user inputs to the database
            $sql = "INSERT INTO user(first_name, last_name, username, email, password, isEmailConfirmed, Token)
            VALUES ('$firstname', '$lastname', '$username', '$email', '$hashedPassword', '0', '$token');";

           if ($connection->query($sql) === TRUE) {
            //SQL insertion successful
            echo "New record created successfully";

            $message = 

            "
            <html>
            <title>GymBuds Verification</title>
            <body>
            <h1>GymBuds</h1>
            <br>
            Use your token " . $token . " on your <a href = http://localhost/gymBuds/GymBuds/acctverify.php>verify page</a> to verify your GymBuds account.
            </body>
            </head>
            </html>";

            $headers = 
            "From: ". $config['HOST_EMAIL'];

            $headers .=
            "Reply-To: ". $config['HOST_EMAIL'];

            $headers .=
            'MIME-Version: 1.0' . "\r\n";

            $headers .= 
            'Content-type: text/html; charset=iso-8859-1' . "\r\n";

             //Sent to
             mail($email, 

             //Subject/title
             "Hello ". $_POST['first-name'] . ", Verify Your GymBuds Account!",
             
             //Body
             $message,
             
             //Headers
                $headers);

             //Start a session to send data to the Account Success page
             session_start();

             $_SESSION['name'] = htmlentities($_POST['first-name']);
             $_SESSION['email'] = htmlentities($_POST['email']);

             //Redirects the page to account success page
             header('Location: acctsuccess.php');
 
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
    $connection->close();

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
    <title>GymBuds: Register</title>
    <script src="scripts/script-login.js"></script>
    <link rel="stylesheet" href="styles/style-create_account.css">
</head>
<body>
<header>
        <h1>
                <a href="login.php">GymBuds</a>
        </h1>

        <?php if($msg != ''): ?>
            <div id="create_account-wrapper">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>
        
        
        <div id="create_account-wrapper">
            <h3>
                Sign Up
            </h3>
            <form name="create-account" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="create-account-labels">
            
            <input type="text" ondblClick="this.select();" name="first-name" id="first-name" placeholder="First Name"  minlength="2" maxlength="25" size = "25" required>
            <br>
            <br>
            <input type="text" ondblClick="this.select();" name = "last-name" id="last-name" placeholder="Last Name" minlength="2" maxlength="25" size = "25" required>
            <br>
            <br>
            <input type="text" ondblClick="this.select();" name= "username" id="username" placeholder="Username" minlength="5" maxlength="25" size = "25" required>
            <br>
            <br>
            <input type="email" ondblClick="this.select();" name = "email" id="email" placeholder="Email" minlength="5" maxlength="50" size = "25" required>
            <br>
            <br>
            <input type="password" ondblClick="this.select();" name = "password" id="password" placeholder="Password (Max Length 20)" size = "25" minlength="5" maxlength="20" required>
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
            <button type="submit" name="submit" value="submit" onclick="reconvertPW()">Create Account</button>
   
            
            </form>
            
            </div>
        
        <footer>
            Website made by Cayfabe Studios &copy;
        </footer>
        </body>
    
</body>
</html>