<?php
require_once 'User.php';

class Admin extends User {
    private $type;
    private $privileges;    //rwx (read,write,execute)

    public function __construct($id, $username, $password, $firstName, $lastName, $phoneNumber, $email, $type, $privileges) {
        parent::__construct($id, $username, $password, $firstName, $lastName, $phoneNumber, $email, $type, $privileges);
        $this->type = $type;
        $this->privileges = $privileges;
    }
    
    public function getType() {
        return $this->type;
    }

    public function getPrivileges() {
        return $this->privileges;
    }

    public function setType($type): void {
        $this->type = $type;
    }

    public function setPrivileges($privileges): void {
        $this->privileges = $privileges;
    }

}

?>

