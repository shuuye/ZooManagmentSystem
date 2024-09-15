<?php

//Routing (category_statistics.php): Connects the URL to the appropriate controller action.

require_once '../../Control/AnimalControllerN/CategoryController.php';


$controller = new CategoryController();
$controller->getCategoryCounts();
?>
