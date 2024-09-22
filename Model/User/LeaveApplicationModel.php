<?php    
    /*Author Name: Chew Wei Seng*/
    require_once __DIR__ . '/../../Config/databaseConfig.php';

    class LeaveApplicationModel extends databaseConfig {
        protected $db;

        public function __construct() {
            //establish database connection
            $this->db = $this->getConnection();
        }
        
        public function isLeaveExistInLeaveApplicationDB($id, $leaveDate, $leaveStartTime, $leaveEndTime) {
            // Prepare the statement with the validated column name
            $query = "SELECT COUNT(*) FROM leaveapplication 
                        WHERE id = :id
                        AND leaveDate = :leaveDate
                        AND leaveStartTime = :leaveStartTime
                        AND leaveEndTime = :leaveEndTime";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':leaveDate', $leaveDate, PDO::PARAM_STR);
            $stmt->bindParam(':leaveStartTime', $leaveStartTime, PDO::PARAM_STR);
            $stmt->bindParam(':leaveEndTime', $leaveEndTime, PDO::PARAM_STR);
            
            $stmt->execute();
            $count = $stmt->fetchColumn();

            // Debugging: Check the query and result
            if ($count === false) {
                throw new RuntimeException("Query failed: " . htmlspecialchars($stmt->errorInfo()[2]));
            }

            // Return true if the record exists, otherwise false
            return $count > 0;
        }
        
        public function removeLeaveApplicationFromDB($id, $leaveDate, $leaveStartTime, $leaveEndTime) {
            // Prepare the DELETE query
            $query = "DELETE FROM leaveapplication
                        WHERE id = :id
                        AND leaveDate = :leaveDate
                        AND leaveStartTime = :leaveStartTime
                        AND leaveEndTime = :leaveEndTime";
            
            // Bind the ID parameter
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':leaveDate', $leaveDate, PDO::PARAM_STR);
            $stmt->bindParam(':leaveStartTime', $leaveStartTime, PDO::PARAM_STR);
            $stmt->bindParam(':leaveEndTime', $leaveEndTime, PDO::PARAM_STR);

            // Execute the query and check if the operation was successful
            if ($stmt->execute()) {
                // Check if any row was affected (i.e., an leave application was deleted)
                if ($stmt->rowCount() > 0) {
                    return true; // leave application was successfully deleted
                } else {
                    return false; // No leave application was found with the given ID
                }
            } else {
                // Query execution failed
                throw new RuntimeException("Query failed: " . htmlspecialchars($stmt->errorInfo()[2]));
            }
        }
        
        public function updateApprovedStatusFromLeaveApplicationInDB($id, $leaveDate, $leaveStartTime, $leaveEndTime, $approved) {
            // Prepare the DELETE query
            $query = "UPDATE leaveapplication SET approved = :approved
                        WHERE id = :id
                        AND leaveDate = :leaveDate
                        AND leaveStartTime = :leaveStartTime
                        AND leaveEndTime = :leaveEndTime";
            
            // Bind the ID parameter
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':leaveDate', $leaveDate, PDO::PARAM_STR);
            $stmt->bindParam(':leaveStartTime', $leaveStartTime, PDO::PARAM_STR);
            $stmt->bindParam(':leaveEndTime', $leaveEndTime, PDO::PARAM_STR);
            $stmt->bindParam(':approved', $approved, PDO::PARAM_BOOL);
            
            if ($stmt->execute()) {
                return $this->getLeaveApplicationFromDB($id, $leaveDate, $leaveStartTime, $leaveEndTime);
            }
            return false;
        }
        
        public function getLeaveApplicationFromDB($id, $leaveDate, $leaveStartTime, $leaveEndTime){
            $query = "SELECT * FROM leaveapplication 
                        WHERE id = :id
                        AND leaveDate = :leaveDate
                        AND leaveStartTime = :leaveStartTime
                        AND leaveEndTime = :leaveEndTime";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':leaveDate', $leaveDate, PDO::PARAM_STR);
            $stmt->bindParam(':leaveStartTime', $leaveStartTime, PDO::PARAM_STR);
            $stmt->bindParam(':leaveEndTime', $leaveEndTime, PDO::PARAM_STR);
            $stmt->execute();
            
            $leaveApplication = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $leaveApplication ?: null; // Return null if no admin type is found
        }
        
        public function getAllLeaveApplicationFromDB(){
            $query = "SELECT * FROM leaveapplication";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            $leaveApplication = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return !empty($leaveApplication) ? $leaveApplication : null; // Return null if no admins are found
        }
               
        public function addLeaveApplicationIntoDB($id,$reason,$evidencePhoto, $leaveDate, $leaveStartTime, $leaveEndTime){
            
            if($this->isLeaveExistInLeaveApplicationDB($id, $leaveDate, $leaveStartTime, $leaveEndTime)){
                throw new RuntimeException("Record already exists for the given details.");
            }
            
            // SQL query to insert data into the leaveappliccation table
            $query = "INSERT INTO leaveapplication (id, reason, evidencePhoto, leaveDate, leaveStartTime, leaveEndTime, approved)
                      VALUES (:id, :reason, :evidencePhoto, :leaveDate, :leaveStartTime, :leaveEndTime, false)";

            // Prepare and execute the query 
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':reason', $reason, PDO::PARAM_STR);
            $stmt->bindParam(':evidencePhoto', $evidencePhoto, PDO::PARAM_STR);
            $stmt->bindParam(':leaveDate', $leaveDate, PDO::PARAM_STR);
            $stmt->bindParam(':leaveStartTime', $leaveStartTime, PDO::PARAM_STR);
            $stmt->bindParam(':leaveEndTime', $leaveEndTime, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return $this->getLeaveApplicationFromDB($id, $leaveDate, $leaveStartTime, $leaveEndTime);
            }
            return false;
        }

    }       
    
?>



