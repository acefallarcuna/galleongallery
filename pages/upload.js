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

// Function to create the Image element with a delete button
document.getElementById('image-btn').addEventListener('click', function () {
    let contentContainer = document.getElementById('content-container');

    let div = document.createElement('div');
    div.style.position = 'relative';  // Make div relative to position delete button
    div.style.marginBottom = '20px';  // Space between elements
    
    let input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';  // Accept only image files
    input.style.display = 'none';  // Hide the file input field

    // Trigger the file input dialog when the button is clicked
    input.click();

    // When a file is selected, this will be triggered
    input.addEventListener('change', function () {
        let file = input.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                let img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
                div.appendChild(img);

                // Add delete button for the image
                let deleteBtn = document.createElement('button');
                deleteBtn.innerHTML = 'Delete';
                deleteBtn.classList.add('btn', 'btn-danger');
                deleteBtn.style.position = 'absolute';
                deleteBtn.style.top = '10px';
                deleteBtn.style.right = '10px';  // Position the delete button to the top-right
                deleteBtn.style.display = 'none';  // Initially hide the button
                deleteBtn.addEventListener('click', function () {
                    div.remove(); // Remove the entire div (image + delete button)
                });
                div.appendChild(deleteBtn);

                // Show the delete button when the element is hovered
                div.addEventListener('mouseenter', function () {
                    deleteBtn.style.display = 'block';  // Show delete button on hover
                });
                div.addEventListener('mouseleave', function () {
                    deleteBtn.style.display = 'none';  // Hide delete button when not hovered
                });
            };
            reader.readAsDataURL(file);
        }
    });

    contentContainer.appendChild(div);  // Append the div to content container
});

// Function to handle video file upload
document.getElementById('video-btn').addEventListener('click', function () {
    // Trigger hidden file input click when video button is clicked
    let input = document.createElement('input');
    input.type = 'file';
    input.accept = 'video/*';  // Accept only video files
    input.style.display = 'none';  // Hide the file input field

    // Trigger the file input dialog when the button is clicked
    input.click();

    // When a file is selected, this will be triggered
    input.addEventListener('change', function () {
        let file = input.files[0];  // Get the selected file

        if (file) {
            // Check if the file size is greater than 25MB (25 * 1024 * 1024 bytes)
            if (file.size > 25 * 1024 * 1024) {
                alert("The file is too large! Please select a video below 25MB.");
                return; // Exit the function if the file is too large
            }

            // Create a container div for the video
            let contentContainer = document.getElementById('content-container'); // Replace with your actual container ID
            let div = document.createElement('div');
            div.style.position = 'relative';  // Make div relative to position delete button
            div.style.marginBottom = '20px';  // Space between elements

            // Create a video element
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
            deleteBtn.style.display = 'none';  // Initially hide the button
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

            contentContainer.appendChild(div);  // Append the div to content container
        }
    });
});
