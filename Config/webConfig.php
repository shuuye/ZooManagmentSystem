<?php
/*Author name: Chew Wei Seng*/

class webConfig {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function restrictAccessForNonLoggedInUser(){
        //only allow the logged in user to access
        if (!isset($_SESSION['currentUserModel'])) {
            header('Location: index.php?controller=user&action=login');
            exit;
        }
    }
    
    public function restrictAccessForNonLoggedInAdmin(){
        //only allow the logged in admin to access
        $this->restrictAccessForNonLoggedInUser();
        if(isset($_SESSION['currentUserModel']) && $_SESSION['currentUserModel']['role']['roleID'] != 1){
            header('Location: index.php?controller=user&action=login');
            exit;
        }
    }
    
    public function restrictAccessForNonLoggedInStaff(){
        //only allow the logged in staff to access
        $this->restrictAccessForNonLoggedInUser();
        if(isset($_SESSION['currentUserModel']) && $_SESSION['currentUserModel']['role']['roleID'] != 3){
            header('Location: index.php?controller=user&action=login');
            exit;
        }
    }
    
    public function restrictAccessForLoggedInEditPermissionAdmin(){
        //only allow the admin that have permission to edit
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

