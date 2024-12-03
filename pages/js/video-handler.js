// Function to handle video upload
document.getElementById('video-btn').addEventListener('click', function () {
    let input = document.getElementById('video-upload'); // Get the hidden input field for video

    // Reset the input value before triggering the click to ensure re-triggering works
    input.value = '';  // Reset the file input value

    // Trigger the file input dialog when the button is clicked
    input.click();

    // Attach the change event listener only once
    input.removeEventListener('change', handleVideoChange);  // Remove any previously attached listener
    input.addEventListener('change', handleVideoChange);  // Add a new listener

});

// Function to handle video change event
function handleVideoChange(event) {
    let input = event.target;
    let file = input.files[0];  // Get the selected file

    if (file) {
        // Check if the file size is greater than 25MB (25 * 1024 * 1024 bytes)
        if (file.size > 25 * 1024 * 1024) {
            alert("The file is too large! Please select a video below 25MB.");
            return; // Exit the function if the file is too large
        }

        let contentContainer = document.getElementById('content-container');
        let div = document.createElement('div');
        div.style.position = 'relative';  // Make div relative to position delete button
        div.style.marginBottom = '20px';  // Space between elements

        // Create and display the video element
        let video = document.createElement('video');
        video.src = URL.createObjectURL(file);  // Create a URL for the video blob
        video.controls = true;  // Show video controls (play, pause, etc.)
        video.style.maxWidth = '100%';  // Ensure the video fits within the container
        div.appendChild(video);

        // Add delete button for the video
        let deleteBtn = document.createElement('button');
        deleteBtn.innerHTML = 'Delete';
        deleteBtn.classList.add('btn', 'btn-danger');
        deleteBtn.style.position = 'absolute';
        deleteBtn.style.top = '10px';
        deleteBtn.style.right = '10px';  // Position the delete button to the top-right
        deleteBtn.addEventListener('click', function () {
            div.remove(); // Remove the entire div (video + delete button)
        });
        div.appendChild(deleteBtn);

        // Show the delete button when the element is hovered
        div.addEventListener('mouseenter', function () {
            deleteBtn.style.display = 'block';  // Show delete button on hover
        });
        div.addEventListener('mouseleave', function () {
            deleteBtn.style.display = 'none';  // Hide delete button when not hovered
        });

        // Append the video container to the content container
        contentContainer.appendChild(div);
    }
}
