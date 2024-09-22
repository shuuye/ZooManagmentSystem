<?php
    /*Author Name: Chew Wei Seng*/
require_once __DIR__ . '/../../Model/User/UserModel.php';

class UserView extends UserModel {
    public function render($view=[], $data = []) {
        // Extract data to be accessible as variables within the view
        extract($data);

        // Start output buffering
        ob_start();

        // Include the view file
        // Assume view files are stored in a "View" directory
        include dirname(__DIR__, 2) . '/View/' . 'htmlHead.php';

        // Loop through each view in the array and include it
        foreach ((array)$view as $viewFile) {
            if ($viewFile == 'adminTopNavHeader') {
                include dirname(__DIR__, 2) . '/View/' . $viewFile . '.php';
            } elseif (strpos($viewFile, '<table') === 0) {
                // If the viewFile is a transformed output (like the table), echo it directly
                echo $viewFile;
            } else {
                include dirname(__DIR__, 2) . '/View/UserView/' . $viewFile . '.php';
            }
        }

        include dirname(__DIR__, 2) . '/View/' . 'footer.php';

        // Get the content from the output buffer
        $output = ob_get_contents();

        // End buffering and clean the buffer
        ob_end_clean();

        // Print out the rendered result
        return $output;
    }
}
