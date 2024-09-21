<?php
header('Content-Type: application/json');

// Check if customerID, startDate, and endDate are provided
if (!isset($_GET['customerid']) || !isset($_GET['start_date']) || !isset($_GET['end_date'])) {
    echo json_encode(['error' => 'Customer ID, start date, and end date are required']);
    http_response_code(400);  // Bad Request
    exit;
}

$customerID = $_GET['customerid'];
$startDate = $_GET['start_date'];
$endDate = $_GET['end_date'];

// Database connection settings
$host = 'localhost';
$db_name = 'zooManagementdb';
$username = 'alibaba';
$password = 'alibaba123';

try {
    // Set up PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch public events for the customer
    $stmt = $pdo->prepare('
        SELECT event_id, title, starttime,endtime, location, date, type
        FROM publiceventbooking
        WHERE customerid = :customerid AND date BETWEEN :start_date AND :end_date
    ');
    $stmt->bindParam(':customerid', $customerID, PDO::PARAM_INT);
    $stmt->bindParam(':start_date', $startDate);
    $stmt->bindParam(':end_date', $endDate);
    $stmt->execute();
    $public_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch private events for the customer
    $stmt = $pdo->prepare('
        SELECT event_id, title, starttime, location, starttime,endtime, date, type
        FROM privateeventbooking      
        WHERE customerid = :customerid AND date BETWEEN :start_date AND :end_date
    ');
    $stmt->bindParam(':customerid', $customerID, PDO::PARAM_INT);
    $stmt->bindParam(':start_date', $startDate);
    $stmt->bindParam(':end_date', $endDate);
    $stmt->execute();
    $private_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Combine results
    $events = array_merge($public_events, $private_events);

    echo json_encode($events);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    http_response_code(500);
}
?>
