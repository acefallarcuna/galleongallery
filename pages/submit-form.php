<?php
session_start(); // Start the session to track logged-in users

// Add a 3-second delay before proceeding with the page load
sleep(3);

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // If not logged in, redirect to the login page
    header("Location: ../pages/account/sign-in.html");
    exit();
}

// Get the user's email from the session
$user_email = $_SESSION['email']; // Assuming you store the email in the session

// Include your database connection
include($_SERVER['DOCUMENT_ROOT'] . '/lpu-eportfolio/configs/database.php');

// Check if the email exists in the database and retrieve fname, lname
$query = "SELECT fname, lname FROM accounts1 WHERE email = ?";
$stmt = mysqli_prepare($connection, $query);
if ($stmt) {
    // Bind the email parameter to the query
    mysqli_stmt_bind_param($stmt, "s", $user_email);
    // Execute the statement
    mysqli_stmt_execute($stmt);
    // Get the result
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) === 0) {
        // Redirect to sign-in page if user is not found
        header("Location: ../pages/account/sign-in.html");
        exit();
    }

    // Fetch the user's first and last name
    $user = mysqli_fetch_assoc($result);
    $first_name = $user['fname'];
    $last_name = $user['lname'];

    // Close the prepared statement
    mysqli_stmt_close($stmt);
} else {
    die("Database query failed: " . mysqli_error($connection));
}

// Proceed with form submission if method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $projectTitle = mysqli_real_escape_string($connection, $_POST['projectTitle']);
    
    // Handling project tags
    $projectTags = isset($_POST['projectTags']) ? $_POST['projectTags'] : [];
    $projectTags1 = isset($projectTags[0]) ? mysqli_real_escape_string($connection, $projectTags[0]) : null;
    $projectTags2 = isset($projectTags[1]) ? mysqli_real_escape_string($connection, $projectTags[1]) : null;
    $projectTags3 = isset($projectTags[2]) ? mysqli_real_escape_string($connection, $projectTags[2]) : null;
    
    // Handling tools used
    $toolsUsed = isset($_POST['toolsUsed']) ? $_POST['toolsUsed'] : [];
    $toolsUsed1 = isset($toolsUsed[0]) ? mysqli_real_escape_string($connection, $toolsUsed[0]) : null;
    $toolsUsed2 = isset($toolsUsed[1]) ? mysqli_real_escape_string($connection, $toolsUsed[1]) : null;
    $toolsUsed3 = isset($toolsUsed[2]) ? mysqli_real_escape_string($connection, $toolsUsed[2]) : null;

    // Handling project categories - concatenate them as a single string
    $projectCategory = isset($_POST['projectCategory']) ? $_POST['projectCategory'] : [];
    $projectCategoriesString = implode(", ", array_map(function($category) use ($connection) {
        return mysqli_real_escape_string($connection, $category);
    }, $projectCategory));

    // Check if adult content flag is set
    $adultContent = isset($_POST['adultContent']) ? 1 : 0;

    // Get caption and embed data
    $imageCaption = isset($_POST['imageCaption']) ? mysqli_real_escape_string($connection, $_POST['imageCaption']) : null;
    $videoCaption = isset($_POST['videoCaption']) ? mysqli_real_escape_string($connection, $_POST['videoCaption']) : null;
    $embedCode = isset($_POST['embedCode']) ? mysqli_real_escape_string($connection, $_POST['embedCode']) : ''; // Set empty string if not provided
    $textDescription = isset($_POST['textDescription']) ? mysqli_real_escape_string($connection, $_POST['textDescription']) : '';

    // Now use the first and last name as the author
    $author = $first_name . ' ' . $last_name; // Concatenate first and last name

    // Ensure the 'uploads' directory exists and is writable
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/lpu-eportfolio/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create the directory if it doesn't exist
    }

    // Debugging: Check the $_FILES array to inspect the file uploads
    echo "<pre>";
    var_dump($_FILES); // Debug the file array
    echo "</pre>";

    // Handle image upload
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageFile = $_FILES['image'];
        $imagePath = 'uploads/' . basename($imageFile['name']);
        $targetFilePath = $uploadDir . basename($imageFile['name']);
        
        // Log any upload errors for debugging
        if ($imageFile['error'] !== UPLOAD_ERR_OK) {
            echo "Image upload error: " . $imageFile['error'];
            exit();
        }

        // Move the uploaded file to the 'uploads' directory
        if (!move_uploaded_file($imageFile['tmp_name'], $targetFilePath)) {
            echo "Error uploading image file.";
            exit();
        } else {
            echo "Image uploaded successfully. Path: " . $imagePath . "<br>";
        }
    } else {
        echo "No image file or error during upload.<br>";
    }

    // Handle video upload
    $videoPath = '';
    if (isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
        $videoFile = $_FILES['video'];
        $videoPath = 'uploads/' . basename($videoFile['name']);
        $targetFilePath = $uploadDir . basename($videoFile['name']);
        
        // Log any upload errors for debugging
        if ($videoFile['error'] !== UPLOAD_ERR_OK) {
            echo "Video upload error: " . $videoFile['error'];
            exit();
        }

        // Move the uploaded file to the 'uploads' directory
        if (!move_uploaded_file($videoFile['tmp_name'], $targetFilePath)) {
            echo "Error uploading video file.";
            exit();
        } else {
            echo "Video uploaded successfully. Path: " . $videoPath . "<br>";
        }
    } else {
        echo "No video file or error during upload.<br>";
    }

    // SQL query with 14 columns (ensure it matches the number of bind parameters)
    $sql = "INSERT INTO works 
                (author, projectTitle, projectTags1, projectTags2, projectTags3, 
                toolsUsed1, toolsUsed2, toolsUsed3, projectCategories, 
                adultContent, imagePath, videoPath, embedCode, textDescription) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    if ($stmt = mysqli_prepare($connection, $sql)) {
        // Bind the parameters (make sure the type definition string matches the number of variables)
        mysqli_stmt_bind_param(
            $stmt, 
            "ssssssssssssss",  // 14 's' to bind 14 string variables (removing the extra 's' for textDescription)
            $author, 
            $projectTitle, 
            $projectTags1, 
            $projectTags2, 
            $projectTags3, 
            $toolsUsed1, 
            $toolsUsed2, 
            $toolsUsed3, 
            $projectCategoriesString, 
            $adultContent, 
            $imagePath, 
            $videoPath, 
            $embedCode, 
            $textDescription
        );

        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php"); // Redirect to a success page after submission
            exit();
        } else {
            echo "Error: " . mysqli_error($connection); // Error executing query
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing the statement: " . mysqli_error($connection); // Error preparing the statement
    }
}
?>
