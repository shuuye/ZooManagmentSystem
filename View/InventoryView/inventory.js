document.addEventListener("DOMContentLoaded", function() {
var modal = document.getElementById("filterModal");
        var filterButton = document.querySelector(".filter");
        var closeButton = document.querySelector(".close");
        // Get references to elements
        const checkboxes = document.querySelectorAll('input[name="record[]"]');
        var filterbtn = document.getElementById("filterbtn");
        const addItemButton = document.getElementById("newbtn");
        filterButton.onclick = function () {
        modal.style.display = "block";
        };
        closeButton.onclick = function () {
        modal.style.display = "none";
        };
        window.onclick = function(event) {
        if (event.target == modal) {
        modal.style.display = "none";
        }
        };
        // Get references to elements
        const checkboxes = document.querySelectorAll('input[name="record[]"]');
        const addItemButton = document.querySelector(".new-item");
        // Function to check if any checkbox is selected
                function checkCheckboxes() {
                const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
                        // Disable/Enable buttons based on whether any checkbox is selected
                        filterbtn.style.pointerEvents = anyChecked ? 'none' : 'auto';
                        addItemButton.style.pointerEvents = anyChecked ? 'none' : 'auto';
                        filterbtn.style.opacity = anyChecked ? '0.5' : '1';
                        addItemButton.style.opacity = anyChecked ? '0.5' : '1';
                }

        // Attach event listeners to each checkbox
        checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', checkCheckboxes);
        });
                // Initial check in case any checkbox is already selected
                checkCheckboxes();
        });


