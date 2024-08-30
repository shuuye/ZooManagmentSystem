<?php


require_once '../../Model/Inventory/InventoryModel.php';
require_once 'InventoryController.php';
//require_once '../../View/InventoryView/InventoryView.php';

// Create a new inventory model object
$model = new InventoryModel();

// Create a new inventory controller object
$controller = new InventoryController($model, new InventoryView('InventoryMasterPage.php'));

// Run the controller's routing method
$controller->route();
