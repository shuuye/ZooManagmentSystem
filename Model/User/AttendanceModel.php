<?php    
    require_once __DIR__ . '/../../Config/databaseConfig.php';

    class AttendanceModel extends databaseConfig {
        protected $db;

        public function __construct() {
            //establish database connection
            $this->db = $this->getConnection();
        }
        
        public function isPrimaryKeyExistInAttendanceDB($id, $workingDate, $workingStartingTime, $workingOffTime) {
            // Prepare the statement with the validated column name
            $query = "SELECT COUNT(*) FROM attendance 
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
        
        public function removeAttendanceByPrimaryKey($id, $workingDate, $workingStartingTime, $workingOffTime) {
            // Prepare the DELETE query
            $query = "DELETE FROM attendance
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
        
        public function updateStatusIDFromAttendanceByPrimaryKey($id, $workingDate, $workingStartingTime, $workingOffTime, $statusID) {
            // Prepare the Update query
            $query = "UPDATE attendance SET statusID = :statusID
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
            $stmt->bindParam(':statusID', $statusID, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return $this->getAttendanceByPrimaryKey($id, $workingDate, $workingStartingTime, $workingOffTime);
            }
            return false;
        }
        
        public function updateColumnFromAttendanceByPrimaryKey($id, $workingDate, $workingStartingTime, $workingOffTime, $columnName, $updatingData) {
            // Prepare the update query
            $query = "UPDATE attendance SET $columnName = :updatingData
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
            $stmt->bindParam(':updatingData', $updatingData);

            if ($stmt->execute()) {
                return $this->getAttendanceByPrimaryKey($id, $workingDate, $workingStartingTime, $workingOffTime);
            }
            return false;
        }


        public function getAttendanceByPrimaryKey($id, $workingDate, $workingStartingTime, $workingOffTime){
            try {
                // Ensure that the $id is an integer and other values are sanitized if needed
                $query = "SELECT * FROM attendance 
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
               
        public function addDefaultAttendanceIntoDB($id, $workingDate, $workingStartingTime, $workingOffTime){
            
            if($this->isPrimaryKeyExistInAttendanceDB($id, $workingDate, $workingStartingTime, $workingOffTime)){
                throw new RuntimeException("Record already exists for the given details.");
            }
            
            // SQL query to insert data into the workingSchedule table
            $query = "INSERT INTO attendance (id, workingDate, workingStartingTime, workingOffTime, photo, location, attendanceDateTime, statusID)
                      VALUES (:id, :workingDate, :workingStartingTime, :workingOffTime, null, null, null, 1)";

            // Prepare and execute the query (assuming you are using PDO)
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':workingDate', $workingDate, PDO::PARAM_STR);
            $stmt->bindParam(':workingStartingTime', $workingStartingTime, PDO::PARAM_STR);
            $stmt->bindParam(':workingOffTime', $workingOffTime, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return $this->getAttendanceByPrimaryKey($id, $workingDate, $workingStartingTime, $workingOffTime);
            }
            return false;
        }

    }       
    
?>



