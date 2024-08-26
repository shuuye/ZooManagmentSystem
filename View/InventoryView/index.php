<?php

require_once '../../Config/databaseConfig.php';
require_once '../../Model/InventoryModel.php';
require_once '../../Control/InventoryController/InventoryController.php';
require_once 'InventoryView.php';

// Create a new database connection object
$database = new databaseConfig();
$db = $database->getConnection();

// Create a new inventory model object
$model = new InventoryModel($db);

// Create a new inventory controller object
$controller = new InventoryController($model, new InventoryView('InventoryMasterPage.php'));

// Run the controller's routing method
$controller->route();
