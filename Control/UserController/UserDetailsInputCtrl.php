<?php

    require_once 'UserController.php';
    require_once 'InputValidationCtrl.php';
    
    class UserDetailsInputCtrl extends UserController{
        protected $id;
        protected $username;
        protected $password;
        protected $confirmPassword;
        protected $full_name;
        protected $phone_number;
        protected $email;
        protected $role;
        protected $userInputData;


        public function __construct() {
            parent::__construct();
            
        }
        
        public function signUp() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if (isset($_SESSION['userInputData'])) {
                $this->userInputData = $_SESSION['userInputData'];
                unset($_SESSION['userInputData']); // Clear the session data
            }
            
            $data = $this->setRenderData('Sign Up');
            $data['userInputData'] = $this->userInputData;
            
            //$data['cssFiles'] = [ //another css file];

            // Render the registration form view
            $view = ['userDetailsInputFormView'];
            $this->renderView($view,$data);
            $this->clearUserInputData();
        }
        
        private function clearUserInputData(){
            $_SESSION['userInputData'] = null;
            $this->userInputData = null;
        }
        
        private function registrationFailedAction(){
            $this->userInputData['inputFormErr'] = 'Registration Failed';
            $_SESSION['userInputData'] = $this->userInputData;
            header("Location: index.php?controller=user&action=signUp");
            exit();
        }
        
        private function actionAfterRegisteredSuccessfully($lastestNewUser){
            $role = $lastestNewUser->getRole();
            if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
            $_SESSION['registrationSuccess'] = 'User Registration Successfully.';
            if($_POST['submitAs'] === 'register'){
                //is registering user
                switch ($role->getRoleID()) {
                    case '1':
                        $this->addNewAdmin($lastestNewUser->getId());
                        break;
                    case '2':
                        $this->addNewCustomer($lastestNewUser->getId());
                        break;
                    case '3':
                        $this->addNewStaff($lastestNewUser->getId());
                        break;
                }
                $navigateWho = isset($_SESSION['currentUserModel']) ? $_SESSION['currentUserModel']['role']['roleID'] : null;
                $this->navigateUserTo($navigateWho, true);// meaning that the user just registered
            }else{
                //go next
                $this->navigateUserTo();
            }
            
        }
        
        private function afterRegisterInput($validInput){
            //only render view when there is error
            if($validInput){
                //see whether is a new user or not
                //if yes only do registration
                
                // register user to db
                $lastestNewUser = parent::registerNewUser($this->username, $this->password, $this->full_name, $this->phone_number, $this->email, $this->role['roleID']);
                
                if($lastestNewUser != false){ // created user successfully
                    $this->actionAfterRegisteredSuccessfully($lastestNewUser);
                    
                }else{
                    $this->registrationFailedAction();
                }
            }else{
                $this->registrationFailedAction();
            }
            
        }
        
        private function editingUserFailedAction(){
            $this->userInputData['inputFormErr'] = 'User Information Editing Failed';
            $_SESSION['userInputData'] = $this->userInputData;
            header("Location: index.php?controller=user&action=editUser&id=$this->id");
            exit();
        }
        
        private function actionAfterEditedSuccessfully($editBy){
            if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
            $_SESSION['editSuccess'] = 'User Details Edited Successfully.';
            
            $role = $this->setFullRole($editBy['role']['roleID']);
            $userObj = new User ($editBy, $role);
            if($_POST['submitAs'] === 'adminEdit'){
                //if the $editBy['id']
                $this->navigateUserTo($userObj->getRole(), true);// meaning that the user just registered
            }else{
                //go next
                $this->navigateUserTo($role);
            }
            
        }
        
        private function updateNewRoleToDefault($editingUser){
            // when role change should delete the previous role data and set the default new one
            require_once __DIR__ . '/../../Model/User/CustomerModel.php';
            require_once __DIR__ . '/../../Model/User/AdminModel.php';
            
            $adminModel = new AdminModel();
            $customerModel = new CustomerModel();
            $newRoleID = $this->role['roleID'];
            
            if ($newRoleID == 1) { // Assuming 1 represents admin
                // Set default admin type
                $customerModel->removeCustomerByID($editingUser['id']);
                $adminModel->addAdminIntoDB($editingUser['id']);
            } else if ($newRoleID == 2) { // Assuming 2 represents customer
                // Set default customer membershipID
                $adminModel->removeAdminByID($editingUser['id']);
                $customerModel->addCustomerIntoDB($editingUser['id']);
            }
        }
        
        
        private function updateData($editingUser){
            // Check which fields have changed
            $isFullNameChanged = $this->full_name !== $editingUser['full_name'];
            $isPhoneNumberChanged = $this->phone_number !== $editingUser['phone_number'];
            $isEmailChanged = $this->email !== $editingUser['email'];
            $isRoleChanged = $this->role['roleID'] !== $editingUser['roleID'];

            // Perform updates if there are changes
            if ($isFullNameChanged) {
                $this->updateDBColumnByID('full_name', $this->full_name, $this->id);
            }

            if ($isPhoneNumberChanged) {
                $this->updateDBColumnByID('phone_number', $this->phone_number, $this->id);
            }

            if ($isEmailChanged) {
                $this->updateDBColumnByID('email', $this->email, $this->id);
            }

            if ($isRoleChanged) {
                $this->updateDBColumnByID('roleID', $this->role['roleID'], $this->id);
                $this->updateNewRoleToDefault($editingUser);
                // when role change should delete the previous role data and set the default new one
            }
            
        }
        
        private function updateSessionCurrentUser($id){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $userModel = $this->getUserDetailsByIDFromDB($id);
            $role = $this->setFullRole($userModel['roleID']);
            $userObj = new User ($userModel, $role);
            $_SESSION['currentUserModel'] = null;
            switch ($userObj->getRole()->getRoleID()) {
                case 1:
                    $userObj = $this->setAdminDetails($userObj);
                    $_SESSION['currentUserModel'] = $userObj;
                    break;
                case 2:
                    $userObj = $this->setCustomerDetails($userObj);
                    $_SESSION['currentUserModel'] = $userObj;
                    break;
                default:
                    break;
            }
        }
        
        private function afterEditingInput($validInput) {
            if (!$validInput) {
                $this->editingUserFailedAction();
                return;
            }

            // Fetch current user details from the database
            $editingUser = $this->getUserDetailsByIDFromDB($this->id);
            $this->initializeSession();

            if ($this->isAdminRole($editingUser['roleID']) && !$this->hasAdminEditPermission()) {
                if ($editingUser['id'] != $_SESSION['currentUserModel']['id']) { // Allow admin to edit their own data
                    $this->redirectNoPermission();
                    return;
                }
            }

            // Update data for the editing user
            $this->updateData($editingUser);

            // If the editing user is the same as the current session user, update session data
            if ($editingUser['id'] == $_SESSION['currentUserModel']['id']) {
                $this->updateSessionCurrentUser($_SESSION['currentUserModel']['id']);
            }

            // Execute post-edit action
            $this->actionAfterEditedSuccessfully($_SESSION['currentUserModel']);
        }
        
        private function initializeSession() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        }

        private function isAdminRole($roleID) {
            return $roleID == 1;
        }

        private function hasAdminEditPermission() {
            return isset($_SESSION['currentUserModel']['role']['roleID']) &&
                   $_SESSION['currentUserModel']['role']['roleID'] == 1 &&
                   in_array('manage admin', $_SESSION['currentUserModel']['permissions']);
        }

        private function redirectNoPermission() {
            $_SESSION['editingFailed'] = 'Admin Details Editing Failed, U have no permission to manage admin.';
            header('Location: index.php?controller=user&action=userManagementMainPanel');
            exit;
        }

        private function setFullRole($roleID){
            require_once __DIR__ . '/../../Model/User/RolesModel.php';
            $roleModel = new RolesModel;
            $role = $roleModel->getRoleByID($roleID);
            return $role;
        }
        
        private function setUserTemp(){
            if($_POST['submitAs'] === 'register'){
                $this->username = $_POST['username'] ?? '';
                $this->password = $_POST['password'] ?? '';
                $this->confirmPassword = $_POST['confirmPassword'] ?? '';
                $this->full_name = $_POST['full_name'] ?? '';
                $this->phone_number = $_POST['phone_number'] ?? '';
                $this->email = $_POST['email'] ?? '';
                $this->role = $this->setFullRole($_POST['roleID'] ?? '');
            }else{
                $this->id = $_POST['id'] ?? '';
                $this->full_name = $_POST['full_name'] ?? '';
                $this->phone_number = $_POST['phone_number'] ?? '';
                $this->email = $_POST['email'] ?? '';
                $this->role = $this->setFullRole($_POST['roleID'] ?? '');
            }
            
        }
        
        private function validateUsername() {
            if (InputValidationCtrl::inputIsEmptyValidation($this->username)) {
                return 'Username cannot be empty!';$this->password = $_POST['password'] ?? '';
                $this->confirmPassword = $_POST['confirmPassword'] ?? '';
                $this->full_name = $_POST['full_name'] ?? '';
                $this->phone_number = $_POST['phone_number'] ?? '';
                $this->email = $_POST['email'] ?? '';
                $this->role = $this->setFullRole($_POST['roleID'] ?? '');
            }
            
            if (!InputValidationCtrl::inputFormatValidation($this->username, "/^[a-zA-Z0-9_]{5,20}$/")) {
                return 'Invalid username format (5-20 alphanumeric characters and underscores only)';
            }

            if (parent::isExistInUserDB($this->username, 'username')) {
                return 'Username is already registered before. Please try another.';
            }

            return ''; // No error
        }

        private function validatePassword() {
            if (InputValidationCtrl::inputIsEmptyValidation($this->password)) {
                return 'Password cannot be empty!';
            }

            if (!InputValidationCtrl::inputMinLengthValidation($this->password, 6)) {
                return 'Password must be at least 6 characters long';
            }

            return ''; // No error
        }

        private function validateConfirmPassword() {
            if (InputValidationCtrl::inputIsEmptyValidation($this->confirmPassword)) {
                return 'Confirm Password cannot be empty!';
            }

            if (!InputValidationCtrl::inputMatchValidation($this->confirmPassword, $this->password)) {
                return 'Confirm Password not matched';
            }

            return ''; // No error
        }
        
        private function validateFullName() {
            if (InputValidationCtrl::inputIsEmptyValidation($this->full_name)) {
                return 'Name cannot be empty!';
            }

            if (!InputValidationCtrl::inputFormatValidation($this->full_name, "/^[a-zA-Z ]*$/")) {
                return 'Only letters and white space allowed';
            }

            return ''; // No error
        }
        
        private function validatePhoneNumber() {
            if (InputValidationCtrl::inputIsEmptyValidation($this->phone_number)) {
                return 'Phone Number cannot be empty!';
            }

            if (!InputValidationCtrl::inputFormatValidation($this->phone_number, "/^\d{10,11}$/")) {
                return 'Invalid phone number format (10 to 11 digits required, without dashes "-")';
            }

            return ''; // No error
        }
        
        private function validateEmail() {
            if (InputValidationCtrl::inputIsEmptyValidation($this->email)) {
                return 'Email cannot be empty!';
            }

            if (!InputValidationCtrl::inputFilterValidation($this->email, FILTER_VALIDATE_EMAIL)) {
                return 'Invalid Email format';
            }

            if($_POST['submitAs'] === 'register'){
                if (parent::isExistInUserDB($this->email, 'email')) {
                    return 'Email is already registered before. Please try another.';
                }
            }
            

            return ''; // No error
        }

        private function checkEmptyUserInputData() {
            foreach ($this->userInputData as $key => $value) {
                if (!empty($value)) {
                    return false; // If any value is not empty, return false
                }
            }
            return true; // If all values are empty, return true
        }
        
        private function checkUserInput(){
            if($_POST['submitAs'] === 'register'){
                $data = [
                    'usernameErr' => $this->validateUsername(),
                    'passwordErr' => $this->validatePassword(),
                    'confirmPasswordErr' => $this->validateConfirmPassword(),
                    'full_nameErr' => $this->validateFullName(),
                    'phone_numberErr' => $this->validatePhoneNumber(),
                    'emailErr' => $this->validateEmail()
                ];
                
            }else{
                $data = [
                    'full_nameErr' => $this->validateFullName(),
                    'phone_numberErr' => $this->validatePhoneNumber(),
                    'emailErr' => $this->validateEmail()
                ];
            }
            
            $this->userInputData = $data;
        }
        
        public function editUser(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if (isset($_SESSION['userInputData'])) {
                $this->userInputData = $_SESSION['userInputData'];
                unset($_SESSION['userInputData']); // Clear the session data
            }
            
            $userId = isset($_GET['id']) ? $_GET['id'] : null;
            $userModel = $this->getUserDetailsByIDFromDB($userId);
            $role = $this->setFullRole($userModel['roleID']);
            $userObj = new User($userModel, $role);
            $user = $userObj->getCurrentUserDetails();
            
            $data = [
                'pageTitle' => 'User Editing Form',
                'selectedUser' => $user,
                'action' => 'edit',
                'userInputData' => $this->userInputData
            ];
            
            $view = ['userDetailsInputFormView'];
            $this->renderView($view,$data);
            $this->clearUserInputData();
        }
        
        public function submitUserDetailsInputForm(){
            //clear is logged in session when submit
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $validInput = false;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->userInputData = null;
                $this->setUserTemp();
                $this->checkUserInput();
                
                if($this->checkEmptyUserInputData()){
                    $validInput = true;
                }
                
            }
            
            // if is registering 
            if($_POST['submitAs'] === 'register'){
                $this->afterRegisterInput($validInput);
            }else{
                $this->afterEditingInput($validInput);
            }
            
        }
        
        
    }
