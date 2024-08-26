<?php
require_once '../../Control/AnimalController/HabitatController.php';
require_once 'habitat_manage.php';

// Initialize the controller
$controller = new HabitatController();

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $availability = $_POST['availability'];
    $capacity = $_POST['capacity'];
    $environment = $_POST['environment'];
    $description = $_POST['description'];

    // Add the new habitat
    $controller->addHabitat($name, $availability,$capacity, $environment, $description);

    // Redirect back to the habitat list or success page
    header("Location: habitat_home.php");
    exit();
}
?>
