// Wait for the DOM to load
document.addEventListener('DOMContentLoaded', function() {
    // Select the <label> element with the for="email" attribute
    var emailLabel = document.querySelector('label[for="email"]');

    // Check if the label exists and modify its innerHTML
    if (emailLabel) {
        emailLabel.innerHTML = 'Email <span class="text-danger">* Invalid Email or Password</span>';
    }
});
