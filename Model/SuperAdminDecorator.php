<?php

    require_once 'UserDecorator.php';
    class SuperAdminDecorator extends UserDecorator {
        private $type;
        private $privileges;
        
        public function navigateToBasedOnRoles() {
            header("Location: ../adminMainPage.php");
            exit();
        }
    }

?>
