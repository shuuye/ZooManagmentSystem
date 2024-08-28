<?php

require_once '../../Xml/createXMLFromDatabase.php';

echo 'start <br>';

$xmlCreater = new createXMLFromDatabase();

$xmlCreater->createXMLFileByTableName("inventory", "../../Xml/inventory.xml", "inventories", "inventory");

  