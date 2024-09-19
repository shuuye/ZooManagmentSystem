<?php
// Allow access from any origin (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once '../../Control/AnimalControllerN/HabitatControllerObserver.php';

$controller = new HabitatControllerObserver();
$habitatData = $controller->displayHabitats();

if ($habitatData) {
    echo json_encode([
        'status' => 'success',
        'data' => $habitatData
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'No data found'
    ]);
}
?>
