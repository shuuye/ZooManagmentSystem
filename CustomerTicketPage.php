<?php
session_start();  // Start session at the very beginning

require_once __DIR__ . '/Control/TicketController/CustomerTicketControl.php';

// Ensure user is logged in
if (!isset($_SESSION['userModel']['id'])) {
    die('User is not logged in. Please log in and try again.');
}

// Initialize the controller and handle the request
try {
    $controller = new CustomerTicketControl();
    $controller->route();
} catch (Exception $e) {
    // Display an error message if something goes wrong
    echo '<p style="color: red;">' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>
