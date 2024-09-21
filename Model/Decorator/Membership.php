<?php
class Membership {
    // Protected attributes
    protected $membershipID;
    protected $membershipType;
    protected $fee;
    protected $discountOffered;

    // Constructor to initialize the class
    public function __construct($membershipID = null, $membershipType = null, $fee = null, $discountOffered = null) {
        $this->membershipID = $membershipID;
        $this->membershipType = $membershipType;
        $this->fee = $fee;
        $this->discountOffered = $discountOffered;
    }

    // Method to apply discount
    public function applyDiscount($originalPrice) {
        if ($this->discountOffered) {
            return $originalPrice - ($originalPrice * ($this->discountOffered / 100));
        }
        return $originalPrice;
    }

    // Method to check membership validity (for future expansion)
    public function isValidMembership() {
        // Logic for checking validity (e.g., check expiration date)
        return true;
    }

    public function setMembershipID($membershipID) {
        $this->membershipID = $membershipID;
    }

    public function setMembershipType($membershipType) {
        $this->membershipType = $membershipType;
    }

    public function setFee($fee) {
        $this->fee = $fee;
    }

    public function setDiscountOffered($discountOffered) {
        $this->discountOffered = $discountOffered;
    }

    public function getMembershipID() {
        return $this->membershipID;
    }

    public function getMembershipType() {
        return $this->membershipType;
    }

    public function getFee() {
        return $this->fee;
    }

    public function getDiscountOffered() {
        return $this->discountOffered;
    }
}
?>
