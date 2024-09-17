<?php    
    require_once __DIR__ . '/../../Config/databaseConfig.php';

    class UserModel extends databaseConfig {
        protected $db;

        public function __construct() {
            //establish database connection
            $this->db = $this->getConnection();
        }
        
        protected function getAllUser(){
            $query = "SELECT * FROM users";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return !empty($users) ? $users : null; // Return null if no users are found
        }
        
        protected function removeUserByID($id) {
            // Ensure the ID is an integer to prevent SQL injection
            $id = (int) $id;

            // Prepare the DELETE query
            $query = "DELETE FROM users WHERE id = :id";
            $stmt = $this->db->prepare($query);

            // Bind the ID parameter
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the query and check if the operation was successful
            if ($stmt->execute()) {
                // Check if any row was affected (i.e., a user was deleted)
                if ($stmt->rowCount() > 0) {
                    return true; // User was successfully deleted
                } else {
                    return false; // No user was found with the given ID
                }
            } else {
                // Query execution failed
                return false;
            }
        }

        protected function isExistInUserDB($value, $columnName) {
            // Prepare the statement with the validated column name
            $query = "SELECT COUNT(*) FROM users WHERE $columnName = :value";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':value', $value);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            // Debugging: Check the query and result
            if ($count === false) {
                throw new RuntimeException("Query failed: " . htmlspecialchars($stmt->errorInfo()[2]));
            }

            // Return true if the record exists, otherwise false
            return $count > 0;
        }
        
        protected function updateLastLoginDateTimeToDB($username){
            // Update last login date and time in the database
            $lastLoginDateTime = date('Y-m-d H:i:s'); // Current date and time
            $updateStmt = $this->db->prepare("UPDATE users SET lastLoginDateTime = :lastLoginDateTime WHERE username = :username");
            $updateStmt->bindParam(':lastLoginDateTime', $lastLoginDateTime);
            $updateStmt->bindParam(':username', $username);
            $updateStmt->execute();
        }
        
        protected function updateLastLogOutDateTimeToDB($username){
            // Update last login date and time in the database
            $lastLogOutDateTime = date('Y-m-d H:i:s'); // Current date and time
            $updateStmt = $this->db->prepare("UPDATE users SET lastLogOutDateTime = :lastLogOutDateTime WHERE username = :username");
            $updateStmt->bindParam(':lastLogOutDateTime', $lastLogOutDateTime);
            $updateStmt->bindParam(':username', $username);
            $updateStmt->execute();
        }
        
        protected function updateDBColumnByID($columnName, $updatingData, $id){
            $updateStmt = $this->db->prepare("UPDATE users SET $columnName  = :updatingData WHERE id = :id");
            $updateStmt->bindParam(':updatingData', $updatingData);
            $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
            if($updateStmt->execute()){
                return $this->getUserDetailsByIDFromDB($id);
            }else{
                return false;
            }
        }

        // Authenticate a user by checking the username and password
        protected function authUserInDB($username, $password) {
            // Fetch user details
            $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    
            if ($user && password_verify($password, trim($user['password']))) {
            //if ($user && ($password == trim($user['password']))) { // when set the database password by string like abc123 it will not be hashed
                return $user;
            }
            return false;
        }
        
        protected function getUserDetailsByIDFromDB($id){
            $query = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $user ?: false; // Return null if no user is found
        }
        
        protected function getLatestNewUser(){
            // Get the ID of the newly inserted user
            $newUserID = $this->db->lastInsertId();
            
            $latestNewUser = $this->getUserDetailsByIDFromDB($newUserID);
            
            return $latestNewUser ?: false;
        }

        protected function insertNewUserToDB($newUsername, $newPassword, $newFullName, $newPhoneNumber, $newEmail, $newRoleID){
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare( //set the registration date time to current date time, and lastLoginDate to null
                "INSERT INTO users (username, password, fullName, phoneNumber, email, registrationDateTime, lastLoginDateTime, lastLogOutDateTime, roleID) 
                 VALUES (:newUsername, :newPassword, :newFullName, :newPhoneNumber, :newEmail, NOW(), null, null, :newRoleID)"
            ); 
            $stmt->bindParam(':newUsername', $newUsername);
            $stmt->bindParam(':newPassword', $hashedPassword);
            $stmt->bindParam(':newFullName', $newFullName);
            $stmt->bindParam(':newPhoneNumber', $newPhoneNumber);
            $stmt->bindParam(':newEmail', $newEmail);
            $stmt->bindParam(':newRoleID', $newRoleID);
            
            return $stmt;
        }

        // Register a new user in the database
        protected function addNewUserIntoDB($newUsername, $newPassword, $newFullName, $newPhoneNumber, $newEmail, $newRoleID) {
            if ($this->isExistInUserDB($newUsername, 'username') || $this->isExistInUserDB($newEmail, 'email')) {
                return false; // User or email already exists
            }
            $stmt = $this->insertNewUserToDB($newUsername, $newPassword, $newFullName, $newPhoneNumber, $newEmail, $newRoleID);
            
            if ($stmt->execute()) {
                return $this->getLatestNewUser();
            }
            return false;
        }
    }       
    
?>

