<?php

    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInUser();

    require_once __DIR__ . '/../../Model/User/WorkingScheduleModel.php';
    require_once __DIR__ . '/../../Model/User/AttendanceModel.php';
    require_once __DIR__ . '/../../Model/User/AttendanceStatusModel.php';
    require_once __DIR__ . '/../../Model/User/LeaveApplicationModel.php';
    require_once 'InputValidationCtrl.php';
    require_once 'UserController.php';

    class UserProfileManagementCtrl extends UserController{
        protected $userInputData;
        protected $id;
        protected $username;
        protected $currentPassword;
        protected $newPassword;
        protected $confirmPassword;
        protected $newFullName;
        protected $newPhoneNumber;
        
        public function __construct() {
            parent::__construct();

        }
        private function validateCurrentPassword(){
            if (InputValidationCtrl::inputIsEmptyValidation($this->currentPassword)) {
                return 'Current Password cannot be empty!';
            }
            
            if(!$this->authUserInDB($this->username, $this->currentPassword)){
                return 'Wrong Password.';
            }
        }
        
        private function validateNewPassword() {
            if (InputValidationCtrl::inputIsEmptyValidation($this->newPassword)) {
                return 'New Password cannot be empty!';
            }

            if (!InputValidationCtrl::inputMinLengthValidation($this->newPassword, 6)) {
                return 'New Password must be at least 6 characters long';
            }

            return ''; // No error
        }

        private function validateConfirmPassword() {
            if (InputValidationCtrl::inputIsEmptyValidation($this->confirmPassword)) {
                return 'Confirm Password cannot be empty!';
            }

            if (!InputValidationCtrl::inputMatchValidation($this->confirmPassword, $this->newPassword)) {
                return 'Confirm Password not matched';
            }

            return ''; // No error
        }
        
        private function validateNewFullName() {
            if (InputValidationCtrl::inputIsEmptyValidation($this->newFullName)) {
                return 'New Name cannot be empty!';
            }

            if (!InputValidationCtrl::inputFormatValidation($this->newFullName, "/^[a-zA-Z ]*$/")) {
                return 'Only letters and white space allowed';
            }

            return ''; // No error
        }
        
        private function validateNewPhoneNumber() {
            if (InputValidationCtrl::inputIsEmptyValidation($this->newPhoneNumber)) {
                return 'New Phone Number cannot be empty!';
            }

            if (!InputValidationCtrl::inputFormatValidation($this->newPhoneNumber, "/^\d{10,11}$/")) {
                return 'Invalid phone number format (10 to 11 digits required, without dashes "-")';
            }

            return ''; // No error
        }
        
        private function checkResetPasswordInput(){
            $data = [
                'currentPasswordErr' => $this->validateCurrentPassword(),
                'newPasswordErr' => $this->validateNewPassword(),
                'confirmPasswordErr' => $this->validateConfirmPassword()
            ];
            
            $this->userInputData = $data;
        }
        
        private function checkEmptyUserInputData() {
            foreach ($this->userInputData as $key => $value) {
                if (!empty($value)) {
                    return false; // If any value is not empty, return false
                }
            }
            return true; // If all values are empty, return true
        }
        
        private function resetPasswordFailedAction(){
            $this->userInputData['inputFormErr'] = 'Password Reset Failed';
            $_SESSION['userInputData'] = $this->userInputData;
            header("Location: index.php?controller=user&action=showUserProfile");
            exit();
        }
        
        private function resetCurrentUserModel() {
            $userDetails = $this->getUserDetailsByIDFromDB($this->id);
            if($userDetails['roleID'] == 1){
                $_SESSION['currentUserModel'] = $this->setAdminDetails($this->setUserObj($this->id));
            }elseif($userDetails['roleID'] == 2){
                $_SESSION['currentUserModel'] = $this->setCustomerDetails($this->setUserObj($this->id));
            }elseif($userDetails['roleID'] == 3){
                $_SESSION['currentUserModel'] = $this->setStaffDetails($this->setUserObj($this->id));
            }
            
        }
        
        private function resetPasswordSuccessfullyAction(){
            $_SESSION['resetSuccess'] = 'Password Reset Successfully.';
            $_SESSION['userInputData'] = $this->userInputData;
            $this->resetCurrentUserModel();
            header("Location: index.php?controller=user&action=showUserProfile");
            exit();
        }
        
        private function afterResetPasswordInput($validInput) {
            if (!$validInput) {
                $this->resetPasswordFailedAction();
                return;
            }
            $hashedPassword = password_hash($this->newPassword, PASSWORD_DEFAULT);
            $this->updateDBColumnByID('password', $hashedPassword, $this->id);

            // Execute post-edit action
            $this->resetPasswordSuccessfullyAction();
        }
        
        public function submitResetPasswordForm(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $this->userInputData = null;
                // Retrieve form data from the POST request
                $this->id = $_POST['id'] ?? '';
                $this->username = $_POST['username'] ?? '';
                $this->currentPassword = $_POST['currentPassword'] ?? '';
                $this->newPassword = $_POST['newPassword'] ?? '';
                $this->confirmPassword = $_POST['confirmPassword'] ?? '';

                $this->checkResetPasswordInput();
                
                if($this->checkEmptyUserInputData()){
                    $validInput = true;
                }
                
                $this->afterResetPasswordInput($validInput);
                
            }
        }
        
        private function resetFullNameFailedAction(){
            $this->userInputData['inputFullNameErr'] = 'Full Name Reset Failed';
            $_SESSION['userInputData'] = $this->userInputData;
            header("Location: index.php?controller=user&action=showUserProfile");
            exit();
        }
        
        private function resetFullNameSuccessfullyAction(){
            $_SESSION['resetFullNameSuccess'] = 'Full Name Reset Successfully.';
            $_SESSION['userInputData'] = $this->userInputData;
            $this->resetCurrentUserModel();
            header("Location: index.php?controller=user&action=showUserProfile");
            exit();
        }
        
        private function afterFullNameInput($validInput){
            if (!$validInput) {
                $this->resetFullNameFailedAction();
                return;
            }
            $this->updateDBColumnByID('full_name', $this->newFullName, $this->id);
            // Execute post-edit action
            $this->resetFullNameSuccessfullyAction();
        }
        
        private function checkResetFullNameInput(){
            $data = [
                'newFullNameErr' => $this->validateNewFullName(),
            ];
            
            $this->userInputData = $data;
        }
        
        public function submitResetFullNameForm(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $this->userInputData = null;
                // Retrieve form data from the POST request
                $this->id = $_POST['id'] ?? '';
                $this->newFullName = $_POST['newFullName'] ?? '';
                
                $this->checkResetFullNameInput();
                
                if($this->checkEmptyUserInputData()){
                    $validInput = true;
                }
                
                $this->afterFullNameInput($validInput);
                
            }
        }
        
        private function resetPhoneNumberFailedAction(){
            $this->userInputData['inputPhoneNumberErr'] = 'Phone Number Reset Failed';
            $_SESSION['userInputData'] = $this->userInputData;
            header("Location: index.php?controller=user&action=showUserProfile");
            exit();
        }
        
        private function resetPhoneNumberSuccessfullyAction(){
            $_SESSION['resetPhoneNumberSuccess'] = 'Phone Number Reset Successfully.';
            $_SESSION['userInputData'] = $this->userInputData;
            $this->resetCurrentUserModel();
            header("Location: index.php?controller=user&action=showUserProfile");
            exit();
        }
        
        private function afterPhoneNumberInput($validInput){
            if (!$validInput) {
                $this->resetPhoneNumberFailedAction();
                return;
            }
            $this->updateDBColumnByID('phone_number', $this->newPhoneNumber, $this->id);
            // Execute post-edit action
            $this->resetPhoneNumberSuccessfullyAction();
        }
        
        private function checkResetPhoneNumberInput(){
            $data = [
                'newPhoneNumberErr' => $this->validateNewPhoneNumber(),
            ];
            
            $this->userInputData = $data;
        }
        
        public function submitResetPhoneNumberForm(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $this->userInputData = null;
                // Retrieve form data from the POST request
                $this->id = $_POST['id'] ?? '';
                $this->newPhoneNumber = $_POST['newPhoneNumber'] ?? '';
                
                $this->checkResetPhoneNumberInput();
                
                if($this->checkEmptyUserInputData()){
                    $validInput = true;
                }
                
                $this->afterPhoneNumberInput($validInput);
                
            }
        }
        
        public function showUserProfile(){
            $data = [
                'pageTitle' => 'User Profile',
            ];
                        
            $view = ['userProfileView'];
            
            $this->renderView($view, $data);
        }
    }