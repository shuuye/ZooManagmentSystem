<?php
    class Roles{
        private $roleID;
        private $roleName;
        
        public function __construct($roleID, $roleName) {
            $this->roleID = $roleID;
            $this->roleName = $roleName;
        }
        
        public function getRoleID() {
            return $this->roleID;
        }

        public function getRoleName() {
            return $this->roleName;
        }

    }
?>

