<?php    
    /*Author Name: Chew Wei Seng*/
    require_once __DIR__ . '/../../Config/databaseConfig.php';

    class AttendanceStatusModel extends databaseConfig {
        protected $db;

        public function __construct() {
            //establish database connection
            $this->db = $this->getConnection();
        }
        
        public function getAttendanceStatusByID($status_id) {
            $stmt = $this->db->prepare("SELECT * FROM attendancestatus WHERE status_id = :status_id");
            $stmt->bindParam(':status_id', $status_id);
            $stmt->execute();

            $attendanceStatus = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if a attendanceStatus was found and return the entire row
            if ($attendanceStatus) {
                return $attendanceStatus; 
            } else {
                return null; // Return null if no attendanceStatus is found
            }
        }
        
        public function getAllAttendanceStatus() {
            $stmt = $this->db->prepare("SELECT * FROM attendancestatus");
            $stmt->execute();

            $attendanceStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Check if a attendanceStatus was found and return the entire row
            if ($attendanceStatus) {
                return $attendanceStatus; 
            } else {
                return null; // Return null if no attendanceStatus is found
            }
        }

    }       
    
?>



