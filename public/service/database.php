<?php

$hostname  = getenv('MYSQLHOST')     ?: 'localhost';
$username  = getenv('MYSQLUSER')     ?: 'root';
$password  = getenv('MYSQLPASSWORD') ?: '';
$database  = getenv('MYSQLDB')       ?: 'sanitaryonthego';
$port      = getenv('MYSQLPORT')     ?: 3306;

$db = mysqli_connect($hostname, $username, $password, $database, $port);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

?>