// Maximum number of categories allowed
const MAX_CATEGORIES = 3;

// Get all the checkboxes for project categories
const categoryCheckboxes = document.querySelectorAll('input[name="projectCategory[]"]');

// Add event listener for each checkbox
categoryCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const checkedCount = document.querySelectorAll('input[name="projectCategory[]"]:checked').length;

        // If more than 3 categories are selected, disable further selection
        if (checkedCount > MAX_CATEGORIES) {
            // Uncheck the last checked category (preventing the 4th selection)
            this.checked = false;
            alert('You can only select up to 3 categories.');
        }
        updateCategoryHiddenInputs();  // Update the hidden inputs after a change
    });
});

// Function to update the hidden inputs for categories
function updateCategoryHiddenInputs() {
    const selectedCategories = Array.from(document.querySelectorAll('input[name="projectCategory[]"]:checked'))
                                      .map(checkbox => checkbox.value);  // Get the selected category values

    console.log('Selected Categories:', selectedCategories);  // Debugging line to check selected categories

    // Fill the hidden inputs with the selected categories (up to 3 categories)
    document.getElementById('projectCategory1').value = selectedCategories[0] || ''; 
    document.getElementById('projectCategory2').value = selectedCategories[1] || ''; 
    document.getElementById('projectCategory3').value = selectedCategories[2] || ''; 

    // If no categories are selected, set the hidden inputs to empty (this is to clear old values)
    if (selectedCategories.length === 0) {
        document.getElementById('projectCategory1').value = '';
        document.getElementById('projectCategory2').value = '';
        document.getElementById('projectCategory3').value = '';
    }
}

// Call the update function on page load to initialize the hidden inputs
document.addEventListener('DOMContentLoaded', updateCategoryHiddenInputs);

// Tools Used - Scoped variables and event listeners
const toolsInputField = document.getElementById('toolsUsed');
const toolsList = document.getElementById('tools-list');
let tools = []; // Store tools in an array for tools used

// Hidden inputs for tools
const toolsUsedHidden1 = document.getElementById('toolsUsed1');
const toolsUsedHidden2 = document.getElementById('toolsUsed2');
const toolsUsedHidden3 = document.getElementById('toolsUsed3');
const MAX_TOOLS = 3; // Maximum number of tools

function createToolTag(toolText) {
    const toolTag = document.createElement('span');
    toolTag.classList.add('badge', 'bg-secondary', 'me-2', 'mb-2');
    toolTag.setAttribute('data-tool', toolText); // Store tool text in a custom data attribute
    toolTag.innerHTML = `${toolText}`; // Tag text displayed
    toolTag.addEventListener('click', removeToolTag); // Make the tag clickable
    return toolTag;
}

toolsInputField.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault(); // Prevent form submission
        const toolText = toolsInputField.value.trim();
        if (toolText && !tools.includes(toolText) && tools.length < MAX_TOOLS) {
            tools.push(toolText);
            toolsList.appendChild(createToolTag(toolText));
            toolsInputField.value = ''; // Clear input field after adding
            updateToolsHiddenInput(); // Update hidden input
        } else if (tools.length >= MAX_TOOLS) {
            alert('You can only add up to 3 tools.');
        }
    }
});

function removeToolTag(event) {
    const toolTagElement = event.target;
    const toolText = toolTagElement.getAttribute('data-tool');
    tools = tools.filter(tool => tool !== toolText); // Remove from tools array
    toolsList.removeChild(toolTagElement); // Remove from DOM
    updateToolsHiddenInput(); // Update hidden input
}

function updateToolsHiddenInput() {
    toolsUsedHidden1.value = tools[0] || ''; // Set first tool
    toolsUsedHidden2.value = tools[1] || ''; // Set second tool
    toolsUsedHidden3.value = tools[2] || ''; // Set third tool
}

// Project Tags - Scoped variables and event listeners
const projectTagsInputField = document.getElementById('projectTags');
const projectTagsList = document.getElementById('tags-list');
let projectTags = []; // Store project tags in an array

// Hidden inputs for tags
const projectTagsHidden1 = document.getElementById('projectTags1');
const projectTagsHidden2 = document.getElementById('projectTags2');
const projectTagsHidden3 = document.getElementById('projectTags3');
const MAX_TAGS = 3; // Maximum number of tags

function createProjectTag(tagText) {
    const tag = document.createElement('span');
    tag.classList.add('badge', 'bg-secondary', 'me-2', 'mb-2');
    tag.setAttribute('data-tag', tagText); // Store tag text in a custom data attribute
    tag.innerHTML = `${tagText}`;
    tag.addEventListener('click', removeProjectTag); // Make the tag clickable
    return tag;
}

