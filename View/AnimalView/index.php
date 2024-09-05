<?php
// Initialize the application
require_once '../../Control/AnimalControllerN/AnimalController.php';  // Include AnimalController

// Define routes
$routes = array(
    '/Control/AnimalControllerN' => 'AnimalController@route',  // Add a route for AnimalController
);

// Get the requested URL
$url = $_SERVER['REQUEST_URI'];

// Parse the URL and determine the controller and action
$controller = null;
$action = null;

foreach ($routes as $route => $controllerAction) {
    if (preg_match("#^$route#i", $url)) {
        list($controller, $action) = explode('@', $controllerAction);
        break;
    }
}

// Default to InventoryController and indexaction if no route matched
if (!$controller) {
    $controller = 'AnimalController';
    $action = 'route';
}

// Execute the controller and action
if (class_exists($controller) && method_exists($controller, $action)) {
    $controllerInstance = new $controller();
    $controllerInstance->$action();
} else {
    // 404 Not Found
    header('HTTP/1.0 404 Not Found');
    echo "404 Not Found";
    exit;
}
