<?php

    require_once 'UserController.php';
    require_once 'InputValidationCtrl.php';
    
    class LoginCtrl extends UserController{
        
        protected $loginUsername;
        protected $loginPassword;
        protected $userLoginInputData;


        public function __construct() {
            parent::__construct();
            
        }
        
        private function clearUserLoginInputData(){
            $_SESSION['userLoginInputData'] = null;
            $this->userLoginInputData = null;
        }
        
        public function login() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if (isset($_SESSION['userLoginInputData'])) {
                $this->userLoginInputData = $_SESSION['userLoginInputData'];
                unset($_SESSION['userLoginInputData']); // Clear the session data
            }
            
            $data = $this->setRenderData('Login');
            $data['userLoginInputData'] = $this->userLoginInputData;
                
            // Render the login form view
            $view = ['loginFormView'];
            $this->renderView($view,$data);
            $this->clearUserLoginInputData();
        }
        
        private function setUserRoleDetails($loggedInUser){
            //set the user full details by thier role
            $role = $loggedInUser->getRole();
            switch ($role->getRoleID()) {
                case 1:
                    $loggedInUser = $this->setAdminDetails($loggedInUser);
                    $_SESSION['currentUserModel'] = $loggedInUser;
                    break;
                case 2:
                    $loggedInUser = $this->setCustomerDetails($loggedInUser);
                    $_SESSION['currentUserModel'] = $loggedInUser;
                    break;
                case 3:
                    $loggedInUser = $this->setStaffDetails($loggedInUser);
                    $_SESSION['currentUserModel'] = $loggedInUser;
                    break;
                default:
                    break;
            }
        }
                
        private function actionAfterLoggedInSuccessFully($loggedInUser){
            $this->setUserRoleDetails($loggedInUser);
            //set Login date time after logged in
            $this->updateLastLoginDateTimeToDB($loggedInUser->getUsername());
            switch ($loggedInUser->getRole()->getRoleID()) {
                case 1:
                    $this->navigateUserTo($loggedInUser->getRole()->getRoleID(), goAdminPanel: true);
                    break;
                case 2:
                    $this->navigateUserTo($loggedInUser->getRole()->getRoleID());
                    break;
                case 3:
                    $this->navigateUserTo($loggedInUser->getRole()->getRoleID(), goStaffPage: true);
                    break;
                default:
                    break;
            }
            
        }
        
        private function loginFailedAction(){
            $_SESSION['userLoginInputData'] = $this->userLoginInputData;
            header("Location: index.php?controller=user&action=login");
            exit();
        }
        
        private function afterLoginInputed($validInput){
            //only render view when there is error
            if($validInput){
                $loggedInUser = $this->loginUser($this->loginUsername, $this->loginPassword);
                if($loggedInUser != false){ // auth user = true
                    $this->actionAfterLoggedInSuccessFully($loggedInUser);
                    exit();
                }else{// auth user = true
                    $this->userLoginInputData['loginErr'] = 'Invalid username or password!';
                    $this->loginFailedAction();
                }
            }else{
                $this->loginFailedAction();
            }
        }
        
        public function submitLoginForm(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $validInput = true;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {//get username and password from POST
                $this->loginUsername = $_POST['username'] ?? '';
                $this->loginPassword = $_POST['password'] ?? '';

                if(InputValidationCtrl::inputIsEmptyValidation($this->loginUsername)){ // check empty
                    $this->userLoginInputData['usernameErr'] = 'Username cannot be empty!';
                    $validInput = false;
                } 
                if (InputValidationCtrl::inputIsEmptyValidation($this->loginPassword)) {// check empty
                    $this->userLoginInputData['passwordErr'] = 'Password cannot be empty!';
                    $validInput = false;
                } 
            }
            
            $this->afterLoginInputed($validInput);
        }
                        
        
    }
