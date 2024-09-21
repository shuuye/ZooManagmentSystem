<?php

    interface UserInterface {
        public function authUser($username, $password); // Authenticates user with username and password
        public function addNewUser($newUsername, $newPassword, $newFullName, $newPhoneNumber, $newEmail, $newRoleID); // Registers a new user
        public function getCurrentUserDetails();
        
        // Getters
        public function getId();
        public function getUsername();
        public function getPassword();
        public function getFullName();
        public function getPhoneNumber();
        public function getEmail();
        public function getRegistrationDateTime();
        public function getLastLoginDateTime();
        public function getRole();
        public function getRoleByRoleID($roleID);

        // Setters
        public function setId($id);
        public function setUsername($username);
        public function setPassword($password);
        public function setFullName($full_name);
        public function setPhoneNumber($phone_number);
        public function setEmail($email);
        public function setRegistrationDateTime($registrationDateTime);
        public function setLastLoginDateTime($lastLoginDateTime);
        public function setRole($role);
        public function setRoleByRoleID($roleID);
    }

?>
