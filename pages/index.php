<?php
session_start(); // Start the session to track logged-in users

// Add a 3-second delay before proceeding with the page load
sleep(3);

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // If not logged in, redirect to the login page
    header("Location: ../pages/account/sign-in.html");  // Adjust the path if necessary
    exit();
}

// Get the user's email from the session
$user_email = $_SESSION['email']; // Assuming you store the email in the session

// Include your database connection (adjust path if necessary)
include($_SERVER['DOCUMENT_ROOT'] . '/lpu-eportfolio/configs/database.php');

// Check if the email exists in the database
$query = "SELECT * FROM accounts1 WHERE email = ?";
$stmt = mysqli_prepare($connection, $query);
if ($stmt) {
    // Bind the email parameter to the query
    mysqli_stmt_bind_param($stmt, "s", $user_email);
    
    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);
    
    // Check if the user exists in the database
    if (mysqli_num_rows($result) === 0) {
        // If no matching email found, redirect to the sign-in page
        header("Location: ../pages/account/sign-in.html");  // Redirect to sign-in page if user is not found
        exit();
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
} else {
    // If there was an issue with the query, handle the error (optional)
    die("Database query failed: " . mysqli_error($connection));
}

// Proceed with the page load if the email exists
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="index.css">
    <title>Galleon Gallery</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <!-- Use inline SVG properly formatted -->
            <a class="navbar-brand" href="../pages/index.php">
                <!-- Use proper SVG structure -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="3vw" height="auto">
                    <path d="M 12 6.59375 L 11.28125 7.28125 L 3.28125 15.28125 L 2.59375 16 L 3.28125 16.71875 L 11.28125 24.71875 L 12 25.40625 L 12.71875 24.71875 L 16.71875 20.71875 L 17.40625 20 L 16.71875 19.28125 L 11.71875 14.28125 L 10.28125 15.71875 L 14.5625 20 L 12 22.5625 L 5.4375 16 L 12 9.4375 L 13.28125 10.71875 L 14.71875 9.28125 L 12.71875 7.28125 Z M 20 6.59375 L 19.28125 7.28125 L 15.28125 11.28125 L 14.59375 12 L 15.28125 12.71875 L 20.28125 17.71875 L 21.71875 16.28125 L 17.4375 12 L 20 9.4375 L 26.5625 16 L 20 22.5625 L 18.71875 21.28125 L 17.28125 22.71875 L 19.28125 24.71875 L 20 25.40625 L 20.71875 24.71875 L 28.71875 16.71875 L 29.40625 16 L 28.71875 15.28125 L 20.71875 7.28125 Z"></path>
                </svg>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <!-- Navbar items here -->
                </ul>
            </div>

            <!-- Search and other features -->
            <div class="dropdown search-bar">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Filter
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Images</a></li>
                    <li><a class="dropdown-item" href="#">Videos</a></li>
                </ul>
                <input class="form-control" type="search" placeholder="Search..." aria-label="Search">
            </div>

            <!-- Upload Work -->
            <div class="upload-cont">
                <button type="button" class="btn btn-primary btn-upload" onclick="redirectToUpload()">Share Your Work</button>
            </div>

            <!-- Navbar Items -->
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="#"><img class="img-mail" src="../res/mail.png" alt=""></a></li>
                <li class="nav-item"><a class="nav-link" href="#"><img class="img-bell" src="../res/bell.png" alt=""></a></li>
                <li class="nav-item"><a class="nav-link" href="#"><img class="img-user" src="../res/user.png" alt=""></a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-sm">
        <div class="card-container">
            <div class="card" style="width: 25vw;">
                <a href="">
                    <img src="../res/test.jpg" class="card-img-top" alt="...">
                </a>
                <div class="card-body">
                    <div class="card-author">
                        <a href="" class="card-author-icon"><img class="card-author-img" src="../res/user.png" alt=""></a>
                        <a href="" class="card-author-name">Luis Musni</a>
                    </div>
                    <div class="card-info">
                        <a href=""><img class="card-info-views" src="../res/views.png" alt=""> 5.3K</a>
                        <a href=""><img class="card-info-likes" src="../res/heart.png" alt=""> 10K</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pass the user email to JavaScript -->
    <script>
        // Echo the logged-in user's email in the console
        const userEmail = "<?php echo $user_email; ?>";
        console.log("Logged in user:", userEmail);

        function redirectToUpload() {
            // Redirect to the 'upload.php' page
            window.location.href = 'upload.php';
        }
    </script>

    <script src="../pages/index.js"></script>
    <script src="../pages/home/visitor/components/footer.js"></script>
    <script src="../pages/home/visitor/components/navbar.js"></script>
    <script src="../pages/home/visitor/components/sidenav.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>