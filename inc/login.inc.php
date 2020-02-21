<?php

//Config file, separate for security reasons
include 'resources/config.php';
session_start();

if (isset($_SESSION['email'])) {
    header('Location: dashboard.php');
}

//Message that updates when an error occurs i.e Username taken, email taken
$msg = "";
try {
    //Submit button has been clicked
    if (filter_has_var(INPUT_POST, 'login-submit')) {

        // All form inputs
        $email = $connection->real_escape_string($_POST['email']);
        $password = $connection->real_escape_string($_POST['password']);

        //Check if any input field is empty
        if (!empty($email) && !empty($password)) {

            //Is the input email in a proper format i.e abc123@x.com
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $msg = 'Please use a valid email';
                return;
            }

            //Valid email format
            else {

                //Check if given email exists
                $sql = "SELECT password, isEmailConfirmed FROM user WHERE email = ?";
                $stmt = mysqli_stmt_init($connection);


                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    //SQL and Statement combo failed
                    echo "SQL statement failed";
                    return;
                } else {
                    //Pass user email input to the SQL statement
                    mysqli_stmt_bind_param($stmt, "s", $email);

                    //Execute the query
                    mysqli_stmt_execute($stmt);

                    //Store the result of the execution
                    mysqli_stmt_store_result($stmt);

                    //Gets the hashed password from the database and stores in hashed password variable
                    mysqli_stmt_bind_result($stmt, $hashedPassword, $isEmailConfirmed);

                    //Gets the result of the statement
                    mysqli_stmt_fetch($stmt);

                    //Stores the number of rows to determine if an email was found (0 means no, 1 means yes)
                    $resultCheck = mysqli_stmt_num_rows($stmt);
                }

                if ($resultCheck === 0) {
                    //Email not found
                    $msg = "Email/Password incorrect";
                    mysqli_stmt_close($stmt);
                    return;
                } else {

                    // $result = mysqli_stmt_get_result($stmt);

                    /*if (!$result) {
                        printf("Error: %s\n", mysqli_error($connection));
                        return;
                    }*/

                    //Comparing hashed password with the given user password
                    if (Sodium_crypto_pwhash_scryptsalsa208sha256_str_verify($hashedPassword, $password)) {
                        if ($isEmailConfirmed === 0) {
                            $msg = "Please verify your email in your inbox";
                            mysqli_stmt_close($stmt);
                            Sodium_memzero($password);
                            Sodium_memzero($hashedPassword);
                            return;
                        }

                        //Passwords match, wipe them from memory

                        Sodium_memzero($password);
                        Sodium_memzero($hashedPassword);

                        session_start();

                        $_SESSION['email'] = htmlentities($_POST['email']);

                        //Redirects the page to the dashboard page
                        header('Location: dashboard.php');
                    } else {
                        $msg = "Email/Password incorrect";
                    }
                }






                /*
                //Email exists
                else {

                    //Is the user account verified?
                    $sql = "SELECT isEmailConfirmed FROM user WHERE email = '$email'";
                    $result = $connection->query($sql);
                    $row = $result->fetch_assoc();
                    $confirmed = $row["isEmailConfirmed"];

                    //User account has not been verified
                    if ($confirmed == 0) {
                        $msg = "Please verify your email";
                    }

                    //User account verified, check password input
                    else if (Sodium_crypto_pwhash_scryptsalsa208sha256_str_verify($hashedPassword, $password)) {
                        // recommended: wipe the plaintext password from memory
                        Sodium_memzero($password);
                        Sodium_memzero($hashedPassword);

                        //Login works
                        echo "Login successful!";
                        $msg = "Login successful!";

                        //Start a session to send data to the dashboard page
                        session_start();

                        $_SESSION['name'] = htmlentities($_POST['first-name']);
                        $_SESSION['email'] = htmlentities($_POST['email']);

                        //Redirects the page to the dashboard page
                        header('Location: dashboard.php');
                    } else {
                        Sodium_memzero($password);
                        Sodium_memzero($hashedPassword);

                        //SQL connection error
                        $msg = "Password is incorrect";
                        //echo "Error: " . $sql . "<br>" . $connection->error;
                    }

                    $connection->close();
            }*/
            }
        }

        //Empty input field found
        else {
            $msg = 'Please fill in all fields';
        }
    }
}
//Error has occurred
catch (PDOException $e) {
    $msg = "Error:" . $e->getMessage();
    echo "Error:" . $e->getMessage();
    $connection->close();
}
