<?php

// To test run this api
// http://localhost/ZooManagementSystem/public/api/visitor_summary.php?visit_date=2024-09-17

require_once __DIR__ . '/../../Model/Tickets/CustomerTicketModel.php';

header('Content-Type: application/json');

// Check if visit_date is provided
if (!isset($_GET['visit_date']) || empty($_GET['visit_date'])) {
    echo json_encode(['error' => 'Visit date is required.']);
    exit;
}

$visitDate = $_GET['visit_date'];

try {
    $model = new CustomerTicketModel();
    $visitors = $model->getVisitorsByDate($visitDate);

    if (empty($visitors)) {
        echo json_encode(['visit_date' => $visitDate, 'total_visitors' => 0, 'visitors' => []]);
        exit;
    }

    // Aggregate quantities by ticket_id
    $aggregatedVisitors = [];
    $totalVisitors = 0;

    foreach ($visitors as $visitor) {
        $ticketId = $visitor['ticket_id'];
        if (!isset($aggregatedVisitors[$ticketId])) {
            $aggregatedVisitors[$ticketId] = [
                'ticket_id' => $ticketId,
                'userID' => $visitor['userID'],
                'quantity' => 0,
                'visit_date' => $visitor['visit_date'],
                'purchase_date' => $visitor['purchase_date']
            ];
        }
        $aggregatedVisitors[$ticketId]['quantity'] += $visitor['quantity'];
        $totalVisitors += $visitor['quantity'];
    }

    echo json_encode([
        'visit_date' => $visitDate,
        'total_visitors' => $totalVisitors,
        'visitors' => array_values($aggregatedVisitors)
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
}
