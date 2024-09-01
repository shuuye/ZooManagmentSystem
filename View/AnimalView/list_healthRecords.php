<?php
// Include the HealthRecordController
include_once '../../Control/AnimalControllerN/HealthController.php';

// Create an instance of HealthRecordController
$controller = new HealthController();

// Call the method to display health records
$controller->displayHealthRecords();
?>
