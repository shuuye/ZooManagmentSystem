<?php

session_start();  // Start session at the very beginning
// Ensure user is logged in
if (!isset($_SESSION['currentUserModel']['id'])) {
    die('User is not logged in. Please log in and try again.');
}

// Load the XML data
$filePath = __DIR__ . '/Model/Xml/ticket_purchases.xml';
require_once __DIR__ . '/Model/Tickets/PaymentModel.php';

$paymentModel = new PaymentModel();
$transactionId = $_GET['transaction_id'] ?? null; // Get transaction ID from PayPal response
if ($transactionId === null) {
    die('Transaction ID is missing.');
}

$totalPrice = $paymentModel->calculateTotalPrice(); // Calculate total price
// Save payment details to the database
try {
    $paymentModel->savePaymentDetails($transactionId, $totalPrice, $_SESSION['currentUserModel']['id']);
} catch (Exception $e) {
    die('Failed to save payment details: ' . htmlspecialchars($e->getMessage()));
}

// Load the XML file
if (file_exists($filePath)) {
    $xml = simplexml_load_file($filePath);
} else {
    die('XML file not found.');
}

// Start outputting the summary report
echo '<html><head><title>Invoice Summary</title>';
echo '<style>
    body { font-family: Arial, sans-serif; margin: 20px; background-color: #f9f9f9; color: #333; }
    h1 { text-align: center; color: #4CAF50; }
    table { width: 80%; margin: 20px auto; border-collapse: collapse; }
    table, th, td { border: 1px solid #ddd; }
    th, td { padding: 12px; text-align: left; }
    th { background-color: #4CAF50; color: white; }
    tr:nth-child(even) { background-color: #f2f2f2; }
    tr:hover { background-color: #ddd; }
    .total { font-weight: bold; font-size: 1.2em; color: #4CAF50; text-align: center; margin: 20px 0; }
    .confirm-button { 
        display: block; 
        margin: 20px auto; 
        padding: 12px 24px; 
        background-color: #4CAF50; 
        color: white; 
        text-align: center; 
        text-decoration: none; 
        border-radius: 5px; 
        font-size: 18px; 
        border: 2px solid #4CAF50; 
        transition: background-color 0.3s, color 0.3s; 
    }
    .confirm-button:hover { 
        background-color: white; 
        color: #4CAF50; 
    }
    .confirm-button:focus {
        outline: none;
        box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
    }
</style>';
echo '</head><body>';
echo '<h1>Invoice Summary</h1>';

// Display the ticket details in a table
echo '<table>';
echo '<tr><th>Type</th><th>Quantity</th><th>Price (RM)</th><th>Visit Date</th></tr>';

$totalAmount = 0;

foreach ($xml->Ticket as $ticket) {
    $type = htmlspecialchars($ticket->type);
    $quantity = htmlspecialchars($ticket->quantity);
    $price = number_format((float) $ticket->price, 2, '.', '');
    $visitDate = htmlspecialchars($ticket->visit_date);

    $totalAmount += (float) $ticket->total;

    echo '<tr>';
    echo '<td>' . $type . '</td>';
    echo '<td>' . $quantity . '</td>';
    echo '<td>' . $price . '</td>';
    echo '<td>' . $visitDate . '</td>';
    echo '</tr>';
}

echo '</table>';

// Display the total amount
echo '<h2 class="total">Total Amount After Discount: RM' . number_format($totalPrice, 2, '.', '') . '</h2>';
echo '<a href="home.php" class="confirm-button">Confirm and Return to Home</a>';

echo '</body></html>';
?>
