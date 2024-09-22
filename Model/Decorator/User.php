<?php
    /*Author Name: Chew Wei Seng*/
    require_once __DIR__ . '/../User/RolesModel.php';
    
    require_once __DIR__ . '/../User/UserModel.php';
    require_once __DIR__ . '/../Decorator/Role.php';
    require_once 'UserInterface.php';

    class User extends UserModel implements UserInterface {
        protected $id;
        protected $username;
        protected $password;
        protected $full_name;
        protected $phone_number;
        protected $email;
        protected $registrationDateTime;
        protected $lastLoginDateTime;
        protected $lastLogOutDateTime;
        protected Role $role;

        public function __construct($user = null, $role = null) {
            parent::__construct(); // Initialize the database connection
        
            if ($user !== null && $role !== null) {
                $this->setUserDetails($user, $role); // Load user data if an ID is provided
            }
        }

        protected function isUserDetailsExist($value, $columnName) {
            $isExist = $this->isExistInUserDB($value, $columnName);
            
            return $isExist;
        }

        private function setUserDetails($user, $role) {
            // Set user details
            $this->id = $user['id'];
            $this->username = $user['username'];
            $this->password = $user['password'];
            $this->full_name = $user['full_name'];
            $this->phone_number = $user['phone_number'];
            $this->email = $user['email'];
            $this->registrationDateTime = $user['registrationDateTime'];
            $this->lastLoginDateTime = $user['lastLoginDateTime'];
            $this->lastLogOutDateTime = $user['lastLogOutDateTime'];

            // Set role details
            if ($role) {
                $roleSetting = new Role();
                $roleSetting->setRoleID($role['roleID']);
                $roleSetting->setRoleName($role['roleName']);
                $this->role = $roleSetting;
            } else {
                $this->roleID = null;
                $this->roleName = null;
            }
        }
        
        // Authenticate a user by checking the username and password
        public function authUser($username, $password) {
            $user = $this->authUserInDB($username, $password);
            if ($user != false) {
                // Fetch role details
                $rolesModel = new RolesModel();
                $role = $rolesModel->getRoleByID($user['roleID']);

                // Set user and role details using the private method
                $this->setUserDetails($user, $role);
                $this->updateLastLoginDateTimeToDB($user['username']);
                return true;
            }

            return false;
        }
        
        protected function setLatestNewUser(){
            // Get the ID of the newly inserted user
            // Fetch the user details
            $user = $this->getUserDetailsByID($this->getLatestNewUserID());

            // Fetch the role details
            $rolesModel = new RolesModel();
            $role = $rolesModel->getRoleByID($user['roleID']);

            // Set user and role details using the private method
            $this->setUserDetails($user, $role);

            // Return the full user details
            return $this->getCurrentUserDetails();
        }

        private function addNewUserRoleDetails($userDetails){
            switch ($userDetails['role']['roleID']) {
                case '1':
                    $this->addAdminIntoDB($userDetails['id']);
                    break;
                case '2':
                    $this->addCustomerIntoDB($userDetails['id']);
                    break;
                default:
                    break;
            }
        }

        // Register a new user in the database
        public function addNewUser($newUsername, $newPassword, $newFullName, $newPhoneNumber, $newEmail, $newRoleID) {
            $createNewStatus = $this->addNewUserIntoDB($newUsername, $newPassword, $newFullName, $newPhoneNumber, $newEmail, $newRoleID);
            if ($createNewStatus) {
                $latestNewUser = $this->setLatestNewUser();
                $this->addNewUserRoleDetails($latestNewUser);
                
                return $latestNewUser;
            }
            return false;
        }
        
        public function getCurrentUserDetails() {
            if ($this->id) { // Check if the user is logged in
                return [
                    'id' => $this->id,
                    'username' => $this->username,
                    'password' => $this->password,
                    'full_name' => $this->full_name,
                    'phone_number' => $this->phone_number,
                    'email' => $this->email,
                    'registrationDateTime' => $this->registrationDateTime,
                    'lastLoginDateTime' => $this->lastLoginDateTime,
                    'lastLogOutDateTime' => $this->lastLogOutDateTime,
                    'role' => [
                        'roleID' => $this->role ? $this->role->getRoleID() : null,
                        'roleName' => $this->role ? $this->role->getRoleName() : null
                    ]
                ];
            }

            return null; // No user is logged in
        }
        
        public function getId() {
            return $this->id;
        }

        public function getUsername() {
            return $this->username;
        }

        public function getPassword() {
            return $this->password;
        }

        public function getFullName() {
            return $this->full_name;
        }

        public function getPhoneNumber() {
            return $this->phone_number;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getRegistrationDateTime() {
            return $this->registrationDateTime;
        }

        public function getLastLoginDateTime() {
            return $this->lastLoginDateTime;
        }
        
        public function getRole(){
            return $this->role;
        }

        public function getRoleByRoleID($roleID) {
            $rolesModel = new RolesModel();
            $role = $rolesModel->getRoleByID($roleID);
            
            return $role;
        }
        
        public function setId($id): void {
            $this->id = $id;
        }

        public function setUsername($username): void {
            $this->username = $username;
        }

        public function setPassword($password): void {
            $this->password = $password;
        }

        public function setFullName($full_name): void {
            $this->full_name = $full_name;
        }

        public function setPhoneNumber($phone_number): void {
            $this->phone_number = $phone_number;
        }

        public function setEmail($email): void {
            $this->email = $email;
        }

        public function setRegistrationDateTime($registrationDateTime): void {
            $this->registrationDateTime = $registrationDateTime;
        }

        public function setLastLoginDateTime($lastLoginDateTime): void {
            $this->lastLoginDateTime = $lastLoginDateTime;
        }
        
        public function setRole($role): void {
            $this->role = $role;
        }

        public function setRoleByRoleID($roleID): void {
            $rolesModel = new RolesModel();
            $role = $rolesModel->getRoleByID($roleID);
            
            $this->role = $role;
        }

    }           
    
?>

