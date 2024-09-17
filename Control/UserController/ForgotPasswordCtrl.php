<?php

    require_once 'UserController.php';
    require_once 'InputValidationCtrl.php';
    require_once __DIR__ . '/../../Model/User/ResetPasswordModel.php';
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // Load Composer's autoloader if you're using Composer
    require 'Public/UserWebService/vendor/autoload.php';

    class ForgotPasswordCtrl extends UserController{
        protected $email;
        protected $forgotPasswordEmailInput;

        public function __construct() {
            parent::__construct();
        }
        
        public function resetForgotPassword(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
              
            // Render the login form view
            $view = ['resetForgotPasswordView'];
            $data = $this->setRenderData('Reset Forgot Password');
            
            $this->renderView($view,$data);
        }
        
        private function afterForgotPassword($validInput){
            //only render view when there is error
            if($validInput){
                // Generate a unique reset token
                //here send email
                //if not then just give email then enough
                $resetToken = bin2hex(random_bytes(32)); // Generate a secure token
                $expires = date("U") + 3600; // Token expires in 1 hour
                $email = $this->email;

                $_SESSION['resetPasswordSent'] = 'A password reset link has been sent to your email address. <br>Please check your inbox and follow the instructions to reset your password.';
                // Redirect to a confirmation page
                header("Location: index.php?controller=user&action=forgotPassword");
                exit();

            }else{
                $_SESSION['userInputData'] = $this->forgotPasswordEmailInput;
                header("Location: index.php?controller=user&action=forgotPassword");
                exit();
            }
        }
        
        private function validateEmail() {
            if (InputValidationCtrl::inputIsEmptyValidation($this->email)) {
                return 'Email cannot be empty!';
            }

            if (!InputValidationCtrl::inputFilterValidation($this->email, FILTER_VALIDATE_EMAIL)) {
                return 'Invalid Email format';
            }

            if(!$this->isExistInUserDB($this->email, 'email')){
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
                
                if($this->checkEmptyUserInputData()){
                    $validInput = true;
                }
                
                $this->afterForgotPassword($validInput);
            }
        }
        
        public function forgotPassword(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
              
            // Render the login form view
            $view = ['forgotPasswordView'];
            $data = $this->setRenderData('Forgot Password');
            
            $this->renderView($view,$data);
        }
    }
