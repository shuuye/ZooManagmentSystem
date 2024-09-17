<?php    
    require_once __DIR__ . '/../../Config/databaseConfig.php';

    class CustomerModel extends databaseConfig {
        protected $db;

        public function __construct() {
            //establish database connection
            $this->db = $this->getConnection();
        }
        
        public function isExistInCustomerDB($value, $columnName) {
            // Prepare the statement with the validated column name
            $query = "SELECT COUNT(*) FROM customer WHERE $columnName = :value";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':value', $value);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            // Debugging: Check the query and result
            if ($count === false) {
                throw new RuntimeException("Query failed: " . htmlspecialchars($stmt->errorInfo()[2]));
            }

            // Return true if the record exists, otherwise false
            return $count > 0;
        }
        
        public function updateDBColumnByID($columnName, $updatingData, $id){
            $updateStmt = $this->db->prepare("UPDATE customer SET $columnName  = :updatingData WHERE id = :id");
            $updateStmt->bindParam(':updatingData', $updatingData);
            $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
            if($updateStmt->execute()){
                return $this->getMembershipIDByID($id);
            }else{
                return false;
            }
        }
        
        public function removeCustomerByID($id) {
            // Ensure the ID is an integer to prevent SQL injection
            $id = (int) $id;

            // Prepare the DELETE query
            $query = "DELETE FROM customer WHERE id = :id";
            $stmt = $this->db->prepare($query);

            // Bind the ID parameter
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the query and check if the operation was successful
            if ($stmt->execute()) {
                // Check if any row was affected (i.e., a customer was deleted)
                if ($stmt->rowCount() > 0) {
                    return true; // Customer was successfully deleted
                } else {
                    return false; // No customer was found with the given ID
                }
            } else {
                // Query execution failed
                throw new RuntimeException("Query failed: " . htmlspecialchars($stmt->errorInfo()[2]));
            }
        }
        
        public function getMembershipIDByID($id){
            // Use the inherited database connection
            $stmt = $this->db->prepare("SELECT membershipID FROM customer WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $membershipID = $stmt->fetchColumn();
            
            return $membershipID ?: null; // Return null if no membershipID is found
            
        }
        
        public function getAllCustomers(){
            $query = "SELECT * FROM customer";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return !empty($customers) ? $customers : null; // Return null if no admins are found
        }
        
        public function addCustomerIntoDB($id, $membershipID = 1){
                        
            $stmt = $this->db->prepare(
                "INSERT INTO customer (id, membershipID) 
                 VALUES (:id, :membershipID)"
            ); 
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':membershipID', $membershipID);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        }
       
    }       
    
?>



