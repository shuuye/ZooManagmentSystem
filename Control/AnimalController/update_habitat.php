<?php
require_once 'HabitatController.php';

$controller = new HabitatController();

$id = $_POST['id'];
$name = $_POST['name'];
$availability = $_POST['availability'];
$capacity = $_POST['capacity'];
$environment = $_POST['environment'];
$description = $_POST['description'];

$controller->editHabitat($id, $name, $availability, $capacity, $environment, $description);

header('Location: ../../View/AnimalModule/habitat_home.php');
exit;
?>