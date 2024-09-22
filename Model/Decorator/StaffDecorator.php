<?php
    /*Author Name: Chew Wei Seng*/
    require_once 'UserDecorator.php';
    require_once __DIR__ . '/../User/StaffModel.php';
    
    class StaffDecorator extends UserDecorator{
        protected $staffDetails;
        
        public function __construct(UserInterface $user) {
            parent::__construct($user);
            $this->setStaffUserDetails();
        }
        
        private function getStaffPosition($userID) {
            // Use the inherited database connection
            $staffModel = new StaffModel();
            $position = $staffModel->getPositionByID($userID);
                        
            return $position ?: null; // Return null if no position is found
            
        }
        
        private function setStaffUserDetails() {
            $details = parent::getCurrentUserDetails();
            if ($details['role']['roleName'] === 'staff') {
                
                // Fetch and add position to the details
                $position = $this->getStaffPosition($details['id']); 
                $details['position'] = $position;
            }
            $this->staffDetails = $details;
        }

        public function getCurrentUserDetails() {
            return $this->staffDetails; // Return the stored user details
        }
    }

