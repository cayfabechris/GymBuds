<?php
$config = array(
    'DB_HOST' => 'localhost',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => 'No.hackPHPMYADMIN97',
    'DB_DATABASE' => 'gymbuds',
    'HOST_EMAIL' => 'cjrcodes@gmail.com'
);

//Database credentials
$DB_HOST = $config['DB_HOST'];
$DB_USER = $config['DB_USERNAME'];
$DB_PASSWORD = $config['DB_PASSWORD'];
$DB_NAME = $config['DB_DATABASE'];

//Connection to MySQL database
$connection = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

//Connection failure
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
