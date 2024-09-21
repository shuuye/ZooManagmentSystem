<?php

    require_once 'UserDecorator.php';
    require_once __DIR__ . '/../User/MembershipModel.php';
    require_once __DIR__ . '/../User/CustomerModel.php';
    require_once __DIR__ . '/../Decorator/Membership.php';

    
    class CustomerDecorator extends UserDecorator{
        protected $customerDetails;
        protected Membership $membership;
        
        public function __construct(UserInterface $user) {
            parent::__construct($user);
            $this->membership = new Membership();
            $this->setCustomerUserDetails();
        }
        
        private function setMembershipDetails($membershipID){
            $membershipModel = new MembershipModel();
            $memberDetails = $membershipModel->getMembershipByMembershipID($membershipID);
            
            // Initialize the membership if not already initialized
            if (!$this->membership) {
                $this->membership = new Membership();
            }
        
            $this->membership->setMembershipID($memberDetails['membershipID']);
            $this->membership->setMembershipType($memberDetails['membershipType']);
            $this->membership->setFee($memberDetails['fee']);
            $this->membership->setDiscountOffered($memberDetails['discountOffered']);
            
            return $this->membership;
        }
        
        private function getCustomerMembership($userID) {
            // Use the inherited database connection
            $customerModel = new CustomerModel();
            $membershipID = $customerModel->getMembershipIDByID($userID);
            
            $membership = $this->setMembershipDetails($membershipID);
            
            return $membership ?: null; // Return null if no membership is found
            
        }
        
        private function setCustomerUserDetails() {
            $details = parent::getCurrentUserDetails();
            if ($details['role']['roleName'] === 'customer') {
                // Fetch and add membership to the details
                $membership = $this->getCustomerMembership($details['id']); 
                $details['membership'] = [
                    'membershipID' => $this->membership ? $this->membership->getMembershipID() : null,
                    'membershipType' => $this->membership ? $this->membership->getMembershipType() : null,
                    'fee' => $this->membership ? $this->membership->getFee() : null,
                    'discountOffered' => $this->membership ? $this->membership->getDiscountOffered() : null,
                ];
            }
            
            $this->customerDetails = $details;
        }


        public function getCurrentUserDetails() {
            return $this->customerDetails; // Return the stored user details
        }
    }

