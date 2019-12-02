<?php

include 'config.php';


$msg = "";
$msgClass = "";
try{
if(filter_has_var(INPUT_POST, 'submit')){

    $DB_HOST = $config['DB_HOST'];
    $DB_USER = $config['DB_USERNAME'];
    $DB_PASSWORD = $config['DB_PASSWORD'];
    $DB_NAME = $config['DB_DATABASE'];

    
    $connection = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

    if ($connection->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

$firstname = $connection->real_escape_string($_POST['first-name']);
$lastname = $connection->real_escape_string($_POST['last-name']);
$username = $connection->real_escape_string($_POST['username']);
$email = $connection->real_escape_string($_POST['email']);
$password = $connection->real_escape_string($_POST['password']);
$cpassword = $connection->real_escape_string($_POST['Cpassword']);

if(!empty($firstname) && !empty($lastname) && !empty($username) && !empty($email) && !empty($password) && !empty($cpassword)){

    if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){

        $msg = 'Please use a valid email';
    }

    else{

    }
}

else{
    $msg = 'Please fill in all fields';
    $msgClass = 'alert-danger';
}

if($password != $cpassword){
    $msg = "Passwords do not match. Please re-enter passwords to ensure correctness.";
}

else{
    $sql = $connection->query("SELECT user_id FROM user WHERE username = '$username'");
    if($sql->num_rows > 0){
        $msg ="Username taken, please use another username.";
    }

    else{

        $sql = $connection->query("SELECT user_id FROM user WHERE email = '$email'");
        if($sql->num_rows > 0 && strlen($email) > 0){
            $msg ="Email taken, please use another email.";
        }
    
        else{
            $token = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789!/()";
            $token = str_shuffle($token);
            $token = substr($token, 0, 10);

            $hashedPassword = sodium_crypto_pwhash_str($password, SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE, SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE);
            
            echo "Create account reached!";

            $sql = "INSERT INTO user(first_name, last_name, username, email, password, isEmailConfirmed, Token)
            VALUES ('$firstname', '$lastname', '$username', '$email', '$hashedPassword', '0', '$token');";
           if ($connection->query($sql) === TRUE) {
            echo "New record created successfully";

             //Sent to
             mail($email, 

             //Subject/title
             "Hello, Verify Your GymBuds Account!",
             
             //Body
             "Hi ". $firstname . 
             "<br><br>Please activate your GxmBxds",
             
             //Sent from
             "From: ". $config['HOST_EMAIL']);

             session_start();

             $_SESSION['name'] = htmlentities($_POST['first-name']);
             $_SESSION['email'] = htmlentities($_POST['email']);

             header('Location: acctsuccess.php');
 
            } else {
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
            
            $connection->close();

           

        }
    
    }

}

  
}
}

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
    <title>GymBuds: Register</title>
    <script src="scripts/script-login.js"></script>
    <link rel="stylesheet" href="styles/style-create_account.css">
</head>
<body>
<header>
        <h1>
                <a href="index.html">GxmBxds</a>
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