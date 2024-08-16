<?php

    class Membership {
        private $id;
        private $name;
        private $fee;
        private $discountOffered;
        
        public function __construct($id, $name, $fee, $discountOffered) {
            $this->id = $id;
            $this->name = $name;
            $this->fee = $fee;
            $this->discountOffered = $discountOffered;
        }
        
        public function getId() {
            return $this->id;
        }

        public function getName() {
            return $this->name;
        }

        public function getFee() {
            return $this->fee;
        }

        public function getDiscountOffered() {
            return $this->discountOffered;
        }

        public function setId($id): void {
            $this->id = $id;
        }

        public function setName($name): void {
            $this->name = $name;
        }

        public function setFee($fee): void {
            $this->fee = $fee;
        }

        public function setDiscountOffered($discountOffered): void {
            $this->discountOffered = $discountOffered;
        }



    }
    
?>

