<?php
// upload_image.php

$targetDir = "uploads/";  // Directory where the image will be stored
$targetFile = $targetDir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Check if image file is a real image
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo json_encode(['success' => false, 'message' => 'File is not an image.']);
        exit;
    }
}

// Check if file already exists
if (file_exists($targetFile)) {
    echo json_encode(['success' => false, 'message' => 'Sorry, file already exists.']);
    exit;
}

// Check file size (optional)
if ($_FILES["file"]["size"] > 5000000) {  // Limit size to 5MB
    echo json_encode(['success' => false, 'message' => 'Sorry, your file is too large.']);
    exit;
}

// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    echo json_encode(['success' => false, 'message' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.']);
    exit;
}

// Try to upload the file
if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
    echo json_encode(['success' => true, 'filePath' => $targetFile]);
} else {
    echo json_encode(['success' => false, 'message' => 'Sorry, there was an error uploading your file.']);
}
?>
