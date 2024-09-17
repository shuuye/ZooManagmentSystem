<?php    
    require_once __DIR__ . '/../../Config/databaseConfig.php';

    class WorkingScheduleModel extends databaseConfig {
        protected $db;

        public function __construct() {
            //establish database connection
            $this->db = $this->getConnection();
        }
        
        public function isPrimaryKeyExistInWorkingScheduleDB($id, $workingDate, $workingStartingTime, $workingOffTime) {
            // Prepare the statement with the validated column name
            $query = "SELECT COUNT(*) FROM workingschedule 
                        WHERE id = :id
                        AND workingDate = :workingDate
                        AND workingStartingTime = :workingStartingTime
                        AND workingOffTime = :workingOffTime";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':workingDate', $workingDate, PDO::PARAM_STR);
            $stmt->bindParam(':workingStartingTime', $workingStartingTime, PDO::PARAM_STR);
            $stmt->bindParam(':workingOffTime', $workingOffTime, PDO::PARAM_STR);
            
            $stmt->execute();
            $count = $stmt->fetchColumn();

            // Debugging: Check the query and result
            if ($count === false) {
                throw new RuntimeException("Query failed: " . htmlspecialchars($stmt->errorInfo()[2]));
            }

            // Return true if the record exists, otherwise false
            return $count > 0;
        }
        
        public function removeWorkingScheduleByPrimaryKey($id, $workingDate, $workingStartingTime, $workingOffTime) {
            // Prepare the DELETE query
            $query = "DELETE FROM workingSchedule
                        WHERE id = :id
                        AND workingDate = :workingDate
                        AND workingStartingTime = :workingStartingTime
                        AND workingOffTime = :workingOffTime";
            
            // Bind the ID parameter
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':workingDate', $workingDate, PDO::PARAM_STR);
            $stmt->bindParam(':workingStartingTime', $workingStartingTime, PDO::PARAM_STR);
            $stmt->bindParam(':workingOffTime', $workingOffTime, PDO::PARAM_STR);

            // Execute the query and check if the operation was successful
            if ($stmt->execute()) {
                // Check if any row was affected (i.e., an workingSchedule was deleted)
                if ($stmt->rowCount() > 0) {
                    return true; // workingSchedule was successfully deleted
                } else {
                    return false; // No workingSchedule was found with the given ID
                }
            } else {
                // Query execution failed
                throw new RuntimeException("Query failed: " . htmlspecialchars($stmt->errorInfo()[2]));
            }
        }
        
        public function getWorkingScheduleByPrimaryKey($id, $workingDate, $workingStartingTime, $workingOffTime){
            $query = "SELECT * FROM workingschedule 
                        WHERE id = :id
                        AND workingDate = :workingDate
                        AND workingStartingTime = :workingStartingTime
                        AND workingOffTime = :workingOffTime";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':workingDate', $workingDate, PDO::PARAM_STR);
            $stmt->bindParam(':workingStartingTime', $workingStartingTime, PDO::PARAM_STR);
            $stmt->bindParam(':workingOffTime', $workingOffTime, PDO::PARAM_STR);
            $stmt->execute();
            
            $workingschedule = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $workingschedule ?: null; // Return null if no admin type is found
        }
        
        public function getAllWorkingSchedule(){
            $query = "SELECT * FROM workingschedule";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            $workingschedule = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return !empty($workingschedule) ? $workingschedule : null; // Return null if no admins are found
        }
               
        public function addWorkingScheduleIntoDB($id, $workingDate, $workingStartingTime, $workingOffTime){
            if($this->isPrimaryKeyExistInWorkingScheduleDB($id, $workingDate, $workingStartingTime, $workingOffTime)){
                throw new RuntimeException("Record already exists for the given details.");
            }
            // SQL query to insert data into the workingSchedule table
            $query = "INSERT INTO workingSchedule (id, workingDate, workingStartingTime, workingOffTime)
                      VALUES (:id, :workingDate, :workingStartingTime, :workingOffTime)";

            // Prepare and execute the query (assuming you are using PDO)
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':workingDate', $workingDate, PDO::PARAM_STR);
            $stmt->bindParam(':workingStartingTime', $workingStartingTime, PDO::PARAM_STR);
            $stmt->bindParam(':workingOffTime', $workingOffTime, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return $this->getWorkingScheduleByPrimaryKey($id, $workingDate, $workingStartingTime, $workingOffTime);
            }
            return false;
        }

    }       
    
?>



