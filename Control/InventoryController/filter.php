<?php
/*Author name: Lim Shuye*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the filters array is set and not empty
    if (isset($_POST['filters']) && !empty($_POST['filters'])) {
        // Count the number of selected checkboxes
        $selected_count = count($_POST['filters']);

        $filters = $_POST['filters'];
        if (in_array("Animal", $filters)) {
            header("Location: index.php?controller=inventory&action=inventoryTracking");
        }
        if (in_array("Habitat", $filters)) {
            header("Location: index.php?controller=inventory&action=habitatItem");
        }
        if (in_array("Food", $filters)) {
            header("Location: index.php?controller=inventory&action=foodItem");
        }
        if (in_array("Cleaning", $filters)) {
            header("Location: index.php?controller=inventory&action=cleaningItem");
        }
      
    } 
}    