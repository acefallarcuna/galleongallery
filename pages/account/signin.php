<?php
session_start(); // Start the session to track logged-in users

// Enable error reporting for debugging (remove this in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set session timeout to 30 minutes (1800 seconds)
$session_timeout = 1800; // 30 minutes in seconds

// Check if the user is inactive for too long (session timeout)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $session_timeout) {
    // If session is expired, destroy session and redirect to login page
    session_unset();  // Remove all session variables
    session_destroy(); // Destroy the session
    header("Location: signin.php"); // Redirect to login page
    exit();
}

// Update the last activity time
$_SESSION['last_activity'] = time(); // Set last activity time to current time

include($_SERVER['DOCUMENT_ROOT'] . '/lpu-eportfolio/configs/database.php');

// Check if the form is submitted
if (isset($_POST['signin'])) {
    // Sanitize user inputs
    $email = legal_input($_POST['email']);
    $password = legal_input($_POST['password']);

    // Use prepared statements to prevent SQL injection
    $query = "SELECT * FROM accounts1 WHERE email = ?";
    if ($stmt = mysqli_prepare($connection, $query)) {
        // Bind the email parameter to the SQL query
        mysqli_stmt_bind_param($stmt, "s", $email);

        // Execute the statement
        if (!mysqli_stmt_execute($stmt)) {
            // If query fails, show the error
            die("Error executing query: " . mysqli_error($connection));
        }

        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        // If a user is found with the provided email
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);  // Fetch user data

            // Verify the password (assuming it's stored as plain text)
            if ($password == $user['password']) {
                // Password is correct, log the user in
                
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);

                // Set session variables
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];  // Store user ID or any other necessary info
                $_SESSION['email'] = $user['email']; // Store email or any other info

                // Redirect to the home page (use index.php for dynamic content, not .html)
                header('Location: ../index.php');
                exit();
            } else {
                // Password is incorrect, redirect to signin with error message
                header('Location: ../account/sign-in-error.html');
                exit();
            }
        } else {
            // No user found with this email, redirect to signin with error message
            header('Location: ../account/sign-in-error.html');
            exit();
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        // If there was an issue with the prepared statement, show the error
        die("Error preparing the query: " . mysqli_error($connection));
    }
}

// Sanitize inputs to prevent XSS and other attacks
function legal_input($value) {
    $value = trim($value);          // Remove extra spaces
    $value = stripslashes($value);  // Remove backslashes
    $value = htmlspecialchars($value);  // Convert special characters to HTML entities
    return $value;
}
?>
