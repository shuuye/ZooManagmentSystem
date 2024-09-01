<?php


require_once '../../Control/AnimalControllerN/AnimalController.php';

$controller = new AnimalController();

$action = isset($_GET['action']) ? $_GET['action'] : 'showForm';

switch ($action) {
    case 'showForm':
        $controller->showForm();
        break;
    case 'processForm':
        $controller->processForm();
        break;
    default:
        echo "Invalid action.";
        break;
}

