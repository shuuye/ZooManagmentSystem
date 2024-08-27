<?php

require_once '../Config/databaseConfig.php';

$database = new databaseConfig();
$db = $database->getConnection();

header('Content-Type: text/xml');

$dom = new DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = true;

$root = $dom->createElement('database');
$dom->appendChild($root);

try {
    $query = "SELECT * FROM Inventory";
    $result = $db->prepare($query);
    $result->execute();

    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $record = $dom->createElement('record');

            $inventoryId = $dom->createElement('inventoryId', $row["inventoryId"]);
            $record->appendChild($inventoryId);

            $itemName = $dom->createElement('itemName', $row["itemName"]);
            $record->appendChild($itemName);

            $itemType = $dom->createElement('itemType', $row["itemType"]);
            $record->appendChild($itemType);

            $supplierId = $dom->createElement('supplierId', $row["supplierId"]);
            $record->appendChild($supplierId);

            $storageLocation = $dom->createElement('storageLocation', $row["storageLocation"]);
            $record->appendChild($storageLocation);

            $reorderThreshold = $dom->createElement('reorderThreshold', $row["reorderThreshold"]);
            $record->appendChild($reorderThreshold);

            $root->appendChild($record);
        }
    } else {
        $message = $dom->createElement('message', '0 results');
        $root->appendChild($message);
    }
    $dom->save('inventory.xml');
    echo $dom->saveXML();
} catch (PDOException $exception) {
    $_SESSION['error_message'] = "Error: " . $exception->getMessage();
    header("Location: ../View/userLoginPage.php");
    exit();
}
?>
