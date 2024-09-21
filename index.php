<?php

require_once __DIR__ . '/Control/UserController/UserController.php';

$controller = isset($_GET['controller']) ? $_GET['controller'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : null;

if ($controller && $action) {
    switch ($controller) {
        case 'user':
            $controller = new UserController();
            break;
        case 'admin':
            require_once __DIR__ . '/Control/AdminPanelController.php';
            $controller = new AdminPanelController();
            break;
        case 'inventory':
            require_once __DIR__ . '/Model/Inventory/InventoryModel.php';
            require_once __DIR__ . '/Control/InventoryController/InventoryController.php';
            $model = new InventoryModel();
            $controller = new InventoryController($model, new InventoryView('InventoryMasterPage.php'));
            break;
         case 'animal':
            require_once __DIR__ . '/Control/AnimalControllerN/AnimalController.php';
            break;
        // Add more controllers as needed
        default:
            // Handle unknown controller
            echo "Unknown controller";
            exit;
    }
    $controller->route();
} else {
    // If no controller or action is set, display a default view (like home page)
    include 'View/home.php';
}
