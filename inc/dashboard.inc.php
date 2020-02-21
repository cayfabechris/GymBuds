<?php
include 'resources/config.php';
session_start();

$msg = "";
if (!isset($_SESSION['email'])) {
    $msg = "You are not logged in. Please log in from the home page.";
    header("refresh:5; url=login.php");

    //Get rid of all user credentials
    session_destroy();
}

$msg = "You are logged in. Your email is " . $_SESSION['email'];
