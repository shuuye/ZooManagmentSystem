<?php    
    /*Author Name: Chew Wei Seng*/
    require_once __DIR__ . '/../../Config/databaseConfig.php';

    class StaffModel extends databaseConfig {
        protected $db;

        public function __construct() {
            //establish database connection
            $this->db = $this->getConnection();
        }
        
        public function isExistInStaffDB($value, $columnName) {
            // Prepare the statement with the validated column name
            $query = "SELECT COUNT(*) FROM staff WHERE $columnName = :value";
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
            $updateStmt = $this->db->prepare("UPDATE staff SET $columnName  = :updatingData WHERE id = :id");
            $updateStmt->bindParam(':updatingData', $updatingData);
            $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
            if($updateStmt->execute()){
                return $this->getPositionByID($id);
            }else{
                return false;
            }
        }
        
        public function removeStaffByID($id) {
            // Ensure the ID is an integer to prevent SQL injection
            $id = (int) $id;

            // Prepare the DELETE query
            $query = "DELETE FROM staff WHERE id = :id";
            $stmt = $this->db->prepare($query);

            // Bind the ID parameter
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the query and check if the operation was successful
            if ($stmt->execute()) {
                // Check if any row was affected (i.e., a staff was deleted)
                if ($stmt->rowCount() > 0) {
                    return true; // Customer was successfully deleted
                } else {
                    return false; // No staff was found with the given ID
                }
            } else {
                // Query execution failed
                throw new RuntimeException("Query failed: " . htmlspecialchars($stmt->errorInfo()[2]));
            }
        }
        
        public function getPositionByID($id){
            // Use the inherited database connection
            $stmt = $this->db->prepare("SELECT position FROM staff WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $position = $stmt->fetchColumn();
            
            return $position ?: null; // Return null if no position is found
            
        }
        
        public function getAllStaff(){
            $query = "SELECT * FROM staff";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return !empty($staff) ? $staff : null; // Return null if no staff are found
        }
        
        public function addStaffIntoDB($id, $position = 'General Staff'){
            //add staff details to the staff table        
            $stmt = $this->db->prepare(
                "INSERT INTO staff (id, position) 
                 VALUES (:id, :position)"
            ); 
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':position', $position);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        }
       
    }       
    
?>



