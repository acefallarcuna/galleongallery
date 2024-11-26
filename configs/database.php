<?php
$hostname = "localhost:3306";
$username = "root";
$password = "";
$databasename = "eportfolio";
// Create connection
$connection = mysqli_connect($hostname, $username, $password, $databasename);

// Check connection
if (!$connection) {
 die("Unable to Connect database: " . mysqli_connect_error());
}
?>