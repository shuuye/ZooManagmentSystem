<?php
    require_once __DIR__ . '/../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInAdmin();
    
    class AdminPanelController{
        
        public function __construct() {
            
        }  
        
        public function displayAdminMainPanel(){
            include 'View/AdminPanelView.php';
            exit();
        }
    
        public function route(){
            $action = isset($_GET['action']) ? $_GET['action'] : 'actionFailed';
            
            if(isset($action)){
                switch($action){
                    case 'displayAdminMainPanel':
                        break;
                    case 'actionFailed':
                    default:
                        echo 'Action Undefined';
                        break;
                }
            }
            
            $this->$action();
        }
    }
