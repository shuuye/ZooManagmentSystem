<?php

    require_once 'UserDecorator.php';
    require_once __DIR__ . '/../User/MembershipModel.php';
    require_once __DIR__ . '/../User/CustomerModel.php';
    
    class CustomerDecorator extends UserDecorator{
        protected $customerDetails;
        protected $membership;
        
        public function __construct(UserInterface $user) {
            parent::__construct($user);
            $this->setCustomerUserDetails();
        }
        
        private function setMembershipDetails($membershipID){
            $membershipModel = new MembershipModel();
            $memberDetails = $membershipModel->getMembershipByMembershipID($membershipID);
            
            return $memberDetails;
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
                $details['membership'] = $membership;
            }
            $this->customerDetails = $details;
        }

        public function getCurrentUserDetails() {
            return $this->customerDetails; // Return the stored user details
        }
    }

