<?php    
    require_once __DIR__ . '/../../Config/databaseConfig.php';

    class WorkingScheduleModel extends databaseConfig {
        protected $db;

        public function __construct() {
            //establish database connection
            $this->db = $this->getConnection();
        }
        
        public function isPrimaryKeyExistInWorkingScheduleDB($id, $working_date, $working_starting_time, $working_off_time) {
            // Prepare the statement with the validated column name
            $query = "SELECT COUNT(*) FROM workingschedule 
                        WHERE id = :id
                        AND working_date = :working_date
                        AND working_starting_time = :working_starting_time
                        AND working_off_time = :working_off_time";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':working_date', $working_date, PDO::PARAM_STR);
            $stmt->bindParam(':working_starting_time', $working_starting_time, PDO::PARAM_STR);
            $stmt->bindParam(':working_off_time', $working_off_time, PDO::PARAM_STR);
            
            $stmt->execute();
            $count = $stmt->fetchColumn();

            // Debugging: Check the query and result
            if ($count === false) {
                throw new RuntimeException("Query failed: " . htmlspecialchars($stmt->errorInfo()[2]));
            }

            // Return true if the record exists, otherwise false
            return $count > 0;
        }
        
        public function removeWorkingScheduleByPrimaryKey($id, $working_date, $working_starting_time, $working_off_time) {
            // Prepare the DELETE query
            $query = "DELETE FROM workingSchedule
                        WHERE id = :id
                        AND working_date = :working_date
                        AND working_starting_time = :working_starting_time
                        AND working_off_time = :working_off_time";
            
            // Bind the ID parameter
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':working_date', $working_date, PDO::PARAM_STR);
            $stmt->bindParam(':working_starting_time', $working_starting_time, PDO::PARAM_STR);
            $stmt->bindParam(':working_off_time', $working_off_time, PDO::PARAM_STR);

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
        
        public function getWorkingScheduleByPrimaryKey($id, $working_date, $working_starting_time, $working_off_time){
            $query = "SELECT * FROM workingschedule 
                        WHERE id = :id
                        AND working_date = :working_date
                        AND working_starting_time = :working_starting_time
                        AND working_off_time = :working_off_time";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':working_date', $working_date, PDO::PARAM_STR);
            $stmt->bindParam(':working_starting_time', $working_starting_time, PDO::PARAM_STR);
            $stmt->bindParam(':working_off_time', $working_off_time, PDO::PARAM_STR);
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
               
        public function addWorkingScheduleIntoDB($id, $working_date, $working_starting_time, $working_off_time){
            if($this->isPrimaryKeyExistInWorkingScheduleDB($id, $working_date, $working_starting_time, $working_off_time)){
                throw new RuntimeException("Record already exists for the given details.");
            }
            // SQL query to insert data into the workingSchedule table
            $query = "INSERT INTO workingSchedule (id, working_date, working_starting_time, working_off_time)
                      VALUES (:id, :working_date, :working_starting_time, :working_off_time)";

            // Prepare and execute the query (assuming you are using PDO)
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':working_date', $working_date, PDO::PARAM_STR);
            $stmt->bindParam(':working_starting_time', $working_starting_time, PDO::PARAM_STR);
            $stmt->bindParam(':working_off_time', $working_off_time, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return $this->getWorkingScheduleByPrimaryKey($id, $working_date, $working_starting_time, $working_off_time);
            }
            return false;
        }

    }       
    
?>



