<?php

class Role {
    protected $roleID;
    protected $roleName;

    // Constructor to initialize the role object
    public function __construct($roleID = null, $roleName = null) {
        $this->roleID = $roleID;
        $this->roleName = $roleName;
    }

    // Getters
    public function getRoleID() {
        return $this->roleID;
    }

    public function getRoleName() {
        return $this->roleName;
    }
    
    // Setters
    public function setRoleID($roleID): void {
        $this->roleID = $roleID;
    }

    public function setRoleName($roleName): void {
        $this->roleName = $roleName;
    }
}

?>
