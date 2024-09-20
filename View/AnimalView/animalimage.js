// animalimage.js

// File validation
document.getElementById('animal_image').addEventListener('change', function() {
    const file = this.files[0];
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    const maxFileSize = 2 * 1024 * 1024; // 2MB

    // Check if a file was selected
    if (!file) {
        alert('No file selected.');
        return;
    }

    // Check file type
    if (!allowedTypes.includes(file.type)) {
        alert('Invalid file type. Only JPG, JPEG, and PNG are allowed.');
        this.value = ''; // Clear the input
        return;
    }

    // Check file size
    if (file.size > maxFileSize) {
        alert('File size exceeds limit. Please upload a file smaller than 2MB.');
        this.value = ''; // Clear the input
        return;
    }

    // If everything is fine
    alert('File is valid.');
});

// input validation


function updateSpecies() {
    const selectedItem = document.getElementById('itemName').value;
    document.getElementById('species').value = itemSpeciesMap[selectedItem];
}

// Initialize the species field when the form loads
window.onload = updateSpecies;

// Input validation
function validateForm() {
    const invalidChars = /[?@#$^~+\-*\/:]/;  // Disallowed characters
    const fields = ['name', 'subspecies', 'description', 'height', 'weight'];  // Fields to validate

    for (let field of fields) {
        const value = document.getElementById(field).value;
        if (invalidChars.test(value)) {
            alert(`Invalid characters in ${field}. Please remove symbols like ? @ # $ ^ ~ + - * / :`);
            return false;  // Prevent form submission if invalid characters are found
        }
    }
    return true;  // Allow form submission if all checks pass
}

// Attach the validateForm function to the form submission
document.querySelector('form').addEventListener('submit', function(event) {
    if (!validateForm()) {
        event.preventDefault();  // Prevent form submission if validation fails
    }
});