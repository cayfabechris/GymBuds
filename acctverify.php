<?php

//Config file, separate for security reasons
include 'resources/config.php';

//Message that updates when an error occurs i.e Username taken, email taken
$msg = "";
$msgClass = "";
try {
    //Submit button has been clicked
    if (filter_has_var(INPUT_POST, 'submit')) {

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

        // All account form inputs

        $email = $connection->real_escape_string($_POST['email']);
        $token = $connection->real_escape_string($_POST['token']);


        //Check if any input field is empty
        if (!empty($email) && !empty($token)) {

            //Is the input email in a proper format i.e abc123@x.com
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {

                $msg = 'Please use a valid email';
            } else {
            }
        }
        //Empty input field found
        else {
            $msg = 'Please fill in all fields';
            $msgClass = 'alert-danger';
        }
        //Check if the given email exists
        $sql = $connection->query("SELECT email FROM user WHERE email = '$email'");

        if ($sql->num_rows == 0) {
            //Email does not exist
            $msg = "Email does not exist";
        }

        //Email exists
        else {
            //Check if account has been verified
            $sql = $connection->query("SELECT * FROM user WHERE email = '$email' AND isEmailConfirmed = 1");
            if ($sql->num_rows > 0 && strlen($email) > 0) {
                //Email already verified
                $msg = "Account already verified";
            }

            //Account not verified
            else {
                //Check to see if verify token is valid
                $sql = $connection->query("SELECT * FROM user WHERE email = '$email' AND token = '$token'");

                if ($sql->num_rows == 0 && strlen($email) > 0) {
                    //Token not valid
                    $msg = "Token does not match the one provided in your email, please check your email and try again";
                }

                //Token is valid
                else {

                    //Update to set user account as verified
                    $sql = "UPDATE user SET isEmailConfirmed = 1 WHERE email = '$email' AND token = '$token'";

                    if ($connection->query($sql) === TRUE) {
                        echo "Account verified successfully";

                        //Start a session to send data to the verified page
                        session_start();

                        //Pass email to display personalized message
                        $_SESSION['email'] = htmlentities($_POST['email']);

                        //Redirects the page to the verified page
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
catch (PDOException $e) {
    echo "Error:" . $e->getMessage();
    $connection->close();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GymBuds: Verify Account</title>
    <link rel="stylesheet" href="styles/fonts.css">
    <link rel="stylesheet" href="styles/style-create_account.css">
</head>

<body>
    <header>
        <h1>
            <a href="login.php">GymBuds</a>
        </h1>

        <?php if ($msg != '') : ?>
            <div id="create_account-wrapper">
                <h3> <?php echo $msg; ?> </h3>
            </div>
        <?php endif; ?>


        <div id="create_account-wrapper">
            <h2>
                Verify Your Account
            </h2>
            <form name="verify-account" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="create-account-labels">

                    <input type="email" ondblClick="this.select();" name="email" id="email" placeholder="Email" minlength="5" maxlength="50" size="25" required>
                    <br>
                    <br>
                    <input type="text" ondblClick="this.select();" name="token" id="token" placeholder="Token (In your signup email)" size="25" maxlength="10" required>


                </div>
                <br>
                <button type="submit" name="submit" value="submit" onclick="reconvertPW()">Verify Account</button>


            </form>

        </div>

        <footer>
            Website made by Christian Rodriguez ((<a href="https://github.com/cjrcodes>cjrcodes on GitHub</a>))
        </footer>
</body>

</body>

</html>