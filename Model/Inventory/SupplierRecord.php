<?php

include_once 'C:\xampp\htdocs\ZooManagementSystem\Config\databaseConfig.php';

class SupplierRecord extends databaseConfig{

    private $db;

    // Constructor to initialize database connection
    public function __construct() {
        $this->db = new databaseConfig();
    }

    // Method to add a new supplier record
    public function addSupplierRecord($supplierId, $inventoryId, $cleaningId = null, $habitatId = null, $foodId = null, $supplyUnitPrice) {
        $query = "INSERT INTO supplierRecord (supplierId, inventoryId, cleaningId, habitatId, foodId, supplyUnitPrice)
                  VALUES (:supplierId, :inventoryId, :cleaningId, :habitatId, :foodId, :supplyUnitPrice)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':supplierId', $supplierId);
        $stmt->bindParam(':inventoryId', $inventoryId);
        $stmt->bindParam(':cleaningId', $cleaningId);
        $stmt->bindParam(':habitatId', $habitatId);
        $stmt->bindParam(':foodId', $foodId);
        $stmt->bindParam(':supplyUnitPrice', $supplyUnitPrice);

        return $stmt->execute();
    }

    public function getFirstBrandWithLowestPrice($inventoryId) {
        try {
            $this->db = new databaseConfig();
            $conn = $this->db->getConnection();

            // Prepare SQL query
            $sql = "
            SELECT sr.supplierId, sr.inventoryId, sr.supplyUnitPrice, sr.cleaningId, sr.habitatId, foodId
            FROM supplierRecord sr
            JOIN (
                SELECT inventoryId, MIN(supplyUnitPrice) AS min_price
                FROM supplierRecord
                WHERE inventoryId = :inventoryId
                GROUP BY inventoryId
            ) sub
            ON sr.inventoryId = sub.inventoryId
            AND sr.supplyUnitPrice = sub.min_price
            ORDER BY sr.supplierId
            LIMIT 1;
        ";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':inventoryId', $inventoryId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            // Handle error
            echo 'Database error: ' . $e->getMessage();
            return null;
        }
    }

    // Method to get supplier records by supplier ID
    public function getSupplierRecordsBySupplierId($supplierId) {
        $query = "SELECT * FROM supplierRecord WHERE supplierId = :supplierId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':supplierId', $supplierId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to delete a supplier record by ID
    public function deleteSupplierRecord($supplierId, $inventoryId) {
        $query = "DELETE FROM supplierRecord WHERE supplierId = :supplierId AND inventoryId = :inventoryId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':supplierId', $supplierId);
        $stmt->bindParam(':inventoryId', $inventoryId);

        return $stmt->execute();
    }

    // Method to update supplier record
    public function updateSupplierRecord($supplierId, $inventoryId, $cleaningId, $habitatId, $foodId, $supplyUnitPrice) {
        $query = "UPDATE supplierRecord
                  SET cleaningId = :cleaningId, habitatId = :habitatId, foodId = :foodId, supplyUnitPrice = :supplyUnitPrice
                  WHERE supplierId = :supplierId AND inventoryId = :inventoryId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':supplierId', $supplierId);
        $stmt->bindParam(':inventoryId', $inventoryId);
        $stmt->bindParam(':cleaningId', $cleaningId);
        $stmt->bindParam(':habitatId', $habitatId);
        $stmt->bindParam(':foodId', $foodId);
        $stmt->bindParam(':supplyUnitPrice', $supplyUnitPrice);

        return $stmt->execute();
    }
}

//$suu = new SupplierRecord();
//$result = $suu->getFirstBrandWithLowestPrice(26);
//print_r($result);
