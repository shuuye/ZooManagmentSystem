<?php
session_start();  // Start session at the very beginning

require_once __DIR__ . '/Control/TicketController/PaymentController.php';

$controller = new PaymentController();
$controller->handleRequest();
?>
