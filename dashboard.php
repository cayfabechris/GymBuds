<?php
include "inc/dashboard.inc.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="scripts/script.js"></script>
    <link rel="stylesheet" href="styles/fonts.css">
    <link rel="stylesheet" href="styles/style.css">
    <title>GymBuds: Dashboard</title>
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

            </div>
        </header>

        <?php if ($msg != '') : ?>
            <div class="warning-wrapper">

                <h3 id="warning-msg"> <?php echo $msg; ?> </h3>
            </div>
        <?php endif; ?>

        <div class="content-wrapper">
            <button name="logout-submit" class="button-wrapper" value="submit" type="submit" onclick=<?php if (filter_has_var(INPUT_POST, 'logout-submit')) {
                                                                            session_destroy();
                                                                            header("Location: login.php");
                                                                        } ?>>
                Log Out
            </button>
        </div>

        <footer class="footer">
            Website made by Christian Rodriguez (<a href="https://github.com/cjrcodes">cjrcodes on GitHub</a>)
        </footer>

</body>

</html>