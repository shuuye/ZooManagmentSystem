<?php    
    require_once __DIR__ . '/../../Config/databaseConfig.php';

    class AttendanceModel extends databaseConfig {
        protected $db;

        public function __construct() {
            //establish database connection
            $this->db = $this->getConnection();
        }
        
        public function isPrimaryKeyExistInAttendanceDB($id, $working_date, $working_starting_time, $working_off_time) {
            // Prepare the statement with the validated column name
            $query = "SELECT COUNT(*) FROM attendance 
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
        
        public function removeAttendanceByPrimaryKey($id, $working_date, $working_starting_time, $working_off_time) {
            // Prepare the DELETE query
            $query = "DELETE FROM attendance
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
        
        public function updateStatusIDFromAttendanceByPrimaryKey($id, $working_date, $working_starting_time, $working_off_time, $status_id) {
            // Prepare the Update query
            $query = "UPDATE attendance SET status_id = :status_id
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
            $stmt->bindParam(':status_id', $status_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return $this->getAttendanceByPrimaryKey($id, $working_date, $working_starting_time, $working_off_time);
            }
            return false;
        }
        
        public function updateColumnFromAttendanceByPrimaryKey($id, $working_date, $working_starting_time, $working_off_time, $columnName, $updatingData) {
            // Prepare the update query
            $query = "UPDATE attendance SET $columnName = :updatingData
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
            $stmt->bindParam(':updatingData', $updatingData);

            if ($stmt->execute()) {
                return $this->getAttendanceByPrimaryKey($id, $working_date, $working_starting_time, $working_off_time);
            }
            return false;
        }


        public function getAttendanceByPrimaryKey($id, $working_date, $working_starting_time, $working_off_time){
            try {
                // Ensure that the $id is an integer and other values are sanitized if needed
                $query = "SELECT * FROM attendance 
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

                // Use fetch if expecting a single record
                $attendance = $stmt->fetch(PDO::FETCH_ASSOC);

                return $attendance ?: null; // Return null if no record is found
            } catch (PDOException $e) {
                // Log the exception or handle it as needed
                error_log($e->getMessage());
                return null;
            }
        }
        
        public function getAllAttendance(){
            $query = "SELECT * FROM attendance";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            $attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return !empty($attendance) ? $attendance : null; // Return null if no admins are found
        }
               
        public function addDefaultAttendanceIntoDB($id, $working_date, $working_starting_time, $working_off_time){
            
            if($this->isPrimaryKeyExistInAttendanceDB($id, $working_date, $working_starting_time, $working_off_time)){
                throw new RuntimeException("Record already exists for the given details.");
            }
            
            // SQL query to insert data into the workingSchedule table
            $query = "INSERT INTO attendance (id, working_date, working_starting_time, working_off_time, photo, location, attendance_date_time, status_id)
                      VALUES (:id, :working_date, :working_starting_time, :working_off_time, null, null, null, 1)";

            // Prepare and execute the query (assuming you are using PDO)
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':working_date', $working_date, PDO::PARAM_STR);
            $stmt->bindParam(':working_starting_time', $working_starting_time, PDO::PARAM_STR);
            $stmt->bindParam(':working_off_time', $working_off_time, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return $this->getAttendanceByPrimaryKey($id, $working_date, $working_starting_time, $working_off_time);
            }
            return false;
        }

    }       
    
?>