projectTagsInputField.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault(); // Prevent form submission
        const tagText = projectTagsInputField.value.trim();
        if (tagText && !projectTags.includes(tagText) && projectTags.length < MAX_TAGS) {
            projectTags.push(tagText);
            projectTagsList.appendChild(createProjectTag(tagText));
            projectTagsInputField.value = ''; // Clear input field after adding
            updateTagsHiddenInput(); // Update hidden input
        } else if (projectTags.length >= MAX_TAGS) {
            alert('You can only add up to 3 project tags.');
        }
    }
});

function removeProjectTag(event) {
    const tagElement = event.target;
    const tagText = tagElement.getAttribute('data-tag');
    projectTags = projectTags.filter(tag => tag !== tagText); // Remove from projectTags array
    projectTagsList.removeChild(tagElement); // Remove from DOM
    updateTagsHiddenInput(); // Update hidden input
}

function updateTagsHiddenInput() {
    projectTagsHidden1.value = projectTags[0] || ''; // Set first tag
    projectTagsHidden2.value = projectTags[1] || ''; // Set second tag
    projectTagsHidden3.value = projectTags[2] || ''; // Set third tag
}

// Function to sort elements by their type
function sortContentContainer() {
    let contentContainer = document.getElementById('content-container');
    let items = Array.from(contentContainer.children);  // Get all child elements

    // Sort the items by their data-type (image, video, embed, description)
    items.sort(function(a, b) {
        const order = ['image', 'video', 'embed', 'description'];
        return order.indexOf(a.getAttribute('data-type')) - order.indexOf(b.getAttribute('data-type'));
    });

    // Re-append the sorted items
    items.forEach(item => contentContainer.appendChild(item));
}

// Image handle
const imageUploadInput = document.getElementById('image-upload');
const imageUploadButton = document.getElementById('image-btn');

// Ensure the button triggers the file input click
imageUploadButton.addEventListener('click', function() {
    // Check if there's already an image or video uploaded
    const existingImageDiv = document.getElementById('content-container').querySelector('div[data-type="image"]');
    const existingVideoDiv = document.getElementById('content-container').querySelector('div[data-type="video"]');
    if (existingImageDiv || existingVideoDiv) {
        alert("An image or video is already uploaded. Please delete the current content before uploading a new one.");
        return; // Stop further execution if there's already an image or video
    }
    imageUploadInput.click();  // Simulate click on hidden file input when image button is pressed
});

// Handle file input change event for image
imageUploadInput.addEventListener('change', function() {
    let file = imageUploadInput.files[0]; // Get the selected file
    if (file) {
        let contentContainer = document.getElementById('content-container');
        
        // Check if there's already an image or video; if so, show an alert and stop
        const existingImageDiv = contentContainer.querySelector('div[data-type="image"]');
        const existingVideoDiv = contentContainer.querySelector('div[data-type="video"]');
        if (existingImageDiv || existingVideoDiv) {
            alert("An image or video is already uploaded. Please delete the current content before uploading a new one.");
            return; // Stop further execution
        }

        // Create a new div for the new image
        let div = document.createElement('div');
        div.style.position = 'relative';
        div.style.marginBottom = '20px';

        // Create a FileReader to read the image file
        let reader = new FileReader();
        reader.onload = function(e) {
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

            deleteBtn.addEventListener('click', function() {
                div.remove(); // Remove the image div along with the delete button
                resetImageUpload(); // Reset file input to allow adding a new image
            });
            div.appendChild(deleteBtn);

            // Show delete button on hover
            div.addEventListener('mouseenter', function() {
                deleteBtn.style.display = 'block';
            });
            div.addEventListener('mouseleave', function() {
                deleteBtn.style.display = 'none';
            });

            // Assign a data-type for sorting
            div.setAttribute('data-type', 'image');
            contentContainer.appendChild(div);
        };

        reader.readAsDataURL(file); // Read the file as DataURL
    }
});

// Function to reset the image upload input
function resetImageUpload() {
    imageUploadInput.value = ''; // This will trigger the change event if needed
}

// Video handle
document.getElementById('video-btn').addEventListener('click', function () {
    // Check if there's already an image or video uploaded
    const existingImageDiv = document.getElementById('content-container').querySelector('div[data-type="image"]');
    const existingVideoDiv = document.getElementById('content-container').querySelector('div[data-type="video"]');
    if (existingImageDiv || existingVideoDiv) {
        alert("An image or video is already uploaded. Please delete the current content before uploading a new one.");
        return; // Stop further execution if there's already an image or video
    }

    let input = document.getElementById('video-upload'); // Get the hidden input field for video
    input.value = '';  // Reset the file input value
    input.click();
    input.removeEventListener('change', handleVideoChange);  // Remove any previously attached listener
    input.addEventListener('change', handleVideoChange);  // Add a new listener
});

