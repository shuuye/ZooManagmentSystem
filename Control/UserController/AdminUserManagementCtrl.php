<?php

    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInAdmin();

    require_once 'UserController.php';

    class AdminUserManagementCtrl extends UserController{
        public function __construct() {
            parent::__construct();

        }
        public function editAdmin($id = 0){
            $admin = $this->setAdminDetails($this->setUserObj($id));
            
            $data = [
                'pageTitle' => 'Admin Editing Form',
                'selectedUser' => $admin,
                'action' => 'edit',
            ];
                        
            $view = ['adminDetailInputFormView'];
            $this->renderView($view, $data);

            //alter the admin Details
        }

        public function adminUserManagement() {
            $this->index();
        }

        public function index(){
            $result = $this->processUsers();
            
            //set role for each data
            $usersArray = $result['usersArray'];
            $adminsArray = $result['adminsArray'];
            
            //set render data (set the user, customer, admin)
            $data = $this->setRenderData('User Management Panel');
            $data['usersArray'] = $usersArray;
            $data['adminsArray'] = $adminsArray;
            $view = ['adminTopNavHeader','userManagementTopNav','adminUserManagementView'];
            //display/render the user view

            $this->renderView($view,$data);
            
        }
    }

