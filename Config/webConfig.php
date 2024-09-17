<?php

class webConfig {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function restrictAccessForNonLoggedInUser(){
        if (!isset($_SESSION['currentUserModel'])) {
            header('Location: index.php?controller=user&action=login');
            exit;
        }
    }
    
    public function restrictAccessForNonLoggedInAdmin(){
        $this->restrictAccessForNonLoggedInUser();
        if(isset($_SESSION['currentUserModel']) && $_SESSION['currentUserModel']['role']['roleID'] != 1){
            header('Location: index.php?controller=user&action=login');
            exit;
        }
    }
    
    public function restrictAccessForNonLoggedInStaff(){
        $this->restrictAccessForNonLoggedInUser();
        if(isset($_SESSION['currentUserModel']) && $_SESSION['currentUserModel']['role']['roleID'] != 3){
            header('Location: index.php?controller=user&action=login');
            exit;
        }
    }
    
    public function restrictAccessForLoggedInEditPermissionAdmin(){
        $this->restrictAccessForNonLoggedInAdmin();
        if (isset($_SESSION['currentUserModel']) && $_SESSION['currentUserModel']['role']['roleID'] == 1 && !in_array('edit', $_SESSION['currentUserModel']['permissions'])){
            header('Location: index.php?controller=user&action=userManagementMainPanel');
            exit;
        }
    }
    
    public function restrictAccessBasedOnActioninData($data) {
        if (!isset($data['action']) || $data['action'] !== 'edit') {
            return; // Exit early if action is not 'edit'
        }

        if (!isset($_SESSION['currentUserModel'])) {
            header('Location: index.php?controller=user&action=login');
            exit;
        }

        if (!in_array('edit', $_SESSION['currentUserModel']['permissions'])) {
            header('Location: index.php?controller=user&action=userManagementMainPanel');
            exit;
        }
    }
}

