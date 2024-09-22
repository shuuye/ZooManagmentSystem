<?php
    /*Author Name: Chew Wei Seng*/
    require_once 'UserDecorator.php';
    require_once __DIR__ . '/../User/AdminModel.php';
    
    class AdminDecorator extends UserDecorator{
        protected $adminDetails;
        
        public function __construct(UserInterface $user) {
            parent::__construct($user);
            $this->setAdminUserDetails();
        }
        
        private function getAdminType($userID) {
            $adminModel = new AdminModel();
            $adminType = $adminModel->getAdminTypeByID($userID);
            
            return $adminType ?: null; // Return null if no admin type is found
            
        }
        
        private function setAdminUserDetails() {
            $details = parent::getCurrentUserDetails();
            if ($details['role']['roleName'] === 'admin') {
                
                // Fetch and add admin type to the details
                $adminType = $this->getAdminType($details['id']); // Assuming 'id' is the userID
                $details['adminType'] = $adminType;
            }
            $this->adminDetails = $details;
        }

        public function getCurrentUserDetails() {
            return $this->adminDetails; // Return the stored user details
        }
    }
