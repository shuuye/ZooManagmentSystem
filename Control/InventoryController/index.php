<?php

require_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Inventory\InventoryModel.php';
require_once 'C:\xampp\htdocs\ZooManagementSystem\Control\InventoryController\InventoryController.php';
//require_once '../../View/InventoryView/InventoryView.php';
// Create a new inventory model object
$model = new InventoryModel();

// Create a new inventory controller object
$controller = new InventoryController($model, new InventoryView('InventoryMasterPage.php'));

// Run the controller's routing method
$controller->route();
