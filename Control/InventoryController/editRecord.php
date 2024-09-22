<?php
/*Author name: Lim Shuye*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve common fields from the POST request
    $inventoryId = $_POST['inventoryId'];
    $itemId = $_POST['itemId'];
    $itemType = $_POST['itemType'];

    // Collect common data into an associative array
    $data = [
        'inventoryId' => $inventoryId,
        'itemId' => $itemId,
    ];

    // Conditional fields based on item type
    switch ($itemType) {
        case 'Cleaning':
            $data['brandName'] = $_POST['brandName'];
            $data['size'] = $_POST['size'];
            $data['instruction'] = $_POST['instruction'];
            break;

        case 'Food':
            $data['brandName'] = $_POST['brandName'];
            $data['nutritionInfo'] = $_POST['nutritionInfo'];
            $data['quantity'] = $_POST['quantity'];
            break;

        case 'Habitat':
            $data['brandName'] = $_POST['brandName'];
            $data['description'] = $_POST['description'];
            $data['habitatType'] = $_POST['habitatType'];
            $data['material'] = $_POST['material'];
            $data['lifeTime'] = $_POST['lifeTime'];
            $data['installationInstru'] = $_POST['installationInstru'];
            $data['disposalInstru'] = $_POST['disposalInstru'];
            break;

        default:
            echo json_encode(['error' => 'Invalid item type']);
            exit;
    }
    print_r($data);
    // Instantiate control classes
    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\Inventory.php';
    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryItemFactory.php';

    // Create the appropriate item
    $inventoryCreater = new InventoryItemFactory();
    $inventory = $inventoryCreater->createItem($inventoryId, $itemType, $itemId, null);

    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryManagement.php';
    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryCommand.php';

    // Execute the edit command
    $inventoryManager = new InventoryManagement();
    $success = $inventoryManager->executeCommand(new editItemRecordCommand($inventory, $data));


    // Redirect back or show a success message
    if ($success) {
        header("Location: index.php?controller=inventory&action=viewItembasedOnInventoryID&status=successEdit&inventoryId=$inventoryId&itemType=$itemType");
    } else {
        header("Location: index.php?controller=inventory&action=viewItembasedOnInventoryID&status=errorEdit&inventoryId=$inventoryId&itemType=$itemType");
    }
    exit;
}
?>

