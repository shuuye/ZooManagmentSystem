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
            // Determine which subclass to instantiate based on itemType or another attribute
            switch ($record['itemType']) {
                case 'animal':
                    // Create and return an AnimalInventory object
                    return new AnimalInventory(
                            $record['itemName'],$record['itemType'],$record['supplierId'],$record['storageLocation'],
                            $record['reorderThreshold'],$record['name'],$record['species'],$record['subspecies'],$record['categories'],
                            $record['age'],$record['gender'],$record['date_of_birth'],$record['avg_lifespan'],$record['description'],
                            $record['height'],$record['weight'],$record['healthStatus'],$record['habitatid']
                    );
                // Add more cases for other subclasses as needed
                default:
                    // Handle cases where no subclass matches (optional)
                    return null;
            }
        } else {
            return null; // No matching record found
        }
    }

    protected function getInventoryIdByName($itemName) {
        $this->db = new databaseConfig();
        // Prepare the SQL query to fetch the inventory ID based on itemType
        $query = "SELECT inventoryId FROM Inventory WHERE itemType = ?";
        $stmt = $this->db->getConnection()->prepare($query);

        // Execute the query with the provided itemType
        $stmt->execute([$itemName]);

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

    protected function addInventoryIntoDB($itemName, $itemType, $supplierId, $storageLocation, $reorderThreshold, $quantity) {
        // Create a new database connection object
        $this->db = new databaseConfig();

        $query = "INSERT INTO Inventory (itemName, itemType, supplierId, storageLocation, reorderThreshold, quantity) VALUES "
                . "(?,?,?,?,?,?)";
        $result = $this->db->getConnection()->prepare($query);

        if (!$result->execute(array($itemName, $itemType, $supplierId, $storageLocation, $reorderThreshold, $quantity))) {
            $result = null;
            exit();
        }
        $lastInsertId = $this->db->getConnection()->lastInsertId();
        $result = null;

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
}
