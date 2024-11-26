<?php
// Corrected include path using DOCUMENT_ROOT
include($_SERVER['DOCUMENT_ROOT'] . '/lpu-eportfolio/configs/database.php');

// Check if the connection is successful
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Collect form data
$firstName = mysqli_real_escape_string($connection, $_POST['firstName']);
$lastName = mysqli_real_escape_string($connection, $_POST['lastName']);
$email = mysqli_real_escape_string($connection, $_POST['email']);
$password = mysqli_real_escape_string($connection, $_POST['password']);

// Insert query
$query = "INSERT INTO accounts1 (fname, lname, email, password) VALUES ('$firstName', '$lastName', '$email', '$password')";

if (mysqli_query($connection, $query)) {
    header("Location: /lpu-eportfolio/pages/account/sign-in.html");
    exit(); // Make sure no further code is executed
} else {
    echo "Error: " . mysqli_error($connection);
}

mysqli_close($connection);  // Close the connection
?>
