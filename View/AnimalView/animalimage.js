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
