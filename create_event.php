<?php
require_once 'Control/eventController/EventController.php';
require_once 'View/eventView/EventView.php';

// Initialize the view and controller
$eventView = new EventView();
$eventController = new EventController($eventView);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = isset($_POST['type']) ? trim($_POST['type']) : '';
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $location = isset($_POST['location']) ? trim($_POST['location']): '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : ''; 
    // Initialize additional fields based on event type
    if ($type === "Public") {
        $capacity = isset($_POST['capacity']) ? trim($_POST['capacity']) : null;
        $price = isset($_POST['price']) ? trim($_POST['price']) : null;
        $date = trim($_POST['date'])? trim($_POST['date']) : null;
        $starttime = trim($_POST['starttime'])? trim($_POST['starttime']) : null;
        $endtime = trim($_POST['endtime'])? trim($_POST['endtime']) : null;
        $deposit = null;
        $numberOfAttendees = null;
    } elseif ($type === "Private") {
        $price = null;
        $capacity = null;
        $date = null;
        $starttime = null;
        $endtime = null;
        $deposit = isset($_POST['deposit']) ? trim($_POST['deposit']) : null;
        $numberOfAttendees = isset($_POST['number_of_attendees']) ? trim($_POST['number_of_attendees']) : null;
    } 
    // Create the event using the controller
    $eventController->validateEvent($type, $title, $description, $location, $date, $starttime, $endtime, $price, $capacity, $deposit, $numberOfAttendees);
    
} else {
    // Display the form for GET request
    $eventView->displayForm();
}
?>
