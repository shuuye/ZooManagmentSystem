<?php
include_once 'C:\xampp\htdocs\ZooManagementSystem\Config\databaseConfig.php';
class Supplier extends databaseConfig{

    private $db;

    public function __construct() {
        // Initialize database connection
        $this->db = new databaseConfig();
    }

    public function getSupplierRecordById($supplierId) {
        try {
            // Get database connection
            $conn = $this->db->getConnection();

            // Prepare SQL query to get supplier record by supplierId
            $sql = "SELECT * FROM supplier WHERE supplierId = :supplierId";

            // Prepare the statement
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':supplierId', $supplierId, PDO::PARAM_INT);

            // Execute the statement
            $stmt->execute();

            // Fetch the result
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result; // Return the supplier record as an associative array
            } else {
                return null; // No record found for the given supplierId
            }
        } catch (PDOException $e) {
            // Handle database error
            echo 'Database error: ' . $e->getMessage();
            return null;
        }
    }
}

$suu = new Supplier();
$result = $suu->getSupplierRecordById(5);
print_r($result);