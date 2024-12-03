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

// Query to fetch the data from the 'works' table
$query_works = "SELECT user_email, projectTitle, author, imagePath, textDescription, projectCategories, projectTags1, projectTags2, projectTags3, toolsUsed1, toolsUsed2, toolsUsed3, adultContent FROM works";
$works_result = mysqli_query($connection, $query_works);

if (!$works_result) {
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
<style>
    /* Modal Styles */
    .modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 90vw; /* Modal width is 70% of the viewport width */
        max-width: 1500px;
        height: 90vh;
        display: none;
        z-index: 9999;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        border: none;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
        transition: opacity 0.5s ease; /* Transition for fade-in/fade-out */
        opacity: 0;
    }

    /* Modal Content */
    .modal-content {
        background-color: white;
        border-radius: 0px !important;
        width: 100%;
        height: 100%;
        position: relative;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
        overflow: hidden;
    }

    /* Modal Body - Flexbox container for image and content */
    .modal-body {
        display: flex;
        width: 100%;
        padding: 0px !important;
    }

    /* Modal Image Container */
    .modal-image-container {
        flex: 1; /* The image container takes up all available space */
        max-height: 100%; /* Optional: To limit the height of the image */
        overflow: hidden;
        position: relative;
        display: flex;
    }

    .modal-image {
        width: 100%;
        height: 100vh;
        object-fit: cover;
        object-position: center;
        border-radius: 0px !important;
    }

    /* Modal Content Details */
    .modal-content-details {
        flex: 0 0 30%; /* The content container takes up 30% of the modal width */
        margin: 20px 20px 0 20px;
        overflow-y: auto; /* Allow scrolling if content is too long */
    }

    /* Close Button */
    .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 1.5rem;
        color: #333;
        cursor: pointer;
        display: none;
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        display: none;
        z-index: 9998;
        transition: opacity 0.5s ease; /* Transition for fade-in/fade-out */
        opacity: 0;
    }

    /* Add this CSS class for the blur effect */
    .blur {
        filter: blur(50px); /* Apply blur */
        transition: filter 0.5s ease; /* Smooth transition */
    }

