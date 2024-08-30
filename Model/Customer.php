<?php

    require_once 'User.php';
    require_once 'Membership.php';

    class Customer extends User {
        private $membership;
        //private $lastPaymentMethodUsed;

        public function __construct($id, $username, $password, $firstName, $lastName, $phoneNumber, $email, Roles $role, Membership $membership) {
            parent::__construct($id, $username, $password, $firstName, $lastName, $phoneNumber, $email, $role);
            $this->membership = $membership;
        }

        public function getMembership() {
            return $this->membership;
        }

        public function setMembership(Membership $membership) {
            $this->membership = $membership;
        }

    }
?>

