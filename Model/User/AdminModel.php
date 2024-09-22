<?php    
    /*Author Name: Chew Wei Seng*/
    require_once __DIR__ . '/../../Config/databaseConfig.php';

    class AdminModel extends databaseConfig {
        protected $db;

        public function __construct() {
            //establish database connection
            $this->db = $this->getConnection();
        }
        
        public function isExistInAdminDB($value, $columnName) {
            // Prepare the statement with the validated column name
            $query = "SELECT COUNT(*) FROM admin WHERE $columnName = :value";
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
            //update the column data with the value passed in when the id is matched
            $updateStmt = $this->db->prepare("UPDATE admin SET $columnName  = :updatingData WHERE id = :id");
            $updateStmt->bindParam(':updatingData', $updatingData);
            $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
            if($updateStmt->execute()){
                return $this->getAdminTypeByID($id);
            }else{
                return false;
            }
        }
        
        public function removeAdminByID($id) {
            // Ensure the ID is an integer to prevent SQL injection
            $id = (int) $id;

            // Prepare the DELETE query
            $query = "DELETE FROM admin WHERE id = :id";
            $stmt = $this->db->prepare($query);

            // Bind the ID parameter
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the query and check if the operation was successful
            if ($stmt->execute()) {
                // Check if any row was affected (i.e., an admin was deleted)
                if ($stmt->rowCount() > 0) {
                    return true; // Admin was successfully deleted
                } else {
                    return false; // No admin was found with the given ID
                }
            } else {
                // Query execution failed
                throw new RuntimeException("Query failed: " . htmlspecialchars($stmt->errorInfo()[2]));
            }
        }
        
        public function getAdminTypeByID($id){
            $query = "SELECT type FROM admin WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $adminType = $stmt->fetchColumn();
            
            return $adminType ?: null; // Return null if no admin type is found
        }
        
        public function getAllAdmins(){
            $query = "SELECT * FROM admin";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return !empty($admins) ? $admins : null; // Return null if no admins are found
        }
               
        public function addAdminIntoDB($id, $type = 'ReadOnlyAdmin'){
            $stmt = $this->db->prepare(
                "INSERT INTO admin (id, type) 
                 VALUES (:id, :type)"
            ); 
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':type', $type);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        }

    }       
    
?>



