<?php
/*
    session_start();
    require_once '../Config/databaseConfig.php';

    $database = new databaseConfig();
    $db = $database->getConnection();
*/
    class userManagementControl{
        
        public static function setUserByRoles(){
            // Retrieve the User object from the session
            $user = $_SESSION['user'];

            // Check if $user is an instance of UserInterface
            if (!$user instanceof UserInterface) {
                throw new Exception("User object is not valid.");
            }

            $role = $user->getRole();

            require_once __DIR__ . '/../Model/AdminDecorator.php';
            require_once __DIR__ . '/../Model/CustomerDecorator.php';

            // Decorate the user based on the role
            if ($role == 1) { // role = 1 = admin
                $user = new AdminDecorator($user);
            } else if ($role == 2) { // role = 2 = customer
                $user = new CustomerDecorator($user);
            }

            // Optionally store the decorated user object in the session
            $_SESSION['user'] = $user;
        }
        
        // Sanitize input data
        public static function prepareInput($data) {
            // Remove extra spaces from both ends of a string
            $data = trim($data);
            // Remove backslashes
            $data = stripslashes($data);
            // Convert special characters to HTML entities
            $data = htmlspecialchars($data);
            return $data;
        }
        
        public static function initializeRegisteringData() {
            $_SESSION['newUsernameErr'] = $_SESSION['newPasswordErr'] = $_SESSION['confirmPasswordErr'] = $_SESSION['newFirstNameErr'] = $_SESSION['newLastNameErr'] = $_SESSION['newPhoneNumberErr'] = $_SESSION['newEmailErr'] = "";
        }
       
        
        public static function inputFormatValidation(&$newInputVar, $sessionName, $whatRequired, &$errVar, $format, $formatErrorMsg){
            if (empty($_POST[$sessionName])) {
                $errVar = $whatRequired . " is required";
            } else {
                $newInputVar = self::prepareInput($_POST[$sessionName]);
                if (!preg_match($format, $newInputVar)) {
                    $errVar = $formatErrorMsg;
                }
            }
        }
        
        public static function inputLengthValidation(&$newInputVar, $sessionName, $whatRequired, &$errVar, $numberOfLength, $formatErrorMsg){
            if (empty($_POST[$sessionName])) {
                $errVar = $whatRequired . " is required";
            } else {
                $newInputVar = self::prepareInput($_POST[$sessionName]);
                if (strlen($newInputVar) < $numberOfLength) {
                    $errVar = $formatErrorMsg;
                }
            }
        }
        
        public static function inputMatchValidation(&$newInputVar, $sessionName, $whatRequired, &$errVar, $matchingVar, $formatErrorMsg){
            if (empty($_POST[$sessionName])) {
                $errVar = $whatRequired . " is required";
            } else {
                $newInputVar = self::prepareInput($_POST[$sessionName]);
                if ($newInputVar != $matchingVar) {
                    $errVar = $formatErrorMsg;
                }
            }
        }
        
        public static function inputFilterValidation(&$newInputVar, $sessionName, $whatRequired, &$errVar, $filterVar, $formatErrorMsg){
            if (empty($_POST[$sessionName])) {
                $errVar = $whatRequired . " is required";
            } else {
                $newInputVar = self::prepareInput($_POST[$sessionName]);
                if (!filter_var($newInputVar, $filterVar)) {
                    $errVar = $formatErrorMsg;
                }
            }
        }
        
        
        public static function validateRegistringInput() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Initialize error messages
                $newUsernameErr = $newPasswordErr = $confirmPasswordErr = $newFirstNameErr = $newLastNameErr = $newPhoneNumberErr = $newEmailErr = "";
                $newUsername = $newPassword = $confirmPassword = $newFirstName = $newLastName = $newPhoneNumber = $newEmail = "";

                self::inputFormatValidation($newUsername, "newUsername", "Username", $newUsernameErr, "/^[a-zA-Z0-9_]{5,20}$/", "Invalid username format (5-20 alphanumeric characters and underscores only)");
                self::inputLengthValidation($newPassword, "newPassword", "Password", $newPasswordErr, 6, "Password must be at least 6 characters long");
                self::inputMatchValidation($confirmPassword, "confirmPassword", "Confirm Password", $confirmPasswordErr, $_POST["newPassword"], "Confirm Password not matched");
                self::inputFormatValidation($newFirstName, "newFirstName", "First Name", $newFirstNameErr, "/^[a-zA-Z ]*$/", "Only letters and white space allowed");
                self::inputFormatValidation($newLastName, "newLastName", "Last Name", $newLastNameErr, "/^[a-zA-Z ]*$/", "Only letters and white space allowed");
                self::inputFormatValidation($newPhoneNumber, "newPhoneNumber", "Phone Number", $newPhoneNumberErr, "/^\d{10}$/", "Invalid phone number format (10 digits required, without dashes \"-\")");
                self::inputFilterValidation($newEmail, "newEmail", "Email Address", $newEmailErr, FILTER_VALIDATE_EMAIL, "Invalid Email format");

                // Store errors in session
                $_SESSION['newUsernameErr'] = $newUsernameErr;
                $_SESSION['newPasswordErr'] = $newPasswordErr;
                $_SESSION['confirmPasswordErr'] = $confirmPasswordErr;
                $_SESSION['newFirstNameErr'] = $newFirstNameErr;
                $_SESSION['newLastNameErr'] = $newLastNameErr;
                $_SESSION['newPhoneNumberErr'] = $newPhoneNumberErr;
                $_SESSION['newEmailErr'] = $newEmailErr;
            }
        }
        
        public static function getAllRoles() {
            require_once __DIR__ . '/../Config/databaseConfig.php';
            $database = new databaseConfig();
            $db = $database->getConnection();

            $query = "SELECT roleId, roleName FROM roles";
            $stmt = $db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public static function generateRoleOptions($defaultRoleId) {
            $roles = self::getAllRoles();
            $options = '';

            foreach ($roles as $role) {
                $isSelected = ($role["roleId"] == $defaultRoleId) ? "selected" : "";
                $options .= '<option value="' . self::prepareInput($role["roleId"]) . '" ' . $isSelected . '>' . self::prepareInput($role["roleName"]) . '</option>';
            }

            return $options;
        }

            
    }
    
    

?>

