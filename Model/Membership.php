<?php

    class Membership {
        private $membershipID;
        private $membershipType;
        private $fee;
        private $discountOffered;
        

        public function __construct($membershipID, $membershipType, $fee, $discountOffered) {
            $this->membershipID = $membershipID;
            $this->membershipType = $membershipType;
            $this->fee = $fee;
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

