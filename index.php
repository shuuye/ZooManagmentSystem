<?php
/*
// Initialize the application
require_once 'Config/databaseConfig.php';
require_once 'Control/InventoryController/InventoryController.php';

// Define routes
$routes = array(
    '/Control/Inventory/Controller/InventoryController' => 'InventoryController@indexaction'
   
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