<?php

    require_once 'UserDecorator.php';
    class ManagerLevelAdminDecorator extends UserDecorator {
        private $type;
        private $privileges;
        
        public function navigateToBasedOnRoles() {
            header("Location: ../adminMainPage.php");
            exit();
        }
        
    }

?>
