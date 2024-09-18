<?php

// Routing: Both category and gender in the same request
require_once '../../Control/AnimalControllerN/CategoryController.php';

$controller = new CategoryController();

$categoryData = $controller->getCategoryCounts();

?>
