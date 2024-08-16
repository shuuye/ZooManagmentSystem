<?php

require_once 'User.php';
require_once 'Membership.php';

class Customer extends User {
    private $membership;

    public function __construct($id, $username, $password, $firstName, $lastName, $phoneNumber, $email, $membership) {
        parent::__construct($id, $username, $password, $firstName, $lastName, $phoneNumber, $email);
        $this->membership = $membership;
    }

    // Getter and setter for membership
    public function getMembership() {
        return $this->membership;
    }

    public function setMembership($membership) {
        $this->membership = $membership;
    }
}
?>

