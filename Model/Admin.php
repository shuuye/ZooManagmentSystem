<?php
    require_once 'User.php';

    class Admin extends User {
        private $type;
        private $privileges;    //rwx (read,write,execute)
        
        public function __construct() {
            parent::__construct();
            $this->type = $type;
            $this->privileges = $privileges;
        }
        
        public function getType() {
            return $this->type;
        }

        public function getPrivileges() {
            return $this->privileges;
        }
    }

?>

