<?php
require_once 'Control/eventController/public_eventController.php'; // Adjust based on actual location
require_once 'View/eventView/eventBookingView.php'; // Adjust based on actual location

session_start(); // Start the session to access session data


$view = new eventBookingView();
$controller = new public_eventController($view);

if (isset($_SESSION['currentUserModel'])) {
    $currentUserModel = $_SESSION['currentUserModel'];
    $customerid = htmlspecialchars($currentUserModel['id']);         
    $controller->fetchPublicEventBookings($customerid);
    } 
else {
  echo '
<script type="text/javascript">
    alert("No user is logged in.");
        window.location.href = "index.php?controller=user&action=login";
</script>';
exit;
}
?>
