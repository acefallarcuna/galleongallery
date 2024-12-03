// Image handle
// Get the input and button elements
const imageUploadInput = document.getElementById('image-upload');
const imageUploadButton = document.getElementById('image-btn');

// Attach the change event listener once when the page loads
imageUploadInput.addEventListener('change', function () {
    let file = imageUploadInput.files[0]; // Get the selected file

    console.log("File selected:", file);

    if (file) {
        let contentContainer = document.getElementById('content-container');
        let div = document.createElement('div');
        div.style.position = 'relative';
        div.style.marginBottom = '20px';

        // Create a FileReader to read the image file
        let reader = new FileReader();
        reader.onload = function (e) {
            let img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '100%';
            img.style.height = 'auto';
            div.appendChild(img);

            // Add a delete button for the image
            let deleteBtn = document.createElement('button');
            deleteBtn.innerHTML = 'Delete';
            deleteBtn.classList.add('btn', 'btn-danger');
            deleteBtn.style.position = 'absolute';
            deleteBtn.style.top = '10px';
            deleteBtn.style.right = '10px';

            deleteBtn.addEventListener('click', function () {
                console.log("Delete button clicked, removing image...");
                div.remove(); // Remove the image div along with the delete button
            });
            div.appendChild(deleteBtn);

            // Show delete button on hover
            div.addEventListener('mouseenter', function () {
                deleteBtn.style.display = 'block';
            });
            div.addEventListener('mouseleave', function () {
                deleteBtn.style.display = 'none';
            });

            // Append the image div to the content container
            contentContainer.appendChild(div);
            console.log("Image and delete button added.");
        };

        reader.readAsDataURL(file); // Read the file as a DataURL
    }
});

// When the button is clicked, trigger the file input
imageUploadButton.addEventListener('click', function () {
    // Reset the input value before triggering the click to ensure re-triggering works
    imageUploadInput.value = ''; // This allows re-selecting the same file
    imageUploadInput.click(); // Trigger the file input dialog
});