function handleVideoChange(event) {
    let input = event.target;
    let file = input.files[0];  // Get the selected file

    if (file) {
        let contentContainer = document.getElementById('content-container');

        // Check if there's already an image or video; if so, show an alert and stop
        const existingImageDiv = contentContainer.querySelector('div[data-type="image"]');
        const existingVideoDiv = contentContainer.querySelector('div[data-type="video"]');
        if (existingImageDiv || existingVideoDiv) {
            alert("An image or video is already uploaded. Please delete the current content before uploading a new one.");
            return; // Stop further execution
        }

        // Create a new div for the new video
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
            div.remove();  // Remove the entire div (video + delete button)
            resetVideoUpload();  // Reset file input to allow adding a new video
        });
        div.appendChild(deleteBtn);

        // Show the delete button when the element is hovered
        div.addEventListener('mouseenter', function () {
            deleteBtn.style.display = 'block';  // Show delete button on hover
        });
        div.addEventListener('mouseleave', function () {
            deleteBtn.style.display = 'none';  // Hide delete button when not hovered
        });

        // Assign a data-type for sorting
        div.setAttribute('data-type', 'video');
        contentContainer.appendChild(div);
        sortContentContainer();  // Sort the container after insertion
    }
}

// Function to reset the video upload input
function resetVideoUpload() {
    let input = document.getElementById('video-upload');
    input.value = '';  // Reset the file input to allow a new video to be selected
}


// Embed handle
document.getElementById('embed-btn').addEventListener('click', function () {
    let contentContainer = document.getElementById('content-container');
    
    // Check if an embed already exists
    if (contentContainer.querySelector('[data-type="embed"]')) {
        alert('Only one embed can exist.');
        return;
    }

    let div = document.createElement('div');
    div.style.position = 'relative';
    div.style.marginBottom = '20px';
    
    let embedInput = document.createElement('input');
    embedInput.type = 'text';
    embedInput.classList.add('form-control');
    embedInput.placeholder = 'Paste your embed code or URL here...';

    embedInput.style.height = '60px';
    embedInput.style.width = '100%';

    div.appendChild(embedInput);

    let deleteBtn = document.createElement('button');
    deleteBtn.innerHTML = 'Delete';
    deleteBtn.classList.add('btn', 'btn-danger');
    deleteBtn.style.position = 'absolute';
    deleteBtn.style.top = '10px';
    deleteBtn.style.right = '10px';

    deleteBtn.addEventListener('click', function () {
        div.remove();
        sortContentContainer();  // Re-sort the container after removal
    });

    div.appendChild(deleteBtn);

    div.addEventListener('mouseenter', function () {
        deleteBtn.style.display = 'block';
    });
    div.addEventListener('mouseleave', function () {
        deleteBtn.style.display = 'none';
    });

    // Assign a data-type for sorting
    div.setAttribute('data-type', 'embed');
    contentContainer.appendChild(div);
    sortContentContainer();  // Sort the container after insertion

    embedInput.addEventListener('input', function() {
        let hiddenEmbedField = document.getElementById('embedCode');
        hiddenEmbedField.value = embedInput.value;
    });
});

// Description Button
document.getElementById('text-btn').addEventListener('click', function() {
    let contentContainer = document.getElementById('content-container');
    
    // Check if a description already exists
    if (contentContainer.querySelector('[data-type="description"]')) {
        alert('Only one description can exist.');
        return;
    }

    let div = document.createElement('div');
    div.style.position = 'relative';
    div.style.marginBottom = '20px';
    
    let textBox = document.createElement('textarea');
    textBox.classList.add('form-control');
    textBox.placeholder = 'Enter the description...';
    div.appendChild(textBox);

    let deleteBtn = document.createElement('button');
    deleteBtn.innerHTML = 'Delete';
    deleteBtn.classList.add('btn', 'btn-danger');
    deleteBtn.style.position = 'absolute';
    deleteBtn.style.top = '10px';
    deleteBtn.style.right = '10px';

    deleteBtn.addEventListener('click', function() {
        div.remove();
        document.getElementById('textDescription').value = '';  // Clear hidden description field
        sortContentContainer();  // Re-sort the container after removal
    });

    div.appendChild(deleteBtn);
    
    div.addEventListener('mouseenter', function() {
        deleteBtn.style.display = 'block';
    });
    div.addEventListener('mouseleave', function() {
        deleteBtn.style.display = 'none';
    });

    // Assign a data-type for sorting
    div.setAttribute('data-type', 'description');
    contentContainer.appendChild(div);
    sortContentContainer();  // Sort the container after insertion

    // Listen for changes in the textarea and update the hidden field accordingly
    textBox.addEventListener('input', function() {
        document.getElementById('textDescription').value = textBox.value;
    });
});
