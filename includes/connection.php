<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'template_DB';

$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli->connect_error) {
    die('Database connection failed: ' . $mysqli->connect_error);
}
?>