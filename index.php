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
/*
// Initialize the application
require_once 'Config/databaseConfig.php';
require_once 'Control/InventoryController/InventoryController.php';
require_once __DIR__ . '/Control/UserManagementModule/UserController.php';

// Define routes
$routes = array(
    '/Control/Inventory/Controller/InventoryController' => 'InventoryController@indexaction',
   
);

// Get the requested URL
$url = $_SERVER['REQUEST_URI'];

// Parse the URL and determine the controller and action
foreach ($routes as $route => $controllerAction) {
    if (preg_match("#^$route#i", $url)) {
        list($controller, $action) = explode('@', $controllerAction);
        break;
    }else{
        $controller = 'InventoryController';
        $action = 'indexaction';
    }
}

// Execute the controller and action
if (isset($controller) && isset($action)) {
    $controllerInstance = new $controller();
    $controllerInstance->$action();
} else {
    // 404 Not Found
    header('HTTP/1.0 404 Not Found');
    exit;
}*/
