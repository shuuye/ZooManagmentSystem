<?php

    require_once 'Membership.php';
    
    class CustomerDecorator extends UserDecorator {
        private $membership;
        
        public function navigateToBasedOnRoles() {
            header("Location: ../ZooManagementSystem/home.php");
            exit();
        }
    }

?>


