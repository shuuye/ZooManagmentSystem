<?php

    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInAdmin();// only allow admin to access

    require_once 'UserController.php';

    class AdminUserManagementCtrl extends UserController{
        public function __construct() {
            parent::__construct();

        }
        public function editAdmin($id = 0){
            //set the user details with admin details (permission, adminType)
            $admin = $this->setAdminDetails($this->setUserObj($id));
            
            $data = [
                'pageTitle' => 'Admin Editing Form',
                'selectedUser' => $admin,
                'action' => 'edit',
            ];
                        
            $view = ['adminDetailInputFormView'];
            $this->renderView($view, $data);
        }

        public function adminUserManagement() {
            $this->index();
        }

        public function index(){
            $result = $this->processUsers();
            
            //set array data for user and admin role
            $usersArray = $result['usersArray'];
            $adminsArray = $result['adminsArray'];
            
            //set render data (set the user, admin)
            $data = $this->setRenderData('User Management Panel');
            $data['usersArray'] = $usersArray;
            $data['adminsArray'] = $adminsArray;
            $view = ['adminTopNavHeader','userManagementTopNav','adminUserManagementView'];
            
            //display/render the view
            $this->renderView($view,$data);
            
        }
    }

