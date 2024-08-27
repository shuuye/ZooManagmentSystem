<?php
require_once '../../Model/Factory/FoodInventoryFactory.php';
require_once '../../Model/Factory/HabitatInventoryFactory.php';
require_once '../../Model/Factory/CleaningInventoryFactory.php';
require_once '../../Xml/createXMLFromDatabase.php';

echo 'start <br>';

$xmlCreater = new createXMLFromDatabase();

$xmlCreater->createXMLFileByTableName("inventory", "../../Xml/inventory.xml", "inventories", "inventory");

  