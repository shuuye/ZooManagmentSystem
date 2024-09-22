<?php
    /*Author Name: Chew Wei Seng*/
    require_once __DIR__ . '/../../Config/databaseConfig.php';
    
    class RolesModel extends databaseConfig{
        protected $roleID;
        protected $roleName;
        
        public function __construct() {
            //establish database connection
            $this->db = $this->getConnection();
        }
        
        public function getRoleByID($roleID) {
            $stmt = $this->db->prepare("SELECT * FROM roles WHERE roleID = :roleID");
            $stmt->bindParam(':roleID', $roleID);
            $stmt->execute();

            $role = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if a role was found and return the entire row
            if ($role) {
                return $role; // Returns an associative array with 'roleID' and 'roleName'
            } else {
                return null; // Return null if no role is found
            }
        }
        
        public function getAllRoles() {
            $stmt = $this->db->prepare("SELECT * FROM roles");
            $stmt->execute();

            $role = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Check if a role was found and return the entire row
            if ($role) {
                return $role; // Returns an associative array with 'roleID' and 'roleName'
            } else {
                return null; // Return null if no role is found
            }
        }
        
        public function getRoleID() {
            return $this->roleID;
        }

        public function getRoleName() {
            return $this->roleName;
        }

        public function setRoleID($roleID): void {
            $this->roleID = $roleID;
        }

        public function setRoleName($roleName): void {
            $this->roleName = $roleName;
        }


    }
?>

