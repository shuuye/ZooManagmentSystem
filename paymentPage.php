<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['currentUserModel']['id'])) {
    die('User is not logged in. Please log in and try again.');
}

// Load the XML data
$filePath = __DIR__ . '/Model/Xml/ticket_purchases.xml';
require_once __DIR__ . '/Model/Tickets/PaymentModel.php';
require_once __DIR__ . '/View/TicketView/PaymentView.php';

$paymentModel = new PaymentModel();

// Calculate total price and load ticket purchases
$totalPrice = $paymentModel->calculateTotalPrice();
if (file_exists($filePath)) {
    $xml = simplexml_load_file($filePath);
} else {
    die('XML file not found.');
}

// Display payment details and PayPal button
PaymentView::displayPaymentDetails($xml, $totalPrice);
?>
