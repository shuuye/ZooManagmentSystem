<?php

    require_once 'UserController.php';
    require_once 'InputValidationCtrl.php';

    class ForgotPasswordCtrl extends UserController{
        protected $email;
        protected $forgotPasswordEmailInput;
        protected $newPassword;
        protected $confirmPassword;

        public function __construct() {
            parent::__construct();
        }
       
        private function afterResetPasswordInput($validInput, $email){
            if (!$validInput) {//if user inputed all valid data
                $_SESSION['userInputData'] = $this->forgotPasswordEmailInput;
                header("Location: index.php?controller=user&action=resetForgotPassword&email=" . $email);
                exit();
            }
            
            $hashedPassword = password_hash($this->newPassword, PASSWORD_DEFAULT);
            $updated = $this->updateDBColumnByEmail('password', $hashedPassword, $email);

            if ($updated){
                $_SESSION['resetPasswordSuccessfully'] = 'Password Reset Successfully';
                header("Location: index.php?controller=user&action=successPage");
                exit();
            }
        }
        
        private function validateNewPassword() {
            if (InputValidationCtrl::inputIsEmptyValidation($this->newPassword)) { //check empty
                return 'New Password cannot be empty!';
            }

            if (!InputValidationCtrl::inputMinLengthValidation($this->newPassword, 6)) { // password cannot less than 6 character
                return 'New Password must be at least 6 characters long';
            }

            return ''; // No error
        }

        private function validateConfirmPassword() {
            if (InputValidationCtrl::inputIsEmptyValidation($this->confirmPassword)) { // check empty
                return 'Confirm Password cannot be empty!';
            }

            if (!InputValidationCtrl::inputMatchValidation($this->confirmPassword, $this->newPassword)) { //confirm password must matched with new password
                return 'Confirm Password not matched';
            }

            return ''; // No error
        }
        
        private function checkResetPasswordInput(){
            $data = [
                'newPasswordErr' => $this->validateNewPassword(),
                'confirmPasswordErr' => $this->validateConfirmPassword()
            ];
            
            $this->forgotPasswordEmailInput = $data;
        }
        
        public function submitNewPasswordAfterForgot(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $email = isset($_GET['email']) ? $_GET['email'] : '';
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $this->forgotPasswordEmailInput = null;
                // Retrieve form data from the POST request
                $this->newPassword = $_POST['newPassword'] ?? '';
                $this->confirmPassword = $_POST['confirmPassword'] ?? '';

                $this->checkResetPasswordInput();
                
                if($this->checkEmptyUserInputData()){ // check if there is not error/invalid input
                    $validInput = true;
                }
                
                $this->afterResetPasswordInput($validInput, $email);
                
            }
        }
        
        public function resetForgotPassword(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            //get email from URL
            $email = isset($_GET['email']) ? $_GET['email'] : '';
            
            // Render the login form view
            $view = ['resetForgotPasswordView'];
            $data = $this->setRenderData('Reset Forgot Password');
            $data['email'] = $email;
            
            $this->renderView($view,$data);
        }
        
        private function afterForgotPassword($validInput){
            //only render view when there is error
            $email = $this->email;
            $this->email = null;
            
            if($validInput){//all input is valid
                header("Location: index.php?controller=user&action=resetForgotPassword&email=" . $email);
                exit();

            }else{
                $_SESSION['userInputData'] = $this->forgotPasswordEmailInput;
                header("Location: index.php?controller=user&action=forgotPassword");
                exit();
            }
        }
        
        private function validateEmail() {
            if (InputValidationCtrl::inputIsEmptyValidation($this->email)) { //check empty
                return 'Email cannot be empty!';
            }

            if (!InputValidationCtrl::inputFilterValidation($this->email, FILTER_VALIDATE_EMAIL)) { //check the email format
                return 'Invalid Email format';
            }

            if(!$this->isExistInUserDB($this->email, 'email')){ // check whether the email is existed in the database
                return 'Email Not Registered';
            }
            
            return ''; // No error
        }
        
        private function checkUserInput(){
            $data = [
                'emailErr' => $this->validateEmail()
            ];
            
            $this->forgotPasswordEmailInput = $data;
        }
        
        private function checkEmptyUserInputData() {
            foreach ($this->forgotPasswordEmailInput as $key => $value) {
                if (!empty($value)) {
                    return false; // If any value is not empty, return false
                }
            }
            return true; // If all values are empty, return true
        }
        
        public function submitForgotPasswordEmail(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $validInput = false;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->forgotPasswordEmailInput = null;
                $this->email = $_POST['email'] ?? '';
                $this->checkUserInput();
                
                if($this->checkEmptyUserInputData()){ // check is there any error/ invlid input
                    $validInput = true;
                }
                
                $this->afterForgotPassword($validInput);
            }
        }
        
        public function forgotPassword(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
              
            // Render the forgot password form view
            $view = ['forgotPasswordView'];
            $data = $this->setRenderData('Forgot Password');
            
            $this->renderView($view,$data);
        }
    }
