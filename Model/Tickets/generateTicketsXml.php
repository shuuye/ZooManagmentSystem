<?php

require_once '../XmlGenerator.php';

$xmlGenerator = new XmlGenerator();

// Parameters: tableName, outputFileName, rootElementName, elementsName, attributeForFirstElement
$xmlGenerator->createXMLFileByTableName("tickets", "tickets.xml", "Tickets", "Ticket", "ticket_id");

?>
