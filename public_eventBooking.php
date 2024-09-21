<?php
require_once 'Control/eventController/public_eventController.php'; // Adjust based on actual location
require_once 'View/eventView/eventBookingView.php'; // Adjust based on actual location

session_start(); // Start the session to access session data

$view = new eventBookingView();
$controller = new public_eventController($view);

if (isset($_SESSION['currentUserModel'])) {
    $currentUserModel = $_SESSION['currentUserModel'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Extract user details from session
        $customerid = htmlspecialchars($currentUserModel['id']);
        $fullname = htmlspecialchars($currentUserModel['full_name']);

        
        // Extract event details from POST data
        $eventId = isset($_POST['event_id']) ? trim($_POST['event_id']) : null;
        $ticket_number = isset($_POST['ticket_number']) ? trim($_POST['ticket_number']) : null;
        $type = isset($_POST['type']) ? trim($_POST['type']) : '';
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $location = isset($_POST['location']) ? trim($_POST['location']) : '';
        $price = isset($_POST['price']) ? trim($_POST['price']) : null;
        $date = isset($_POST['date']) ? trim($_POST['date']) : null;
        $starttime = isset($_POST['starttime']) ? trim($_POST['starttime']) : null;
        $endtime = isset($_POST['endtime']) ? trim($_POST['endtime']) : null;
        
        // Pass data to controller for validation and processing
        $controller->validateAndProcessTicketPurchase($customerid, $fullname,$eventId, $ticket_number, $type, $title, $location, $price, $date, $starttime, $endtime);
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
