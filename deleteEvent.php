<?php
require_once 'Control/eventController/EventController.php';
require_once 'View/eventView/EventView.php';

// Initialize the view and controller
$view = new EventView();
$controller = new EventController($view);
// Handle form submissions
if (isset($_POST['delete_selected'])) {
    if (!empty($_POST['event_ids']) && !empty($_POST['type'])) {
        try {
            //Attempt to delete selected events
            $controller->deleteSelectedEvents($_POST['event_ids'], $_POST['type']);
            exit();
        } catch (Exception $e) {           
            // Handle any exceptions that occur during deletion
            error_log($e->getMessage()); // Log the error message
            // Redirect to the same page with an error parameter to show the error message
            header('Location: deleteEvent.php?error=' . urlencode($e->getMessage()));
            exit();
        }
    } else {
        // Redirect to the same page with a noEvent parameter if no events are selected
        header('Location: deleteEvent.php?noEvent=2');
        exit();
    }
} elseif (isset($_POST['filter'])) {
    $type = filter_var($_POST['filter'], FILTER_SANITIZE_STRING); // Sanitize input
    try {
        // Fetch and display events based on the selected type
        $controller->fetchAndDisplayEvents($type);
    } catch (Exception $e) {
        // Handle any exceptions that occur during fetching events
        error_log($e->getMessage()); // Log the error message
        // Redirect to the same page with an error parameter to show the error message
        header('Location: deleteEvent.php?error=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Default to display all events if no filter is selected
    try {
        $controller->fetchAndDisplayEvents();
    } catch (Exception $e) {
        // Handle any exceptions that occur during fetching all events
        error_log($e->getMessage()); // Log the error message
        // Redirect to the same page with an error parameter to show the error message
        header('Location: deleteEvent.php?error=' . urlencode($e->getMessage()));
        exit();
    }
}
?>
