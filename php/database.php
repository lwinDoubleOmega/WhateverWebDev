<?php
/* Host name of the MySQL server */
$host = 'localhost';
/* MySQL account username */
$user = 'root';
/* MySQL account password */
$passwd = '';
/* The schema you want to use */
$db = 'EdamamePerfume';
/* Connection with MySQLi, procedural-style */
// Create a connection to the database
$conn = new mysqli($host, $user, $passwd, $db);
// Check the connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
?>