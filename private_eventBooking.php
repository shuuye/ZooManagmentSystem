<?php
require_once 'Control/eventController/private_eventController.php'; // Adjust based on actual location
require_once 'View/eventView/eventBookingView.php'; // Adjust based on actual location

session_start(); // Start the session to access session data

$view = new eventBookingView();
$controller = new private_eventController($view);

if (isset($_SESSION['currentUserModel'])) {
    $currentUserModel = $_SESSION['currentUserModel'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Extract user details from session
        $customerid = htmlspecialchars($currentUserModel['id']);
        $fullname = htmlspecialchars($currentUserModel['full_name']);
       
        // Extract event details from POST data
        $eventId = isset($_POST['event_id']) ? trim($_POST['event_id']) : null;
        $number_of_attendees = isset($_POST['number_of_attendees']) ? trim($_POST['number_of_attendees']) : null;
        $numberofattendees = isset($_POST['numberofattendees']) ? trim($_POST['numberofattendees']) : null;
        $type = isset($_POST['type']) ? trim($_POST['type']) : '';
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $location = isset($_POST['location']) ? trim($_POST['location']) : '';
        $deposit = isset($_POST['deposit']) ? trim($_POST['deposit']) : null;
        $date = isset($_POST['date']) ? trim($_POST['date']) : null;
        $starttime = isset($_POST['starttime']) ? trim($_POST['starttime']) : null;
        $endtime = isset($_POST['endtime']) ? trim($_POST['endtime']) : null;
        
        // Pass data to controller for validation and processing
        //echo '<pre>';
//echo '</pre>';
        $controller->validateAndProcessTicketPurchase($customerid, $fullname, $eventId, $numberofattendees, $number_of_attendees, $type, $title, $location, $deposit, $date, $starttime, $endtime);
    } elseif (isset($_GET['event_ids'])) {
        // Display event details for the selected event
        $eventId = $_GET['event_ids'][0]; // Get the first event ID from selected checkboxes
        $controller->displayEventDetails($eventId);
    } else {
        // Display the list of events
        $controller->fetchAndDisplayEvents();
    }
} else {
    echo '
<script type="text/javascript">
    alert("No user is logged in.");
        window.location.href = "index.php?controller=user&action=login";
</script>';
exit;
}
?>
