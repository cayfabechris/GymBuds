<?php

//Config file, separate for security reasons
include 'config.php';

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

//Check if any input field is empty
if(!empty($email)){

    //Is the input email in a proper format i.e abc123@x.com
    if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
        $msg = 'Please use a valid email';
    }

    //Email in valid format
    else{

    //Check email existence
    $sql = $connection->query("SELECT * FROM user WHERE email = '$email'");
    
    //No email found
    if($sql->num_rows == 0 && strlen($email) > 0){
        $msg ="Email does not exist";
    }

    //Email found
    else if($sql->num_rows == 1){

        $row = $sql->fetch_assoc();

        //Generate new forgotten password token
        $fpToken = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789!/()";
        $fpToken = str_shuffle($fpToken);
        $fpToken = substr($fpToken, 0, 10);

        //Update forgotten password token
        $sqlUpdate = $connection->query("UPDATE user SET FPToken = '$fpToken' WHERE email = '$email'");

        //Email sent using Send Mail
        $msg = "New password email sent, please check your email";

        $message = 

        "
        <html>
        <title>GymBuds: Forgot Password</title>
        <body>
        <h1>GymBuds</h1>
        <br>
        Use your token " . $fpToken . " on the <a href = http://localhost/gymBuds/GymBuds/passwordverify.php>new password</a> page to create a new password for your GymBuds account.
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
         "Hello ". $row['first_name'] . ", set your new GymBuds Password",
         
         //Body
         $message,
         
         //Headers
            $headers);

        $connection->close();

    }

    else{
                //SQL connection error
            $msg = "Error";
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
            
        }
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
            <h2>
                Forgot Password
            </h2>
            <form name="forgotPassword" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="login-labels">
                    <input type="email" ondblClick="this.select();" name=email id="email" placeholder="Email" size="25"
                        required>
                </div>
                <br>
                <button name="submit" value="submit" type="submit">Send New Password Email</button>
                <br><br>

            </form>

        </div>

        <footer>
            Website made by Cayfabe Studios &copy;
        </footer>
</body>

</html>