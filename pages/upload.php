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
    <link rel="stylesheet" type="text/css" href="upload.css">
    <title>Galleon Gallery</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="../pages/index.php">
                <!-- Replace this text with the SVG code -->
                <svg fill="#000000" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path d="M 12 6.59375 L 11.28125 7.28125 L 3.28125 15.28125 L 2.59375 16 L 3.28125 16.71875 L 11.28125 24.71875 L 12 25.40625 L 12.71875 24.71875 L 16.71875 20.71875 L 17.40625 20 L 16.71875 19.28125 L 11.71875 14.28125 L 10.28125 15.71875 L 14.5625 20 L 12 22.5625 L 5.4375 16 L 12 9.4375 L 13.28125 10.71875 L 14.71875 9.28125 L 12.71875 7.28125 Z M 20 6.59375 L 19.28125 7.28125 L 15.28125 11.28125 L 14.59375 12 L 15.28125 12.71875 L 20.28125 17.71875 L 21.71875 16.28125 L 17.4375 12 L 20 9.4375 L 26.5625 16 L 20 22.5625 L 18.71875 21.28125 L 17.28125 22.71875 L 19.28125 24.71875 L 20 25.40625 L 20.71875 24.71875 L 28.71875 16.71875 L 29.40625 16 L 28.71875 15.28125 L 20.71875 7.28125 Z"></path>
                    </g>
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
    <div class="container">
        <style>
            .form-label {
                font-size: 12px;
            }
        </style>
        <div class="row justify-content-center"> <!-- Centers the row in the container --> 
            <div class="col-lg-10 border" id="content-container"> <!-- border-dark -->
                <p class="empty-text">Create a post</p>
            </div>

            <div class="col-lg-2 border"> <!-- Adjusted column size to better center --> <!-- border-dark -->
            <label class="form-label">Add Content</label>
                <style>
                    .btn-no-bg {
                        background-color: transparent !important;
                        border-color: none !important;
                    }
                </style>
                <div class="container">
                    <style>
                        .button-text {
                            font-size: 12px;
                        }

                        .form-control::placeholder {
                            font-size: 12px;  /* Set the font size to your desired size */
                        }
                    </style>
                    <form method="POST" action="submit-form.php" enctype="multipart/form-data">
                        <!-- Hidden Image File Input (for image file upload) -->
                        <input type="file" name="image" id="image-upload" accept="image/*" style="display:none;">
                        
                        <div class="row justify-content-center">
                            <!-- Left Column: Image and Embed buttons -->
                            <div class="col-md-6 text-center">
                                <!-- Image -->
                                <button id="image-btn" type="button" class="btn btn-no-bg btn-lg w-100 d-flex justify-content-center align-items-center">
                                    <!-- Image SVG Icon -->
                                    <svg viewBox="0 -2 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" style="width: 30px; height: 30px;">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <title>image_picture [#971]</title>
                                            <desc>Created with Sketch.</desc>
                                            <defs></defs>
                                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g id="Dribbble-Light-Preview" transform="translate(-420.000000, -3881.000000)" fill="#000000">
                                                    <g id="icons" transform="translate(56.000000, 160.000000)">
                                                        <path d="M376.083,3725.667 C376.083,3724.562 376.978,3723.667 378.083,3723.667 C379.188,3723.667 380.083,3724.562 380.083,3725.667 C380.083,3726.772 379.188,3727.667 378.083,3727.667 C376.978,3727.667 376.083,3726.772 376.083,3725.667 L376.083,3725.667 Z M382,3733.086 L377.987,3729.074 L377.971,3729.089 L377.955,3729.074 L376.525,3730.504 L371.896,3725.876 L371.881,3725.892 L371.865,3725.876 L366,3731.741 L366,3723 L382,3723 L382,3733.086 Z M364,3737 L384,3737 L384,3721 L364,3721 L364,3737 Z" id="image_picture-[#971]"> </path>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </button>
                                <div class="mt-1 mb-2 button-text">Image</div>

                                <!-- Embed -->
                                <button id="embed-btn" type="button" class="btn btn-no-bg btn-lg w-100 d-flex justify-content-center align-items-center">
                                    <!-- Embed SVG Icon -->
                                    <svg height="30px" width="30px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" fill="#000000">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path class="st0" d="M153.527,138.934c-0.29,0-0.581,0.088-0.826,0.258L0.641,242.995C0.238,243.27,0,243.721,0,244.213v27.921 c0,0.484,0.238,0.943,0.641,1.21l152.06,103.811c0.246,0.17,0.536,0.258,0.826,0.258c0.238,0,0.468-0.064,0.686-0.169 c0.484-0.258,0.782-0.758,0.782-1.306v-44.478c0-0.476-0.238-0.936-0.641-1.202L48.769,258.166l105.585-72.068 c0.403-0.282,0.641-0.734,0.641-1.226V140.41c0-0.548-0.298-1.049-0.782-1.299C153.995,138.991,153.765,138.934,153.527,138.934z"></path>
                                            <path class="st0" d="M511.358,242.995l-152.06-103.803c-0.246-0.169-0.536-0.258-0.827-0.258c-0.238,0-0.467,0.056-0.685,0.177 c-0.484,0.25-0.782,0.751-0.782,1.299v44.478c0,0.484,0.238,0.936,0.641,1.21l105.586,72.068l-105.586,72.092 c-0.403,0.266-0.641,0.725-0.641,1.217v44.462c0,0.548,0.298,1.049,0.782,1.306c0.218,0.105,0.448,0.169,0.685,0.169 c0.291,0,0.581-0.088,0.827-0.258l152.06-103.811c0.404-0.267,0.642-0.726,0.642-1.21v-27.921 C512,243.721,511.762,243.27,511.358,242.995z"></path>
                                            <path class="st0" d="M325.507,114.594h-42.502c-0.629,0-1.186,0.395-1.387,0.984l-96.517,279.885 c-0.153,0.443-0.08,0.943,0.194,1.322c0.278,0.387,0.722,0.621,1.198,0.621h42.506c0.625,0,1.182-0.395,1.387-0.992l96.513-279.868 c0.153-0.452,0.081-0.952-0.193-1.339C326.427,114.828,325.982,114.594,325.507,114.594z"></path>
                                        </g>
                                    </svg>
                                </button>
                                <div class="mt-1 mb-2 button-text">Embed</div>
                            </div>

                            <!-- Right Column: Video and Text buttons -->
                            <div class="col-md-6 text-center">
                                <!-- Video -->
                                <button id="video-btn" type="button" class="btn btn-no-bg btn-lg w-100 d-flex justify-content-center align-items-center">
                                    <!-- Video SVG Icon -->
                                    <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 30px; height: 30px;">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path d="M10 3H0V13H10V3Z" fill="#000000"></path>
                                            <path d="M15 3L12 6V10L15 13H16V3H15Z" fill="#000000"></path>
                                        </g>
                                    </svg>
                                </button>
                                <div class="mt-1 mb-2 button-text">Video</div>

                                <!-- Hidden File Input (to trigger video file selection) -->
                                <input type="file" name="video" id="video-upload" accept="video/*" style="display:none;">

                                <!-- Description -->
                                <button id="text-btn" type="button" class="btn btn-no-bg btn-lg w-100 d-flex justify-content-center align-items-center">
                                    <!-- Text SVG Icon -->
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 30px; height: 30px;">
                                        <g id="SVGRepo_iconCarrier">
                                            <path d="M12 3V21M9 21H15M19 6V3H5V6" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </g>
                                    </svg>
                                </button>
                                <div class="mt-1 mb-2 button-text">Text</div>
                            </div>
                        </div>
                    <!-- Project Title Field -->
                    <div class="mb-3">
                        <label for="projectTitle" class="form-label">Project Title</label>
                        <input type="text" class="form-control border border-secondary" id="projectTitle" name="projectTitle"
                            placeholder="Give your project a title" title="Give your project a title"
                            data-bs-toggle="tooltip" data-bs-placement="top" required>
                    </div>

                    <!-- Project Tags Field -->
                    <div class="mb-3">
                        <label for="projectTags" class="form-label">Project Tags</label>
                        <div class="input-container">
                            <input type="text" class="form-control border border-secondary" id="projectTags" name="projectTags"
                                placeholder="Add keywords to help people discover your project"
                                title="Add keywords to help people discover your project" 
                                data-bs-toggle="tooltip" data-bs-placement="top">
                            <div id="tags-list" class="d-flex flex-wrap mt-2"></div>
                        </div>
                    </div>

                    <!-- Tools Used Field -->
                    <div class="mb-3">
                        <label for="toolsUsed" class="form-label">Tools Used</label>
                        <div class="input-container">
                            <input type="text" class="form-control border border-secondary" id="toolsUsed" name="toolsUsed" 
                                placeholder="What software, hardware, or materials did you use?" 
                                title="What software, hardware, or materials did you use?"
                                data-bs-toggle="tooltip" data-bs-placement="top">
                            <div id="tools-list" class="d-flex flex-wrap mt-2"></div>
                        </div>
                    </div>

                    <!-- Category Selection Field -->
                    <div class="mb-3">
                        <label for="projectCategory" class="form-label">Category</label>
                        <div class="row">
                            <style>
                                .form-check-label {
                                    font-size: 10px;
                                    white-space: nowrap;
                                    display: inline-block;
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                }
                            </style>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Architecture" id="architecture" name="projectCategory[]">
                                    <label class="form-check-label" for="architecture">Architecture</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Art Design" id="artDesign" name="projectCategory[]">
                                    <label class="form-check-label" for="artDesign">Art Design</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Branding" id="branding" name="projectCategory[]">
                                    <label class="form-check-label" for="branding">Branding</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Fashion" id="fashion" name="projectCategory[]">
                                    <label class="form-check-label" for="fashion">Fashion</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Graphic Design" id="graphicDesign" name="projectCategory[]">
                                    <label class="form-check-label" for="graphicDesign">Graphic Design</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Illustration" id="illustration" name="projectCategory[]">
                                    <label class="form-check-label" for="illustration">Illustration</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Industrial Design" id="industrialDesign" name="projectCategory[]">
                                    <label class="form-check-label" for="industrialDesign">Industrial Design</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Logo Design" id="logoDesign" name="projectCategory[]">
                                    <label class="form-check-label" for="logoDesign">Logo Design</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Motion Graphics" id="motionGraphics" name="projectCategory[]">
                                    <label class="form-check-label" for="motionGraphics">Motion Graphics</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Photography" id="photography" name="projectCategory[]">
                                    <label class="form-check-label" for="photography">Photography</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="UI/UX" id="uiux" name="projectCategory[]">
                                    <label class="form-check-label" for="uiux">UI/UX</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Web Design" id="webDesign" name="projectCategory[]">
                                    <label class="form-check-label" for="webDesign">Web Design</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Adult Content Checkbox -->
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="adultContent" name="adultContent">
                            <label class="form-check-label" for="adultContent">This project contains adult content</label>
                        </div>
                    </div>
                    
                    <!-- Description and Embed -->
                    <input type="hidden" name="textDescription" id="textDescription">
                    <input type="hidden" name="embedCode" id="embedCode">

                    <!-- Tags and Tools -->
                    <input type="hidden" id="projectTags1" name="projectTags[]">
                    <input type="hidden" id="projectTags2" name="projectTags[]">
                    <input type="hidden" id="projectTags3" name="projectTags[]">
                    
                    <input type="hidden" id="toolsUsed1" name="toolsUsed[]">
                    <input type="hidden" id="toolsUsed2" name="toolsUsed[]">
                    <input type="hidden" id="toolsUsed3" name="toolsUsed[]">

                    <!-- Buttons: Post and Cancel -->
                    <div class="d-flex flex-column mt-3">
                        <button type="submit" class="btn btn-dark border border-dark mb-2" onclick="validateForm()">Post</button>
                        <button type="button" class="btn btn-light text-dark border-dark mb-2" onclick="window.location.href='../pages/index.php'">Cancel</button>
                    </div>
                </form>

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

        function validateForm() {
            const projectTitle = document.getElementById('projectTitle').value;
            if (!projectTitle.trim()) {
                alert('Project Title is required!');
                return false;
            }
        }

        document.getElementById('image-upload').addEventListener('change', function(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('image-preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(this.files[0]);
        });

    </script>
    <script src="../pages/upload.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>