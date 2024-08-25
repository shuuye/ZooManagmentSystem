<?php
// Load the XML file
$xmlDoc = new DOMDocument();
$xmlDoc->load("Inventory.xml");

$records = $xmlDoc->getElementsByTagName('record');

foreach ($records as $record) {
    $itemName = $record->getElementsByTagName('itemName')->item(0)->nodeValue;
    echo "Item Name: " . $itemName . "<br>";
}

$xpath = new DOMXPath($xmlDoc);
$result = $xpath->query("//record[itemType='Animal']");
echo "<br><br> Here is the inventory for animal only";
foreach ($result as $node) {
    $itemNameNode = $node->getElementsByTagName('itemName');
    if ($itemNameNode->length > 0) {
        $itemName = $itemNameNode->item(0)->nodeValue;
        echo "Item Name: $itemName" . "<br>";
    }
}

