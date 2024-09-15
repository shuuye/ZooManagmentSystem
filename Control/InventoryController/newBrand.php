<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve values from the POST request
    $brandName = isset($_POST['brandName']) ? trim($_POST['brandName']) : '';
    $inventoryId = $_POST['inventoryId'];
    $itemType = $_POST['itemType'];
    $supplierId = $_POST['supplierId'];
    $price = $_POST['price'];
    // Conditional fields based on item type
    $data = ['inventoryId' => $inventoryId, 'brandName' => $brandName, 'supplierId' => $supplierId, 'price' => $price];

    if ($itemType === 'Cleaning') {
        $data['usageInstructions'] = isset($_POST['insturction']) ? trim($_POST['insturction']) : '';
        $data['size'] = isset($_POST['size']) ? trim($_POST['size']) : '';
    } elseif ($itemType === 'Food') {
        $data['dailyQuatntity'] = isset($_POST['quatntity']) ? trim($_POST['quatntity']) : '';
        $data['nutritionInformation'] = isset($_POST['information']) ? trim($_POST['information']) : '';
    } elseif ($itemType === 'Habitat') {
        $data['description'] = isset($_POST['description']) ? trim($_POST['description']) : '';
        $data['habitatType'] = isset($_POST['habitatType']) ? trim($_POST['habitatType']) : '';
        $data['material'] = isset($_POST['material']) ? trim($_POST['material']) : '';
        $data['lifeTime'] = isset($_POST['lifeTime']) ? trim($_POST['lifeTime']) : '';
        $data['installationInstru'] = isset($_POST['installationInstru']) ? trim($_POST['installationInstru']) : '';
        $data['disposalInstru'] = isset($_POST['disposalInstru']) ? trim($_POST['disposalInstru']) : '';
    }

    // Instantiate control class
    include_once '../../Model/Command/Inventory.php';
    include_once '../../Model/Command/InventoryItemFactory.php';

    // Create item
    $inventoryCreater = new InventoryItemFactory();
    $inventory = $inventoryCreater->createItem($inventoryId, $itemType, NULL, Null);

    include_once '../../Model/Command/InventoryManagement.php';
    include_once '../../Model/Command/InventoryCommand.php';

    // Execute command with item data
    $inventoryManager = new InventoryManagement();
    $success = $inventoryManager->executeCommand(new AddItemRecordCommand($inventory, $data));
    
    $itemId = $inventory->getLastRecordID();

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB
        // Validate file type by MIME type
        if (!in_array($file['type'], $allowedTypes)) {
            echo 'Invalid file type.';
        } elseif ($file['size'] > $maxFileSize) {
            echo 'File size exceeds limit.';
        } else {
            // Validate file extension
            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($fileExtension, ['jpg', 'jpeg', 'png'])) {
                echo 'Invalid file extension.';
            } else {
                // Generate a unique file name to prevent overwriting
                $uniqueFileName = strtolower("{$itemType}_{$inventoryId}_{$itemId}.{$fileExtension}");
                $uploadDir = dirname(__DIR__, 2) . '/assests/InventoryImages/';
                $uploadFilePath = $uploadDir . $uniqueFileName;

                // Ensure the upload directory exists and is writable
                if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
                    echo 'Upload directory is not writable or does not exist.';
                } else {
                    // Save the file securely
                    if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                        // Save file information in the database
                        $imagePath = '/ZooManagementSystem/assests/InventoryImages/' . $uniqueFileName;
                        if ($success) {
                            $inventory->updateImage($uniqueFileName, $itemId);
                            echo "Animal and image added successfully.";
                        } else {
                            echo "Image uploaded, but animal could not be added.";
                        }
                    } else {
                        echo 'Failed to upload image.';
                    }
                }
            }
        }
    } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        echo 'No file uploaded or upload error.';
    }

    // Redirect with status message
    if ($success) {
        header("Location: ../../Control/InventoryController/index.php?action=viewItembasedOnInventoryID&status=success&inventoryId=$inventoryId&itemType=$itemType");
    } else {
        header("Location: ../../Control/InventoryController/index.php?action=viewItembasedOnInventoryID&status=error&inventoryId=$inventoryId&itemType=$itemType");
    }
    exit();
}
?>
