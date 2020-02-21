<?php
include "inc/login.inc.php";
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width; initial-scale=1.0;">
    <meta charset="UTF-8">
    <script src="scripts/script.js"></script>
    <script src="scripts/script-login.js"></script>
    <link rel="stylesheet" href="styles/fonts.css">
    <link rel="stylesheet" href="styles/style.css">
    <title>
        GymBuds
    </title>
</head>

<body>
    <div id="main-wrapper">
        <header class="header-wrapper">
            <div id="header-title">
                <h1>
                    <a href="login.php">GymBuds</a>
                </h1>
            </div>
            <div id="header-content">
                <img src="images/dumbbell.png" height="128" width="128" alt="No dumbbell!">
                <h3>Gym together, Gain together!</h3>
            </div>
        </header>

        <?php if ($msg != '') : ?>
            <div class="warning-wrapper">
                <style>

                </style>
                <h3 id="warning-msg"> <?php echo $msg; ?> </h3>
            </div>
        <?php endif; ?>

        <div class="content-wrapper">
            <div class="content-border">
                <form name="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

                    <div id="content-title">
                        <h3>
                            Log In
                        </h3>

                        <!-- <br id="content-title-break"> -->
                    </div>

                    <div class="input-wrapper">

                        <!-- <br id="content-title-break"> -->

                        <input type="email" autocomplete="email" ondblClick="this.select();" name=email id="email" minlength="5" maxlength="50" required placeholder="Email">

                        <br>
                        <br>

                        <input type="password" autocomplete="current-password" ondblClick="this.select();" name=password id="password" minlength="5" maxlength="20" required placeholder="Password">

                    </div>
                    <div class="checkbox-wrapper">
                        <input type="checkbox">Remember me
                    </div>
                    <div class="button-wrapper">
                        <button name="login-submit" value="submit" type="submit" style="margin-bottom: 8px;">Log In</button>
                    </div>

                    <div class="links-wrapper">
                        <a href="register.php">Sign Up</a>
                        <br>
                        <a href="forgotpassword.php">Forgot password</a>
                    </div>
                </form>
            </div>
        </div>

        <footer class="footer">
            Website made by Christian Rodriguez (<a href="https://github.com/cjrcodes">cjrcodes on GitHub</a>)
        </footer>

</body>

</html>