<?php
    class User {
        protected $id;
        protected $username;
        protected $password;
        protected $firstName;
        protected $lastName;
        protected $phoneNumber;
        protected $email;

        
        public function __construct($id, $username, $password, $firstName, $lastName, $phoneNumber, $email) {
            $this->id = $id;
            $this->username = $username;
            $this->password = $password;
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->phoneNumber = $phoneNumber;
            $this->email = $email;
        }

        public function login() {
            // Implement login logic here
        }

        public function register() {
            // Implement register logic here
        }

        //public function forgotPassword() {
            // Implement forgot password logic here
        //}

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

        public function setId($id): void {
            $this->id = $id;
        }

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

    }
?>

