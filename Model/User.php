<?php
    require_once 'Roles.php';
    
    require_once __DIR__ . '/../Config/databaseConfig.php';
    require_once 'UserInterface.php';

    class User implements UserInterface{
        protected $id;
        protected $username;
        protected $password;
        protected $firstName;
        protected $lastName;
        protected $phoneNumber;
        protected $email;
        protected $registrationDate;
        protected $lastLoginDate;
        protected $role;
        
        public function __construct() {
            if (isset($_SESSION['user'])) {
                $user = $_SESSION['user'];
                $this->id = $user['id'];
                $this->username = $user['username'];
                $this->password = $user['password'];
                $this->firstName = $user['firstName'];
                $this->lastName = $user['lastName'];
                $this->phoneNumber = $user['phoneNumber'];
                $this->email = $user['email'];
                $this->role = $user['roleId']; // Assuming roleId is stored in the session
                $_SESSION['loginStatus'] = 'User logged in';
            } else {
                // Handle case where session data is not set
                $_SESSION['loginStatus'] = 'User not logged in';
            }
        }
     
        private function setUserInstance($userArray){
            // Create an instance of the User class
            $user = new User();
            $user->setId($userArray['id']);
            $user->setUsername($userArray['username']);
            $user->setPassword($userArray['password']);
            $user->setFirstName($userArray['firstName']);
            $user->setLastName($userArray['lastName']);
            $user->setPhoneNumber($userArray['phoneNumber']);
            $user->setEmail($userArray['email']);
            $user->setRoleID($userArray['roleId']);
            
            return $user;
        }
        
        public function login() {
            session_start();
            $_SESSION['error_message']="";
            if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["username"]!=''&& $_POST["password"]!='') {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $database = new databaseConfig();
                $db = $database->getConnection();
                
                try {
                    $query = "SELECT * FROM users WHERE username = :username AND password = :password";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $password);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        // Fetch the result, and store the logged in user in to the variable
                        $userArray = $stmt->fetch(PDO::FETCH_ASSOC);

                        //$user = self::setUserInstance($userArray);
                        //store in the session for further use
                        $_SESSION['user'] = $user;  // Store the entire user array in session
                        //$role = $user['roleId']; 
                        $_SESSION['loginStatus'] = 'User logged in';
                        return true; // Login successful
                        
                    } else {
                        $_SESSION['error_message'] = "Invalid username or password.";
                        return false; // Login failed
                    }
                } catch (PDOException $exception) {
                    $_SESSION['error_message'] = "Error: " . $exception->getMessage();
                    return false; // Login failed due to an error
                }
            }
        }

        public function register() {
            // dont Implement register logic here first
        }
        
        public function navigateToBasedOnRoles(){
            throw new Exception("Navigate function must be implemented by decorators");
            //or default will go to home.php page
        }

        //public function forgotPassword() {
            // Implement forgot password logic here
        //}
        public function setUsername($username): void {
            $this->username = $username;
        }

        public function setPassword($password): void {
            $this->password = $password;
        }

        public function setFirstName($firstName): void {
            $this->firstName = $firstName;
        }

        public function setLastName($lastName): void {
            $this->lastName = $lastName;
        }

        public function setPhoneNumber($phoneNumber): void {
            $this->phoneNumber = $phoneNumber;
        }

        public function setEmail($email): void {
            $this->email = $email;
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

        public function getFirstName() {
            return $this->firstName;
        }

        public function getLastName() {
            return $this->lastName;
        }

        public function getPhoneNumber() {
            return $this->phoneNumber;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getRole() {
            return $this->role;
        }
        
        public function setRole(Role $role) {
            $this->role = $role;
        }
        
        public function setRoleById(int $role) {
            
        }

    }
?>

