<?php

include_once '../../Config/databaseConfig.php';

//include_once '../Command/AnimalInventory.php';

class InventoryModel extends databaseConfig {

    private $db;

    public function __construct() {
        $this->db = new databaseConfig();
    }

    protected function getInventory($offset, $recordsPerPage) {
        $query = "SELECT * FROM Inventory LIMIT :offset, :recordsPerPage";
        $result = $this->db->getConnection()->prepare($query);
        $result->bindParam(':offset', $offset, PDO::PARAM_INT);
        $result->bindParam(':recordsPerPage', $recordsPerPage, PDO::PARAM_INT);
        $result->execute();
        $data = $result->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    protected function getTotalRecords() {
        $query = "SELECT COUNT(*) AS totalRecords FROM Inventory";
        $result = $this->db->getConnection()->prepare($query);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC)['totalRecords'];
    }

    public function getObjectById($inventoryId) {
        $this->db = new databaseConfig();
        $query = "SELECT * FROM Inventory WHERE inventoryId = ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute([$inventoryId]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($record) {
            // Determine which subclass to instantiate based on itemType
            switch ($record['itemType']) {
                case 'Animal':
                    return $this->getAnimalInventory($inventoryId, $record);
                case 'Habitat':
                    return $this->getHabitatInventory($inventoryId, $record);
                case 'Cleaning':
                    return $this->getCleaningInventory($inventoryId, $record);
                case 'Food':
                    return $this->getFoodInventory($inventoryId, $record);
                default:
                    return null; // Handle cases where no subclass matches
            }
        } else {
            return null; // No matching record found
        }
    }

    private function getAnimalInventory($inventoryId, $record) {
        $query = "SELECT * FROM AnimalInventory WHERE inventoryId = ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute([$inventoryId]);
        $animalRecord = $stmt->fetch(PDO::FETCH_ASSOC);

        return new AnimalInventory(
                $record['itemName'], $record['itemType'], $record['storageLocation'],
                $record['reorderThreshold'], $animalRecord['name'], $animalRecord['species'], $animalRecord['subspecies'],
                $animalRecord['categories'], $animalRecord['age'], $animalRecord['gender'], $animalRecord['date_of_birth'],
                $animalRecord['avg_lifespan'], $animalRecord['description'], $animalRecord['height'], $animalRecord['weight'],
                $animalRecord['healthStatus'], $animalRecord['habitat_id']
        );
    }

    private function getHabitatInventory($inventoryId, $record) {
        $query = "SELECT * FROM HabitatInventory WHERE inventoryId = ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute([$inventoryId]);
        $habitatRecord = $stmt->fetch(PDO::FETCH_ASSOC);

        return new HabitatInventory(
                $record['itemName'], $record['itemType'], $record['storageLocation'],
                $record['reorderThreshold'], $habitatRecord['habitatItemName'], $habitatRecord['description'],
                $habitatRecord['habitatType'], $habitatRecord['material'], $habitatRecord['expected_lifetime'],
                $habitatRecord['installation_instructions'], $habitatRecord['disposal_instructions']
        );
    }

    private function getCleaningInventory($inventoryId, $record) {
        $query = "SELECT * FROM CleaningInventory WHERE inventoryId = ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute([$inventoryId]);
        $cleaningRecord = $stmt->fetch(PDO::FETCH_ASSOC);

        return new CleaningInventory(
                $record['itemName'], $record['itemType'], $record['storageLocation'],
                $record['reorderThreshold'], $cleaningRecord['cleaningName'], $cleaningRecord['size'],
                $cleaningRecord['usageInstructions']
        );
    }

    private function getFoodInventory($inventoryId, $record) {
        $query = "SELECT * FROM FoodInventory WHERE inventoryId = ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute([$inventoryId]);
        $foodRecord = $stmt->fetch(PDO::FETCH_ASSOC);

        return new FoodInventory(
                $record['itemName'], $record['itemType'], $record['storageLocation'],
                $record['reorderThreshold'], $foodRecord['foodName'], $foodRecord['nutritionInfo'],
                $foodRecord['daily_quantity_required']
        );
    }

    public function getAnimalItemNames() { // Pam add ver
        $query = "SELECT itemName FROM inventory WHERE itemType = 'Animal'";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    protected function getInventoryIdByName($itemName) { //Pam ver
        $this->db = new databaseConfig();
        // Prepare the SQL query to fetch the inventory ID based on itemName
        $query = "SELECT inventoryId FROM Inventory WHERE itemName = :itemName";
        $stmt = $this->db->getConnection()->prepare($query);

        // Bind the itemName parameter
        $stmt->bindParam(':itemName', $itemName, PDO::PARAM_STR);

        // Execute the query
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the inventoryId was found
        if ($result) {
            return $result['inventoryId'];
        } else {
            return null; // or handle the case where no matching record is found
        }
    }

    protected function getAllRecordsByType($itemType) {
        $this->db = new databaseConfig();
        // Prepare the SQL query to fetch all records based on itemType
        $query = "SELECT * FROM Inventory WHERE itemType = ?";
        $stmt = $this->db->getConnection()->prepare($query);

        // Execute the query with the provided itemType
        $stmt->execute([$itemType]);

        // Fetch all results as an associative array
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results; // Return the array of results
    }

    protected function getLatestPOID() {
        try {
            // Initialize database connection
            $this->db = new databaseConfig();

            // Query to get the latest purchaseOrderId from the purchaseorder table
            $query = "SELECT poID FROM purchaseorder ORDER BY poID DESC LIMIT 1;";

            // Prepare and execute the query
            $stmt = $this->db->getConnection()->prepare($query);
            $stmt->execute();

            // Fetch the result (latest purchaseOrderId)
            $latestPOID = $stmt->fetchColumn();

            return $latestPOID;
        } catch (PDOException $e) {
            // Handle any errors
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    protected function getLatestPOLineID() {
        try {
            // Initialize database connection
            $this->db = new databaseConfig();

            // Query to get the latest purchaseOrderId from the purchaseorder table
            $query = "SELECT poID FROM purchaseorderlineitem ORDER BY poLineItemId DESC LIMIT 1;";

            // Prepare and execute the query
            $stmt = $this->db->getConnection()->prepare($query);
            $stmt->execute();

            // Fetch the result (latest purchaseOrderId)
            $latestPOLineID = $stmt->fetchColumn();

            return $latestPOLineID;
        } catch (PDOException $e) {
            // Handle any errors
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    protected function addInventoryIntoDB($itemName, $itemType, $storageLocation, $reorderThreshold, $quantity) {
        // Create a new database connection object
        $this->db = new databaseConfig();

        $query = "INSERT INTO Inventory (itemName, itemType, storageLocation, reorderThreshold, quantity) VALUES "
                . "(?,?,?,?,?)";
        $result = $this->db->getConnection()->prepare($query);

        if (!$result->execute(array($itemName, $itemType, $storageLocation, $reorderThreshold, $quantity))) {
            $result = null;
            exit();
        }
        $lastInsertId = $this->db->getConnection()->lastInsertId();
        $result = null;
        $this->updateXML();

        return $lastInsertId;
    }

    protected function removeInventoryIntoDB() {
        // Create a new database connection object

        $database = new databaseConfig();
        $this->db = $database->getConnection();

//        $query = "INSERT INTO Inventory (itemName, itemType, supplierId, storageLocation, reorderThreshold) VALUES "
//                . "(?,?,?,?,?)";
//        $result = $this->db->prepare($query);
//
//        if (!$result->execute(array($itemName, $itemType, $supplierId, $storageLocation, $reorderThreshold))) {
//            $result = null;
////            header("location: ../../View/InventoryView/index2.php");
//            exit();
//        }
//        $result = null;
    }

    protected function getItemNameById($itemId, $itemType) {
        // Ensure that the itemType is valid
        $validItemTypes = ['Food', 'Habitat', 'Cleaning'];
        if (!in_array($itemType, $validItemTypes)) {
            return ['error' => 'Invalid item type'];
        }

        // Define the table and field names based on the item type
        $table = '';
        $nameField = '';

        switch ($itemType) {
            case 'Food':
                $table = 'foodinventory'; // Replace with your actual table name for food items
                $nameField = 'foodName'; // Replace with your actual field name for food names
                break;
            case 'Habitat':
                $table = 'habitatinventory'; // Replace with your actual table name for habitat items
                $nameField = 'habitatItemName'; // Replace with your actual field name for habitat item names
                break;
            case 'Cleaning':
                $table = 'cleaninginventory'; // Replace with your actual table name for cleaning items
                $nameField = 'cleaningName'; // Replace with your actual field name for cleaning item names
                break;
        }

        try {
            // Prepare the query
            $query = "SELECT $nameField FROM $table WHERE id = ?";
            $stmt = $this->db->getConnection()->prepare($query);

            if (!$stmt) {
                throw new Exception('Query preparation failed: ' . implode(' ', $this->db->getConnection()->errorInfo()));
            }

            // Execute the query
            if (!$stmt->execute([$itemId])) {
                throw new Exception('Query execution failed: ' . implode(' ', $stmt->errorInfo()));
            }

            // Fetch the result
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result === false) {
                return ['error' => 'No item found with the provided ID'];
            }

            return $result[$nameField];
        } catch (Exception $e) {
            // Log the error message and return a user-friendly message
            error_log($e->getMessage());
            return ['error' => 'An error occurred: ' . $e->getMessage()];
        }
    }

    protected function getSupplyUnitPrice($itemId, $itemType) {
        // Determine the correct column name based on itemType
        switch ($itemType) {
            case 'Cleaning':
                $column = 'cleaningId';
                break;
            case 'Habitat':
                $column = 'habitatId';
                break;
            case 'Food':
                $column = 'foodId';
                break;
            default:
                throw new Exception('Invalid item type');
        }

        // SQL query to get the supplyUnitPrice based on itemId and itemType
        $query = "SELECT supplyUnitPrice FROM supplierRecord WHERE $column = ?";
        $result = $this->db->getConnection()->prepare($query);

        if (!$result->execute([$itemId])) {
            $result = null;
            exit();
        }

        // Fetch the supplyUnitPrice from the result
        return $result->fetchAll(PDO::FETCH_COLUMN);
    }

    protected function getSupplierIdBasedOnItemId($itemId, $itemType) {
        // Determine the appropriate column to query based on itemType
        $itemColumn = '';
        switch ($itemType) {
            case 'Cleaning':
                $itemColumn = 'cleaningId';
                break;
            case 'Habitat':
                $itemColumn = 'habitatId';
                break;
            case 'Food':
                $itemColumn = 'foodId';
                break;
            default:
                throw new InvalidArgumentException('Invalid item type provided.');
        }

        // Prepare and execute the query to get supplierIds
        $query = "SELECT supplierId FROM supplierRecord WHERE $itemColumn = ?";
        $result = $this->db->getConnection()->prepare($query);

        if (!$result->execute([$itemId])) {
            $result = null;
            exit();
        }

        // Fetch all supplier IDs
        return $result->fetchAll(PDO::FETCH_COLUMN);
    }

    // Method to get Supplier object based on supplierId
    protected function getSupplierDetailsById($supplierId) {
        $query = "SELECT * FROM Supplier WHERE supplierId = ?";
        $result = $this->db->getConnection()->prepare($query);

        if (!$result->execute([$supplierId])) {
            $result = null;
            exit();
        }

        $supplierData = $result->fetch(PDO::FETCH_ASSOC);

        return $supplierData;
    }

    protected function updateXML() {//if want more can add $table and use it to determine which xml to update
        require_once 'C:\xampp\htdocs\ZooManagementSystem\Model\XmlGenerator.php';
        $xmlGenerator = new XmlGenerator();
        $xmlGenerator->createXMLFileByTableName("inventory", "inventory.xml", "inventories", "inventory");
        $xmlGenerator->createXMLFileByTableName("cleaninginventory", "cleaninginventory.xml", "cleaninginventories", "cleaninginventory");
        $xmlGenerator->createXMLFileByTableName("foodinventory", "foodinventory.xml", "foodinventories", "foodinventory");
        $xmlGenerator->createXMLFileByTableName("habitatinventory", "habitatinventory.xml", "habitatinventories", "habitatinventory");
        $xmlGenerator->createXMLFileByTableName("purchaseorder", "purchaseorder.xml", "purchaseorders", "purchaseorder");
        $xmlGenerator->createXMLFileByTableName("purchaseorderlineitem", "purchaseorderlineitem.xml", "purchaseorderlineitems", "purchaseorderlineitem");
        $xmlGenerator->createXMLFileByTableName("supplier", "supplier.xml", "suppliers", "supplier");
        $xmlGenerator->createXMLFileByTableName("batch", "batch.xml", "batchs", "batch");
        $xmlGenerator->createXMLFileByTableName("item_image", "itemimage.xml", "itemimages", "itemimage");
        $xmlGenerator->createXMLFileByTableName("supplierRecord", "supplierRecord.xml", "supplierRecords", "supplierRecord");
    }

    protected function addPOIntoDB($supplierId, $orderDate, $deliveryDate, $billingAddress, $ShippingAddress, $totalAmount, $status) {
        // Create a new database connection object
        $this->db = new databaseConfig();

        $query = "INSERT INTO purchaseorder (supplierId, orderDate, deliveryDate, billingAddress,shippingAddress, totalAmount, status) VALUES "
                . "(?, ?, ?, ?, ?, ?, ?)";
        $result = $this->db->getConnection()->prepare($query);

        if ($result->execute(array($supplierId, $orderDate, $deliveryDate, $billingAddress, $ShippingAddress, $totalAmount, $status))) {

            $this->updateXML();

            return $this->getLatestPOID();
        } else {
            return null;
        }
    }

    protected function addPOLineIntoDB($poId, $inventoryId, $cleaningId, $habitatId, $foodId, $quantity, $unitPrice) {
        // Create a new database connection object
        $this->db = new databaseConfig();

        $query = "INSERT INTO purchaseorderlineitem (poId, inventoryId, cleaningId, habitatId, foodId, quantity, unitPrice) VALUES "
                . "(?, ?, ?, ?, ?, ?, ?)";
        $result = $this->db->getConnection()->prepare($query);

        if (!$result->execute(array($poId, $inventoryId, $cleaningId, $habitatId, $foodId, $quantity, $unitPrice))) {
            $result = null;
            exit();
        }
        $lastInsertId = $this->getLatestPOLineID();
        echo "Last Insert ID: " . $lastInsertId; // Add this line for debugging
        $result = null;
        $this->updateXML();
        return $lastInsertId;
    }

    protected function removePOfromDB($poId) {
        try {
            // Create a new database connection object
            $this->db = new databaseConfig();

            $query = "DELETE FROM PurchaseOrder WHERE poId = ?";
            $stmt = $this->db->getConnection()->prepare($query);

            // Execute the query
            $stmt->execute([$poId]);
            $this->updateXML();
            return $stmt->rowCount() > 0; // Returns true if a row was deleted
        } catch (PDOException $e) {
            // Log the error or handle it accordingly
            echo 'Database error: ' . $e->getMessage();
            return false;
        }
    }

    protected function updatePOStatusDB($poId, $status) {
        try {
            // Create a new database connection object
            $this->db = new databaseConfig();

            $query = "UPDATE purchaseorder
                    SET status = ?
                    WHERE poId = ?";
            $stmt = $this->db->getConnection()->prepare($query);

            // Execute the query
            $stmt->execute([$status, $poId]);
            $this->updateXML();
            return $stmt->rowCount() > 0; // Returns true if a row was deleted
        } catch (PDOException $e) {
            // Log the error or handle it accordingly
            echo 'Database error: ' . $e->getMessage();
            return false;
        }
    }

    protected function updateInventoryQuantityDB($poId) {
        try {
            // Create a new database connection object
            $this->db = new databaseConfig();

            $query = "UPDATE Inventory i
                    JOIN (
                        -- Subquery to get the sum of quantities for each inventory item based on poId
                        SELECT inventoryId, SUM(quantity) AS total_quantity
                        FROM purchaseorderlineitem
                        WHERE poId = ?
                        GROUP BY inventoryId
                    ) pli ON i.inventoryId = pli.inventoryId
                    SET i.quantity = i.quantity + pli.total_quantity;";
            $stmt = $this->db->getConnection()->prepare($query);

            // Execute the query
            $stmt->execute([$poId]);
            $this->updateXML();
            return $stmt->rowCount() > 0; // Returns true if a row was deleted
        } catch (PDOException $e) {
            // Log the error or handle it accordingly
            echo 'Database error: ' . $e->getMessage();
            return false;
        }
    }

    protected function removeRecordFromDB($itemType, $recordId) {
        try {
            // Create a new database connection object
            $this->db = new databaseConfig();

            // Determine the table based on item type
            switch ($itemType) {
                case 'Food':
                    $table = 'foodinventory';
                    break;
                case 'Habitat':
                    $table = 'habitatinventory';
                    break;
                case 'Cleaning':
                    $table = 'cleaninginventory';
                    break;
                default:
                    throw new Exception('Invalid item type');
            }

            // Prepare the SQL query
            $query = "DELETE FROM $table WHERE id = ?";
            $stmt = $this->db->getConnection()->prepare($query);

            // Execute the query with the record ID
            $stmt->execute([$recordId]);

            // Optionally update XML or perform other actions
            $this->updateXML();

            return $stmt->rowCount() > 0; // Returns true if a row was deleted
        } catch (PDOException $e) {
            // Log the error or handle it accordingly
            echo 'Database error: ' . $e->getMessage();
            return false;
        } catch (Exception $e) {
            // Handle other exceptions
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }

    protected function removeInventoryRecordDB($recordId) {
        try {
            // Create a new database connection object
            $this->db = new databaseConfig();

            // Prepare the SQL query
            $query = "DELETE FROM inventory WHERE inventoryId = ?";
            $stmt = $this->db->getConnection()->prepare($query);

            // Execute the query with the record ID
            $stmt->execute([$recordId]);

            // Optionally update XML or perform other actions
            $this->updateXML();

            return $stmt->rowCount() > 0; // Returns true if a row was deleted
        } catch (PDOException $e) {
            // Log the error or handle it accordingly
            echo 'Database error: ' . $e->getMessage();
            return false;
        } catch (Exception $e) {
            // Handle other exceptions
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }

    protected function addRecordInDB($itemType, $data) {
        // Create a new database connection object
        $this->db = new databaseConfig();

        // Initialize variables for SQL and parameters
        $table = '';
        $columns = [];
        $values = [];
        $placeholders = [];

        // Determine the table and prepare columns/values based on item type
        switch ($itemType) {
            case 'Food':
                $table = 'foodinventory';
                $columns = ['inventoryId', 'foodName', 'daily_quantity_required', 'nutritionInfo'];
                $values = [$data['inventoryId'], $data['brandName'], $data['dailyQuatntity'], $data['nutritionInformation']];
                break;

            case 'Habitat':
                $table = 'habitatinventory';
                $columns = ['inventoryId', 'habitatItemName', 'description', 'habitatType', 'material', 'expected_lifetime'
                    , 'installation_instructions', 'disposal_instructions'];
                $values = [$data['inventoryId'], $data['brandName'], $data['description'], $data['habitatType'], $data['material'], $data['lifeTime']
                    , $data['installationInstru'], $data['disposalInstru']];
                break;

            case 'Cleaning':
                $table = 'cleaninginventory';
                $columns = ['inventoryId', 'cleaningName', 'size', 'usageInstructions'];
                $values = [$data['inventoryId'], $data['brandName'], $data['size'], $data['usageInstructions']];
                break;

            default:
                throw new Exception('Invalid item type');
        }

        // Build SQL statement dynamically
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $columnsString = implode(',', $columns);
        $sql = "INSERT INTO $table ($columnsString) VALUES ($placeholders)";

        // Prepare and execute the SQL query
        $stmt = $this->db->getConnection()->prepare($sql);
        $result = $stmt->execute($values);

        $this->updateXML();

        if ($result) {
            $data['id'] = $this->getLastInsertedId($table);

            $SupplierRecord = $this->addSupplierRecordInDB($itemType, $data);

            if (!$SupplierRecord) {
                return null;
            }

            return $data['id'];
        } else {
            return null; // or handle the case where no matching record is found
        }
    }

    protected function addSupplierRecordInDB($itemType, $data) {
        // Create a new database connection object
        $this->db = new databaseConfig();
        $conn = $this->db->getConnection(); // Get the database connection
        // Define SQL and parameters based on item type
        switch ($itemType) {
            case 'Cleaning':
                $sql = "INSERT INTO supplierRecord (supplierId, inventoryId, cleaningId, supplyUnitPrice) VALUES (?, ?, ?, ?)";
                $params = [$data['supplierId'], $data['inventoryId'], $data['id'], $data['price']];
                break;

            case 'Food':
                $sql = "INSERT INTO supplierRecord (supplierId, inventoryId, foodId, supplyUnitPrice) VALUES (?, ?, ?, ?)";
                $params = [$data['supplierId'], $data['inventoryId'], $data['id'], $data['price']];
                break;

            case 'Habitat':
                $sql = "INSERT INTO supplierRecord (supplierId, inventoryId, habitatId, supplyUnitPrice) VALUES (?, ?, ?, ?)";
                $params = [$data['supplierId'], $data['inventoryId'], $data['id'], $data['price']];
                break;

            default:
                throw new Exception('Invalid item type');
        }

        // Prepare and execute the SQL statement
        $stmt = $conn->prepare($sql);
        return $stmt->execute($params);
    }

    protected function getLastInsertedId($table) {
        // Get the last inserted ID
        $sql = "SELECT id FROM $table ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }

    function insertItemImage($cleaningId, $habitatId, $foodId, $imagePath) {
        $sql = "INSERT INTO item_image (cleaningId, habitatId, foodId, image_path) VALUES (?, ?, ?, ?)";
        // Prepare and bind
        $stmt = $this->db->getConnection()->prepare($sql);

        // Execute statement
        $result = $stmt->execute([$cleaningId, $habitatId, $foodId, $imagePath]);

        return $result;
    }

    function getRecordDetailsfromDB($inventoryId, $itemId, $table) {
        $sql = "SELECT * FROM $table WHERE inventoryId=? AND id=?";

        // Prepare the statement
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bind_param('ii', $inventoryId, $itemId);

        // Execute the statement
        $stmt->execute();

        // Fetch the result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $item = $result->fetch_assoc();
            return $item;
        } else {
            return null;
        }
    }

    protected function editItemRecordInDB($itemType, $data) {
        // Create a new database connection object
        $this->db = new databaseConfig();
        $conn = $this->db->getConnection(); // Get the database connection
        // Define SQL and parameters based on item type
        switch ($itemType) {
            case 'Cleaning':
                $sql = "UPDATE cleaninginventory SET 
                        cleaningName = ?, 
                        size = ?, 
                        usageInstructions = ?
                    WHERE inventoryId = ? AND id = ?";
                $params = [
                    $data['brandName'],
                    $data['size'],
                    $data['instruction'],
                    $data['inventoryId'],
                    $data['itemId']
                ];
                break;

            case 'Food':
                $sql = "UPDATE foodinventory SET 
                        foodName = ?, 
                        nutritionInfo = ?, 
                        daily_quantity_required = ?
                    WHERE inventoryId = ? AND id = ?";
                $params = [
                    $data['brandName'],
                    $data['nutritionInfo'],
                    $data['quantity'],
                    $data['inventoryId'],
                    $data['itemId']
                ];
                break;

            case 'Habitat':
                $sql = "UPDATE habitatinventory SET 
                        habitatItemName = ?, 
                        description = ?, 
                        habitatType = ?, 
                        material = ?, 
                        expected_lifetime = ?, 
                        installation_instructions = ?, 
                        disposal_instructions = ?
                    WHERE inventoryId = ? AND id = ?";
                $params = [
                    $data['brandName'],
                    $data['description'],
                    $data['habitatType'],
                    $data['material'],
                    $data['lifeTime'],
                    $data['installationInstru'],
                    $data['disposalInstru'],
                    $data['inventoryId'],
                    $data['itemId']
                ];
                break;

            default:
                throw new Exception('Invalid item type');
        }

        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);

        // Execute the statement
        if ($stmt->execute($params)) {
            return true;
        } else {
            return false;
        }
    }

    protected function editItemInDB($data) {
        // Create a new database connection object
        $this->db = new databaseConfig();
        $conn = $this->db->getConnection(); // Get the database connection
        // Define SQL and parameters based on item type
        $sql = "UPDATE inventory SET 
                        itemName = ?, 
                        itemType = ?, 
                        storageLocation = ?, 
                        reorderThreshold = ?
                    WHERE inventoryId = ?";
        $params = [
            $data['itemName'],
            $data['itemType'],
            $data['storageLocation'],
            $data['reorderThreshold'],
            $data['inventoryId']
        ];

        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);

        // Execute the statement
        if ($stmt->execute($params)) {
            return true;
        } else {
            return false;
        }
    }
}

//
//$new = new InventoryModel();
//$get = $new->getSupplierIdBasedOnItemId("7", "Food");
//$price = $new->getSupplyUnitPrice("7", "Food");
//
//foreach ($get as $supplierId) {
//    echo "Supplier ID: " . $supplierId . "<br>";
//    $details = $new->getSupplierDetailsById($supplierId);
//    if ($details) {
//        $supplierDetails[$supplierId] = $details; // Store details with supplierId as key
//    }
//}
//print_r($price);
//echo $price[0];
//
//$int = 0;
//foreach ($price as $oneRecord) {
//
//    if ($oneRecord) {
//        $Allprice[$get[$int]] = $oneRecord; // Store details with supplierId as key
//        $int++;
//    }
//}
//
//print_r($Allprice);

//foreach ($supplierDetails as $supplierdetail) {
//    if ($supplierdetail) {
//        echo $supplierdetail['supplierId'] . "<br>";
//        echo $supplierdetail['supplierName'] . "<br>";
//    }
//}
//
//echo "hello" . $supplierDetails[$get[0]]['supplierName'];

