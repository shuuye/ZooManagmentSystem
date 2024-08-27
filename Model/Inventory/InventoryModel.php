<?php

include_once '../../Config/databaseConfig.php';

class InventoryModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    protected function getInventory($offset, $recordsPerPage) {
        $query = "SELECT * FROM Inventory LIMIT :offset, :recordsPerPage";
        $result = $this->db->prepare($query);
        $result->bindParam(':offset', $offset, PDO::PARAM_INT);
        $result->bindParam(':recordsPerPage', $recordsPerPage, PDO::PARAM_INT);
        $result->execute();
        $data = $result->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    protected function getTotalRecords() {
        $query = "SELECT COUNT(*) AS totalRecords FROM Inventory";
        $result = $this->db->prepare($query);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC)['totalRecords'];
    }

    protected function addInventoryIntoDB($itemName, $itemType, $supplierId, $storageLocation, $reorderThreshold) {
        // Create a new database connection object
     
        $database = new databaseConfig();
        $this->db = $database->getConnection();

        $query = "INSERT INTO Inventory (itemName, itemType, supplierId, storageLocation, reorderThreshold) VALUES "
                . "(?,?,?,?,?)";
        $result = $this->db->prepare($query);

        if (!$result->execute(array($itemName, $itemType, $supplierId, $storageLocation, $reorderThreshold))) {
            $result = null;
//            header("location: ../../View/InventoryView/index2.php");
            exit();
        }
        $result = null;
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
