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
                //is registering user/
                // register user roles data based on role id
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
                // if it is edit by admin
                $this->navigateUserTo($userObj->getRole(), true);// meaning that the user just registered
            }else{
                $this->navigateUserTo($role);
            }
            
        }
        
        private function updateNewRoleToDefault($editingUser){
            // when role change should delete the previous role data and set the default new one
            require_once __DIR__ . '/../../Model/User/CustomerModel.php';
            require_once __DIR__ . '/../../Model/User/AdminModel.php';
            //here
            $adminModel = new AdminModel();
            $customerModel = new CustomerModel();
            $staffModel = new StaffModel();
            $newRoleID = $this->role['roleID'];
            
            if ($newRoleID == 1) { // Assuming 1 represents admin
                if($editingUser['roleID'] == 2){
                    //remove customer data if the editing user is customer
                    // Set default admin type
                    $customerModel->removeCustomerByID($editingUser['id']);
                    $adminModel->addAdminIntoDB($editingUser['id']);
                }elseif($editingUser['roleID'] == 3){
                    //remove staff data if the editing user is staff
                    // Set default admin type
                    $staffModel->removeStaffByID($editingUser['id']);
                    $adminModel->addAdminIntoDB($editingUser['id']);
                }
                
            } else if ($newRoleID == 2) { // Assuming 2 represents customer
                if($editingUser['roleID'] == 1){
                    //remove admin data if the editing user is admin
                    // Set default customer membershipID
                    $adminModel->removeAdminByID($editingUser['id']);
                    $customerModel->addCustomerIntoDB($editingUser['id']);
                }elseif($editingUser['roleID'] == 3){
                    //remove staff data if the editing user is staff
                    // Set default admin type
                    $staffModel->removeStaffByID($editingUser['id']);
                    $customerModel->addCustomerIntoDB($editingUser['id']);
                }
            }else if ($newRoleID == 3) { // Assuming 2 represents staff
                if($editingUser['roleID'] == 1){
                    //remove admin data if the editing user is admin
                    // Set default staff 
                    $adminModel->removeAdminByID($editingUser['id']);
                    $staffModel->addStaffIntoDB($editingUser['id']);
                }elseif($editingUser['roleID'] == 2){
                    //remove customer data if the editing user is customer
                    // Set default staff 
                    $staffModel->removeStaffByID($editingUser['id']);
                    $staffModel->addStaffIntoDB($editingUser['id']);
                }
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
            //check whether the logged in admin have permission to manage other admin
            return isset($_SESSION['currentUserModel']['role']['roleID']) &&
                   $_SESSION['currentUserModel']['role']['roleID'] == 1 &&
                   in_array('manage admin', $_SESSION['currentUserModel']['permissions']);
        }

        private function redirectNoPermission() {
            //refuse to edit admin if the logged admin did not have permission to edit
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
            //get data from POST
            if($_POST['submitAs'] === 'register'){
                $this->username = $_POST['username'] ?? '';
                $this->password = $_POST['password'] ?? '';
                $this->confirmPassword = $_POST['confirmPassword'] ?? '';
                $this->full_name = $_POST['full_name'] ?? '';
                $this->phone_number = $_POST['phone_number'] ?? '';
                $this->email = $_POST['email'] ?? '';
                $this->role = $this->setFullRole($_POST['roleID'] ?? '');
            }else{
                //editing
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
            if (InputValidationCtrl::inputIsEmptyValidation($this->password)) { //check empty
                return 'Password cannot be empty!';
            }

            if (!InputValidationCtrl::inputMinLengthValidation($this->password, 6)) { //password should more or same than 6 character
                return 'Password must be at least 6 characters long';
            }

            return ''; // No error
        }

        private function validateConfirmPassword() {
            if (InputValidationCtrl::inputIsEmptyValidation($this->confirmPassword)) {  //check empty
                return 'Confirm Password cannot be empty!';
            }

            if (!InputValidationCtrl::inputMatchValidation($this->confirmPassword, $this->password)) { //confirm password need to matched with the password
                return 'Confirm Password not matched';
            }

            return ''; // No error
        }
        
        private function validateFullName() {
            if (InputValidationCtrl::inputIsEmptyValidation($this->full_name)) { //check empty
                return 'Name cannot be empty!';
            }

            if (!InputValidationCtrl::inputFormatValidation($this->full_name, "/^[a-zA-Z ]*$/")) { //only allowe letter and space
                return 'Only letters and white space allowed';
            }

            return ''; // No error
        }
        
        private function validatePhoneNumber() {
            if (InputValidationCtrl::inputIsEmptyValidation($this->phone_number)) { //check empty
                return 'Phone Number cannot be empty!';
            }

            if (!InputValidationCtrl::inputFormatValidation($this->phone_number, "/^\d{10,11}$/")) { //onyl allowed 10 to 11 digit without dash
                return 'Invalid phone number format (10 to 11 digits required, without dashes "-")';
            }

            return ''; // No error
        }
        
        private function validateEmail() {
            if (InputValidationCtrl::inputIsEmptyValidation($this->email)) { //check empty
                return 'Email cannot be empty!';
            }

            if (!InputValidationCtrl::inputFilterValidation($this->email, FILTER_VALIDATE_EMAIL)) { //check email format
                return 'Invalid Email format';
            }

            if($_POST['submitAs'] === 'register'){ // if they are registering as new user, the email should not duplicate with the data in users table 
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
            //check user input
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