</style>
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
                    <li><a class="dropdown-item" href="../pages/filter-img.php">Images</a></li>
                    <li><a class="dropdown-item" href="../pages/filter-video.php">Videos</a></li>
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
    <div class="container mt-5" id="card-container">
        <div class="row row-cols-1 row-cols-md-3 g-4"> <!-- Bootstrap grid for better layout -->
            <!-- Loop through the works and display them as cards -->
            <?php 
            $counter = 0; // Initialize the counter
            while ($row = mysqli_fetch_assoc($works_result)) { 
                if ($counter == 0) {
                    $counter++;
                    continue;
                }

                // Concatenate the relative URL with the imagePath stored in the database
                $image_url = "/lpu-eportfolio" . htmlspecialchars($row['imagePath']);
            ?>
                <div class="col">
                    <a href="javascript:void(0);" class="text-decoration-none open-modal" 
                    data-title="<?php echo htmlspecialchars($row['projectTitle']); ?>" 
                    data-author="<?php echo htmlspecialchars($row['author']); ?>" 
                    data-image="<?php echo $image_url; ?>"
                    data-description="<?php echo htmlspecialchars($row['textDescription']); ?>"
                    data-categories="<?php echo htmlspecialchars($row['projectCategories']); ?>"
                    data-tags1="<?php echo htmlspecialchars($row['projectTags1']); ?>"
                    data-tags2="<?php echo htmlspecialchars($row['projectTags2']); ?>"
                    data-tags3="<?php echo htmlspecialchars($row['projectTags3']); ?>"
                    data-tools1="<?php echo htmlspecialchars($row['toolsUsed1']); ?>"
                    data-tools2="<?php echo htmlspecialchars($row['toolsUsed2']); ?>"
                    data-tools3="<?php echo htmlspecialchars($row['toolsUsed3']); ?>"
                    data-adult="<?php echo $row['adultContent']; ?>"
                    data-user-email="<?php echo htmlspecialchars($row['user_email']); ?>">
                        <div class="card h-100 shadow-lg border-light rounded">
                            <img src="<?php echo $image_url; ?>" class="card-img-top" alt="Project Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['projectTitle']); ?></h5>
                                <p class="card-text text-muted">by <?php echo htmlspecialchars($row['author']); ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php 
            } 
            ?>

        </div>
    </div>

    <!-- Page Overlay for Dimming Effect -->
    <div id="modalOverlay" class="modal-overlay"></div>

    <!-- Modal Structure -->
    <div id="modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            
            <!-- Modal Body (Two containers for image and content) -->
            <div class="modal-body d-flex">
                <!-- Left container for the image -->
                <div class="modal-image-container">
                    <img id="modal-image" class="modal-image" src="" alt="Modal Image">
                </div>

                <!-- Right container for the content details -->
                <div class="modal-content-details">
                    <h3 id="modal-title"></h3>
                    <p id="modal-author"></p>
                    <p id="modal-description"></p>
                    <p id="modal-categories"></p>
                    <p id="modal-tags"></p>
                    <p id="modal-tools"></p>
                    <p id="modal-adult-content"></p>

                    <!-- Contact Form (Fixed email input) -->
                    <div id="contact-form" class="mt-3">
                        <h3>Contact</h3>
                        <p>Let's get this conversation started.</p>
                        <form id="contactForm" onsubmit="submitForm(event)">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                            </div>
                            <input type="hidden" id="author_email" name="author_email">
                            <button type="submit" class="btn btn-dark border border-dark">Send</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Pass the user email to JavaScript -->
    <script>

         // Echo the logged-in user's email from PHP to JavaScript
        var userEmail = "<?php echo htmlspecialchars($user_email); ?>";

        // Log the user's email to the console
        console.log("Logged in as: " + userEmail);

        function redirectToUpload() {
            // Redirect to the 'upload.php' page
            window.location.href = 'upload.php';
        }

        // Get modal elements
        const modal = document.getElementById("modal");
        const modalImage = document.getElementById("modal-image");
        const modalTitle = document.getElementById("modal-title");
        const modalAuthor = document.getElementById("modal-author");
        const modalDescription = document.getElementById("modal-description");
        const modalCategories = document.getElementById("modal-categories");
        const modalTags = document.getElementById("modal-tags");
        const modalTools = document.getElementById("modal-tools");
        const modalAdultContent = document.getElementById("modal-adult-content");
        const modalOverlay = document.getElementById("modalOverlay");
        const closeButton = document.querySelector(".close-btn");

        // Event listener for opening the modal
        const cardLinks = document.querySelectorAll('.open-modal');
        cardLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                
                // Get data from the clicked card
                const title = this.getAttribute('data-title');
                const author = this.getAttribute('data-author');
                const image = this.getAttribute('data-image');
                const description = this.getAttribute('data-description');
                const categories = this.getAttribute('data-categories')
                const tags1 = this.getAttribute('data-tags1');
                const tags2 = this.getAttribute('data-tags2');
                const tags3 = this.getAttribute('data-tags3');
                const tools1 = this.getAttribute('data-tools1');
                const tools2 = this.getAttribute('data-tools2');
                const tools3 = this.getAttribute('data-tools3');
                const adultContent = this.getAttribute('data-adult');

                // Get the user's email associated with the project
                const authorEmail = this.getAttribute('data-user-email'); // Get the user's email from the clicked card
                console.log("User Email for this work: " + authorEmail); // Log it to the console
                
                // Set the modal content
                modalTitle.textContent = title;
                modalAuthor.textContent = "by " + author;
                modalImage.src = image;
                modalDescription.textContent = description;
                modalCategories.innerHTML = "<b>Creative Styles:</b><br>" + categories;
                
                let tagsArray = [tags1, tags2, tags3].filter(tag => tag !== "");
                let toolsArray = [tools1, tools2, tools3].filter(tool => tool !== "");
                
                modalTags.innerHTML = tagsArray.length > 0 ? `<b>Tags:</b><br> ${tagsArray.join(', ')}` : "";
                modalTools.innerHTML = toolsArray.length > 0 ? `<b>Tools Used:</b><br> ${toolsArray.join(', ')}` : "";

                if (adultContent == "1") {
                    // modalAdultContent.innerHTML = "<i>Contains Adult Content</i>";
                    modalImage.classList.add('blur');
                } else {
                    // modalAdultContent.innerHTML = "<i>Non Adult Content</i>";
                    modalImage.classList.remove('blur');
                }

                // Set the hidden input field with the author's email
                document.getElementById('author_email').value = authorEmail;

                // Display the modal and overlay with transition
                modal.style.display = 'flex';
                modalOverlay.style.display = 'block';
                setTimeout(() => {
                    modal.style.opacity = '1'; // Fade in the modal
                    modalOverlay.style.opacity = '1'; // Fade in the overlay
                }, 10); // Small delay to allow the display property to be applied first
            });
        });

        // Event listener for closing the modal
        closeButton.addEventListener('click', function() {
            modal.style.opacity = '0'; // Fade out the modal
            modalOverlay.style.opacity = '0'; // Fade out the overlay
            setTimeout(() => {
                modal.style.display = 'none';
                modalOverlay.style.display = 'none'; // Hide the modal and overlay after fading out
            }, 500); // Match the transition duration for smooth fading
        });

        // Close modal if clicked outside the modal content
        window.addEventListener('click', function(event) {
            if (event.target === modal || event.target === modalOverlay) {
                closeButton.click();
            }
        });

        // Event listener for removing the blur when the image is clicked
        modalImage.addEventListener('click', function() {
            if (modalImage.classList.contains('blur')) {
                modalImage.classList.remove('blur');
            }
        });

        function submitForm(event) {
        event.preventDefault();  // Prevent the form from submitting the traditional way

        // Get form values
        var userEmail = document.getElementById('email').value;
        var message = document.getElementById('message').value;
        var authorEmail = document.getElementById('author_email').value;  // Get the author's email (hidden field)

        // Build the Gmail compose URL with the form values
        var subject = "Galleon Gallery Inquiry";
        var body = message;

        // Construct the Gmail URL
        var gmailComposeURL = "https://mail.google.com/mail/?view=cm&fs=1&to=" + encodeURIComponent(authorEmail) +
                                "&su=" + encodeURIComponent(subject) + 
                                "&body=" + encodeURIComponent(body);

        // Open the Gmail compose URL in a new tab
        window.open(gmailComposeURL, '_blank');
    }

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>