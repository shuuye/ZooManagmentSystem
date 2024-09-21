<<<<<<< HEAD
<?php

    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInAdmin();

    require_once 'UserController.php';

    class CustomerUserManagementCtrl extends UserController{
        public function __construct() {
            parent::__construct();

        }
        
        public function editCustomer($id = 0){
            $customer = $this->setCustomerDetails($this->setUserObj($id));
            
            $data = [
                'pageTitle' => 'Customer Editing Form',
                'selectedUser' => $customer,
                'action' => 'edit',
            ];
                        
            $view = ['customerDetailInputFormView'];
            $this->renderView($view, $data);

            //alter the customer details
        }
        
        public function customerUserManagement() {
            $this->index();
        }

        public function index(){
            $result = $this->processUsers();
            
            //set role for each data
            $usersArray = $result['usersArray'];
            $customersArray = $result['customersArray'];
            
            //set render data (set the user, customer, admin)
            $data = $this->setRenderData('User Management Panel');
            $data['usersArray'] = $usersArray;
            $data['customersArray'] = $customersArray;
            
            $view = ['adminTopNavHeader','userManagementTopNav','customerUserManagementView'];
            //display/render the user view
                
            $this->renderView($view,$data);
            
        }
    }
=======
<?php

    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInAdmin();// only allow admin to access

    require_once 'UserController.php';

    class CustomerUserManagementCtrl extends UserController{
        public function __construct() {
            parent::__construct();

        }
        
        public function editCustomer($id = 0){
            //set customer details
            $customer = $this->setCustomerDetails($this->setUserObj($id));
            
            $data = [
                'pageTitle' => 'Customer Editing Form',
                'selectedUser' => $customer,
                'action' => 'edit',
            ];
                        
            $view = ['customerDetailInputFormView'];
            $this->renderView($view, $data);

        }
        
        public function customerUserManagement() {
            $this->index();
        }

        public function index(){
            $result = $this->processUsers();
            
            //set user and details
            $usersArray = $result['usersArray'];
            $customersArray = $result['customersArray'];
            
            //set render data (set the user, customer)
            $data = $this->setRenderData('User Management Panel');
            $data['usersArray'] = $usersArray;
            $data['customersArray'] = $customersArray;
            
            $view = ['adminTopNavHeader','userManagementTopNav','customerUserManagementView'];
            //display/render the view
                
            $this->renderView($view,$data);
            
        }
    }
>>>>>>> 6ea1a893419d37eb823b4036368cf06f04acc540
