
<?php
    require_once 'UserInterface.php';

    abstract class UserDecorator implements UserInterface {
        protected $user;

        // Constructor accepting a UserInterface instance
        public function __construct(UserInterface $user) {
            $this->user = $user;
        }

        public function authUser($username, $password) {
            return $this->user->authUser($username, $password);
        }

        public function addNewUser($newUsername, $newPassword, $newFullName, $newPhoneNumber, $newEmail, $newRoleID) {
            return $this->user->addNewUser($newUsername, $newPassword, $newFullName, $newPhoneNumber, $newEmail, $newRoleID);
        }

        public function getCurrentUserDetails() {
            return $this->user->getCurrentUserDetails();
        }
        // Implement getters and setters if needed
        public function getId() { return $this->user->getId(); }
        public function getUsername() { return $this->user->getUsername(); }
        public function getPassword() { return $this->user->getPassword(); }
        public function getFullName() { return $this->user->getFullName(); }
        public function getPhoneNumber() { return $this->user->getPhoneNumber(); }
        public function getEmail() { return $this->user->getEmail(); }
        public function getRegistrationDateTime() { return $this->user->getRegistrationDateTime(); }
        public function getLastLoginDateTime() { return $this->user->getLastLoginDateTime(); }
        public function getRole() { return $this->user->getRole(); }
        public function getRoleByRoleID($roleID) { return $this->user->getRoleByRoleID($roleID); }
        public function setId($id) { $this->user->setId($id); }
        public function setUsername($username) { $this->user->setUsername($username); }
        public function setPassword($password) { $this->user->setPassword($password); }
        public function setFullName($fullName) { $this->user->setFullName($fullName); }
        public function setPhoneNumber($phoneNumber) { $this->user->setPhoneNumber($phoneNumber); }
        public function setEmail($email) { $this->user->setEmail($email); }
        public function setRegistrationDateTime($registrationDateTime) { $this->user->setRegistrationDateTime($registrationDateTime); }
        public function setLastLoginDateTime($lastLoginDateTime) { $this->user->setLastLoginDateTime($lastLoginDateTime); }
        public function setRole($role) { $this->user->setRole($role); }
        public function setRoleByRoleID($roleID) { $this->user->setRoleByRoleID($roleID); }
    }
?>

