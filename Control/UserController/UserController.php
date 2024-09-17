<?php

    require_once __DIR__ . '/../../Model/User/UserModel.php';
    require_once __DIR__ . '/../../Model/User/RolesModel.php';
    require_once __DIR__ . '/../../Model/User/CustomerModel.php';
    require_once __DIR__ . '/../../Model/User/AdminModel.php';
    require_once __DIR__ . '/../../Model/User/StaffModel.php';
    require_once __DIR__ . '/../../Model/Decorator/User.php';
    require_once __DIR__ . '/../../View/UserView/UserView.php';
    require_once __DIR__ . '/../../View/UserView/UserView.php';
    
    class UserController extends UserModel{
        protected $model;
        protected $view;
      
        public function __construct() {
            parent::__construct();
            $this->view = new UserView();
            $this->model = $this;
        }        
        
        protected function renderView($view=[], $data = []){
            // If login failed or form wasn't submitted yet, re-render the login page with the error message
            $output = $this->view->render($view, $data);
            
            echo $output;
        }
        
        protected function setRenderData($title, $cssFile=[]){
            $data = [
                'pageTitle' => $title, // Set the title for the page
                'cssFiles' => [
                    'Css\userManagementCss.css'
                    //'path/to/your/styles.css', // Add the path to your main CSS file
                    //'path/to/another/styles.css' // Add paths to any additional CSS files
                ]
            ];
            
            return $data;
            
        }
        
        private function setFullRole($roleID){
            $roleModel = new RolesModel;
            $role = $roleModel->getRoleByID($roleID);
            return $role;
        }
        
        protected function setUserObj($id){
            $userId = '';
            if($id == 0){
                $userId = isset($_GET['id']) ? $_GET['id'] : null;
            }else{
                $userId = $id;
            }
            $userModel = $this->getUserDetailsByIDFromDB($userId);
            $role = $this->setFullRole($userModel['roleID']);
            $userObj = new User($userModel, $role);
            
            return $userObj;
        }
                
        protected function navigateUserTo($roleID = null, $justRegistered = false, $goAdminPanel = false, $goStaffPage = false){
            
            switch ($roleID){
                case 1:
                case 3:
                    if($goStaffPage){
                        header("Location: index.php?controller=user&action=staffMainPanel");
                    }else if($goAdminPanel){
                        header("Location: index.php?controller=admin&action=displayAdminMainPanel");
                    } else {
                        header("Location: index.php?controller=user&action=userManagementMainPanel");
                    }
                    
                    exit();
                case 2:
                    if($justRegistered){ 
                        header("Location: index.php?controller=user&action=login");
                    }else{
                        header("Location: index.php");
                    }
                    exit();
                default:
                    header("Location: index.php?controller=user&action=login");
                    exit();
            }
        }
        
        protected function setPermissionByType($adminType, $decoratedUserModel) {
            require_once __DIR__ . '/../../Model/Decorator/AdminReadDecorator.php';
            require_once __DIR__ . '/../../Model/Decorator/AdminEditDecorator.php';
            require_once __DIR__ . '/../../Model/Decorator/AdminManagerDecorator.php';

            switch ($adminType){
                case 'ReadOnlyAdmin':
                    $specializedDecoratedUserModel = new AdminReadDecorator($decoratedUserModel);
                    break;
                case 'EditorAdmin':
                    $specializedDecoratedUserModel = new AdminEditDecorator(new AdminReadDecorator($decoratedUserModel));
                    break;
                case 'SuperAdmin':
                    $specializedDecoratedUserModel = new AdminManagerDecorator(new AdminEditDecorator(new AdminReadDecorator($decoratedUserModel)));
                    break;
                default:
                    $specializedDecoratedUserModel = new AdminReadDecorator($decoratedUserModel);
            }
            
            return $specializedDecoratedUserModel;
        }
        
        protected function setAdminDetails($userObj){
            require_once __DIR__ . '/../../Model/Decorator/AdminDecorator.php';
            $decoratedUserModel = new AdminDecorator($userObj);
            $adminDetails = $decoratedUserModel->getCurrentUserDetails();
            $adminType = $adminDetails['adminType'];
            
            $specializedDecoratedUserModel = $this->setPermissionByType($adminType, $decoratedUserModel);
            
            $userDetails = $specializedDecoratedUserModel->getCurrentUserDetails();
            
            return $userDetails;
            
        }
        
        protected function setCustomerDetails($userObj){
            require_once __DIR__ . '/../../Model/Decorator/CustomerDecorator.php';
            $decoratedUserModel = new CustomerDecorator($userObj);
            $userDetails = $decoratedUserModel->getCurrentUserDetails();
            
            return $userDetails;
        }
        
        protected function setStaffDetails($userObj){
            require_once __DIR__ . '/../../Model/Decorator/StaffDecorator.php';
            $decoratedUserModel = new StaffDecorator($userObj);
            $userDetails = $decoratedUserModel->getCurrentUserDetails();
            
            return $userDetails;
        }
        

        protected function registerNewUser($newUsername, $newPassword, $newFullName, $newPhoneNumber, $newEmail, $newRoleID) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            // Use inherited methods from UserModel
            $lastestNewUser = $this->model->addNewUserIntoDB($newUsername, $newPassword, $newFullName, $newPhoneNumber, $newEmail, $newRoleID);
            if ($lastestNewUser != false) {
                //register user successfuly
                $role = new RolesModel();
                $userRole = $role->getRoleByID($lastestNewUser['roleID']);
                $newRegisteredUser = new User($lastestNewUser, $userRole);
                return $newRegisteredUser;
                
            } else {
                return false;
            }
        }
        
        protected function addNewCustomer($id, $membershipID = 1){
            if(!$this->isExistInUserDB($id, 'id')){
                return false;
            }else{
                $newCustomer = new CustomerModel();
                $newCustomer->addCustomerIntoDB($id, $membershipID);
            }
            return $newCustomer;
        }
        
        protected function addNewAdmin($id, $type = 'ReadOnlyAdmin'){
            if(!$this->isExistInUserDB($id, 'id')){
                return false;
            }else{
                $newAdmin = new AdminModel();
                $newAdmin->addAdminIntoDB($id, $type);
            }
            return $newAdmin;
        }
        
        protected function addNewStaff($id, $position = 'General Staff'){
            if(!$this->isExistInUserDB($id, 'id')){
                return false;
            }else{
                $newStaff = new StaffModel();
                $newStaff->addStaffIntoDB($id, $position);
            }
            return $newStaff;
        }
        
        protected function loginUser($loginUsername, $loginPassword) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            // Use inherited methods from UserModel
            $loginUser = $this->model->authUserInDB($loginUsername, $loginPassword);
            if ($loginUser != false) {
                // Store all user detail in session
                return $this->setUserObj($loginUser['id']);
                
            } else {
                return false;
            }
        }
        
        protected function processUsers(){
            // Step 1: Retrieve all users from the database
            $usersModelArray = $this->getAllUser(); // Assume this method returns an array of user data

            // Arrays to hold admin, customer, staff, user objects
            $usersArray = [];
            $adminsArray = [];
            $customersArray = [];
            $staffsArray = [];

            // Step 2: Loop through each user, set the role, and create the User object
            foreach ($usersModelArray as $user) {
                $userObj = $this->setUserObj($user['id']);

                $usersArray[] = $userObj->getCurrentUserDetails();
                
                // Step 3: Set admin or customer details
                if ($user['roleID'] == 1) {
                    $admin = $this->setAdminDetails($userObj); // Set admin details
                    $adminsArray[] = $admin; // Store admin object into admin array
                } elseif ($user['roleID'] == 2) {
                    $customer = $this->setCustomerDetails($userObj); // Set customer details
                    $customersArray[] = $customer; // Store customer object into customer array
                } elseif ($user['roleID'] == 3) {
                    $staff = $this->setStaffDetails($userObj); // Set customer details
                    $staffsArray[] = $staff; // Store customer object into customer array
                }
            }
            return [
                'usersArray' => $usersArray, 
                'adminsArray' => $adminsArray, 
                'customersArray' => $customersArray,
                'staffsArray' => $staffsArray
            ];
        }

        public function userManagement(){
            $result = $this->processUsers();
            
            //set role for each data
            $usersArray = $result['usersArray'];
            $adminsArray = $result['adminsArray'];
            $customersArray = $result['customersArray'];
            $staffsArray = $result['staffsArray'];
            
            //set render data (set the user, customer, admin)
            $data = $this->setRenderData('User Management Panel');
            $data['usersArray'] = $usersArray;
            $data['adminsArray'] = $adminsArray;
            $data['customersArray'] = $customersArray;
            $data['staffsArray'] = $staffsArray;
            
            $view = ['adminTopNavHeader','userManagementTopNav','userManagementView'];
            //display/render the user view

            $this->renderView($view,$data);
            
        }
        
        public function route(){
            $action = isset($_GET['action']) ? $_GET['action'] : 'actionFailed';
            
            switch($action){
                case 'login': 
                case 'submitLoginForm':
                    require_once __DIR__ . '/LoginCtrl.php';
                    $controller = new LoginCtrl();
                    break;
                case 'signUp': 
                case 'editUser':
                case 'submitUserDetailsInputForm':
                    require_once __DIR__ . '/UserDetailsInputCtrl.php';
                    $controller = new UserDetailsInputCtrl();
                    break;
                case 'deleteUser':
                case 'confirmDeletion':
                    require_once __DIR__ . '/DeleteDifferentTypeUsersCtrl.php';
                    $controller = new DeleteDifferentTypeUsersCtrl();
                    break;
                case 'submitAdminDetailsForm':
                case 'submitCustomerDetailsForm':
                case 'submitStaffDetailsForm':
                    require_once __DIR__ . '/EditUserDetailsCtrl.php';
                    $controller = new EditUserDetailsCtrl();
                    break;
                case 'showUserProfile':
                case 'submitResetPasswordForm':
                case 'submitResetFullNameForm':
                case 'submitResetPhoneNumberForm':
                    require_once __DIR__ . '/UserProfileManagementCtrl.php';
                    $controller = new UserProfileManagementCtrl();
                    break;
                case 'userManagement':
                    $this->userManagement();
                    break;
                case 'editAdmin':
                case 'adminUserManagement':
                    require_once __DIR__ . '/AdminUserManagementCtrl.php';
                    $controller = new AdminUserManagementCtrl();
                    break;
                case 'editCustomer':
                case 'customerUserManagement':
                    require_once __DIR__ . '/CustomerUserManagementCtrl.php';
                    $controller = new CustomerUserManagementCtrl();
                    break;
                case 'editStaff':
                case 'staffUserManagement':
                    require_once __DIR__ . '/StaffUserManagementCtrl.php';
                    $controller = new StaffUserManagementCtrl();
                    break;
                case 'attendanceManagement':
                case 'updateAttendanceStatus':
                case 'editAttendanceStatus':
                    require_once __DIR__ . '/AttendanceManagementCtrl.php';
                    $controller = new AttendanceManagementCtrl();
                    break;
                case 'leaveApplicationManagement':
                case 'addLeaveApplication':
                case 'applyNewLeave':
                case 'updateLeaveApprovalStatus':
                case 'deleteLeaveApplication':
                case 'confirmLeaveApplicationDeletion':
                    require_once __DIR__ . '/LeaveApplicationManagementCtrl.php';
                    $controller = new LeaveApplicationManagementCtrl();
                    break;
                case 'workingScheduleManagement':
                case 'createNewWorkSchedule':
                case 'submitWorkingScheduleInputForm':
                case 'editWorkingSchedule':
                case 'deleteWorkingSchedule':
                case 'confirmWorkScheduleDeletion':
                    require_once __DIR__ . '/WorkScheduleManagementCtrl.php';
                    $controller = new WorkScheduleManagementCtrl();
                    break;
                case 'userManagementMainPanel':
                    require_once __DIR__ . '/UserManagementMainPanelCtrl.php';
                    $controller = new UserManagementMainPanelCtrl();
                    break;  
                case 'staffWorkingScheduleWithAttendance':
                case 'staffTakeWorkingAttendance':
                case 'staffLeaveApplication':
                case 'staffMainPanel':
                case 'takeAttendance':
                    require_once __DIR__ . '/StaffCtrl.php';
                    $controller = new StaffCtrl();
                    break;
                case 'showMembership':
                case 'purchaseMembership':
                case 'purchaseSuccess':
                    require_once __DIR__ . '/CustomerCtrl.php';
                    $controller = new CustomerCtrl();
                    break;
                case 'forgotPassword':
                case 'submitForgotPasswordEmail':
                case 'resetForgotPassword':
                    require_once __DIR__ . '/ForgotPasswordCtrl.php';
                    $controller = new ForgotPasswordCtrl();
                    break;
                case 'logOut':
                    require_once __DIR__ . '/LogOutCtrl.php';
                    $controller = new LogOutCtrl();
                    break;
                case 'actionFailed':
                default:
                    echo 'Action Undefined';
                    break;
            }
            
            if(isset($controller)){
                $controller->$action();
            }
            
        }
        
        
    }
    
?>


