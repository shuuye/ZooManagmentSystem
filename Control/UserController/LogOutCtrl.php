<?php

    require_once 'UserController.php';
    
    class LogOutCtrl extends UserController{
        public function logOut(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $this->updateLastLogOutDateTimeToDB($_SESSION['currentUserModel']['username']);
            
            if (isset($_SESSION['currentUserModel'])) {
                //clear the user session when ever reach this page
                unset($_SESSION['currentUserModel']);
            }
            header('Location: index.php?controller=user&action=login');
        }
    }
