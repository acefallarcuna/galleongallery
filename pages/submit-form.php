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
    $textDescription = isset($_POST['textDescription']) ? mysqli_real_escape_string($connection, $_POST['textDescription']) : '';
    $embedCode = isset($_POST['embedCode']) ? mysqli_real_escape_string($connection, $_POST['embedCode']) : '';
    
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

    // Get path and embed data
    $imagePath = isset($_POST['imagePath']) ? mysqli_real_escape_string($connection, $_POST['imagePath']) : '';
    $videoPath = isset($_POST['videoPath']) ? mysqli_real_escape_string($connection, $_POST['videoPath']) : '';
    $embedCode = isset($_POST['embedCode']) ? mysqli_real_escape_string($connection, $_POST['embedCode']) : '';
    $textDescription = isset($_POST['textDescription']) ? mysqli_real_escape_string($connection, $_POST['textDescription']) : '';

    // Now use the first and last name as the author
    $author = $first_name . ' ' . $last_name; // Concatenate first and last name

    // Ensure the 'uploads' directory exists and is writable
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/lpu-eportfolio/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create the directory if it doesn't exist
    }

    // Create specific subdirectories for image uploads
    $imageUploadDir = $uploadDir . 'images/';
    if (!is_dir($imageUploadDir)) {
        mkdir($imageUploadDir, 0777, true);
    }

    // Create specific subdirectories for video uploads
    $videoUploadDir = $uploadDir . 'videos/';
    if (!is_dir($videoUploadDir)) {
        mkdir($videoUploadDir, 0777, true);
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageFile = $_FILES['image'];
        $imageTmpName = $imageFile['tmp_name'];
        
        // Generate the new image filename using the author's name, project title, and current date
        $imageExtension = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
        $imageName = $first_name . '_' . $last_name . '_' . $projectTitle . '_' . date('Y-m-d_H-i-s') . '.' . $imageExtension;
        $imageName = preg_replace("/[^a-zA-Z0-9\-_\.]/", "", $imageName);  // Sanitize the filename
        $imageTargetPath = $imageUploadDir . $imageName; // Correct the target path (directory + sanitized name)

        // Check file size
        if ($imageFile['size'] > 10 * 1024 * 1024) {
            echo "The image file is too large.<br>";
        }

        // Check if the uploaded file is a valid image
        $allowedImageTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageExtension, $allowedImageTypes)) {
            // Move the uploaded image to the target directory
            if (move_uploaded_file($imageTmpName, $imageTargetPath)) {
                echo "Image uploaded successfully: $imageName<br>";
                // Store image path in the database or use it
                $imagePath = '/uploads/images/' . $imageName;  // Save the path for database use
            } else {
                echo "Error uploading the image.<br>";
            }
        } else {
            echo "Only image files (JPG, JPEG, PNG, GIF) are allowed for the image.<br>";
        }
    }

    // Handle video upload
    if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $videoFile = $_FILES['video'];
        $videoTmpName = $videoFile['tmp_name'];

        // Generate the new video filename using the author's name, project title, and current date
        $videoExtension = strtolower(pathinfo($videoFile['name'], PATHINFO_EXTENSION));
        $videoName = $first_name . '_' . $last_name . '_' . $projectTitle . '_' . date('Y-m-d_H-i-s') . '.' . $videoExtension;
        $videoName = preg_replace("/[^a-zA-Z0-9\-_\.]/", "", $videoName);  // Sanitize the filename
        $videoTargetPath = $videoUploadDir . $videoName; // Correct the target path (directory + sanitized name)

        // Check file size
        if ($videoFile['size'] > 10 * 1024 * 1024) {
            echo "The video file is too large.<br>";
        }

        // Check if the uploaded file is a valid video
        $allowedVideoTypes = ['mp4', 'avi', 'mov', 'wmv'];
        if (in_array($videoExtension, $allowedVideoTypes)) {
            // Move the uploaded video to the target directory
            if (move_uploaded_file($videoTmpName, $videoTargetPath)) {
                echo "Video uploaded successfully: $videoName<br>";
                // Store video path in the database
                $videoPath = '/uploads/videos/' . $videoName;  // Save the path for database use
            } else {
                echo "Error uploading the video.<br>";
            }
        } else {
            echo "Only video files (MP4, AVI, MOV, WMV) are allowed for the video.<br>";
        }
    }

    // SQL query with email included (ensure it matches the number of columns in your table)
    $sql = "INSERT INTO works 
                (user_email, author, projectTitle, projectTags1, projectTags2, projectTags3, 
                toolsUsed1, toolsUsed2, toolsUsed3, projectCategories, 
                adultContent, imagePath, videoPath, embedCode, textDescription) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    if ($stmt = mysqli_prepare($connection, $sql)) {
        // Bind the parameters (make sure the type definition string matches the number of variables)
        mysqli_stmt_bind_param(
            $stmt, 
            "sssssssssssssss",  // 15 's' to bind 15 string variables
            $user_email,        // email
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
