<?php

    interface UserInterface {
        public function getId();
        public function getUsername();
        public function getPassword();
        public function getFirstName();
        public function getLastName();
        public function getPhoneNumber();
        public function getEmail();
        public function getRole();
        public function navigateToBasedOnRoles();
    }

?>
