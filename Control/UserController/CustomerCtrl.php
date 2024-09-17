<?php

    require_once __DIR__ . '/../../Model/User/MembershipModel.php';
    require_once __DIR__ . '/../../Model/User/CustomerModel.php';
    require_once 'UserController.php';
    
    class CustomerCtrl extends UserController{
        
        public function __construct() {
            parent::__construct();
            
        }
        
        public function purchaseSuccess(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $membershipID = isset($_GET['membershipID']) ? htmlspecialchars($_GET['membershipID']) : null;
            
            //update the membersip of the user
            $_SESSION['membershipPurchaseSuccessFully'] = 'Membership Purchase Successfully';
            $customerModel = new CustomerModel();
            $updated = $customerModel->updateDBColumnByID('membershipID', $membershipID, $_SESSION['currentUserModel']['id']);
            $userObj = $this->setUserObj($_SESSION['currentUserModel']['id']);
            $_SESSION['currentUserModel'] = $this->setCustomerDetails($userObj);
            if($updated){
                header('Location: index.php?controller=user&action=showMembership');
            }
                        
        }
        
        private function displayMembershipPurchase($selectedMembership){
                     
            $view = [
                'membershipPurchaseView',
            ];
            
            $data = $this->setRenderData('Membership Purchase');
            $data['selectedMembership'] = $selectedMembership;
            
            // Render the user view
            $this->renderView($view, $data);
        }
        
        public function purchaseMembership(){
            $membershipID = isset($_GET['membershipID']) ? htmlspecialchars($_GET['membershipID']) : null;
            $membershipModel = new MembershipModel();
            $membership = $membershipModel->getMembershipByMembershipID($membershipID);
            
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if(!isset($_SESSION['currentUserModel'])){
                header('Location: index.php?controller=user&action=login');
            }
            
            if($_SESSION['currentUserModel']['membership']['membershipID'] == $membershipID){
                $_SESSION['membershipAldBuy'] = 'The Selected Member ' . $membership['membershipType'] . ' is Already Bought';
                header('Location: index.php?controller=user&action=showMembership');
            }
            
            $this->displayMembershipPurchase($membership);
        }
        
        public function showMembership(){
            $membershipModel = new MembershipModel();
            $membershipArray = $membershipModel->getAllMembership();
                        
            $view = [
                '../clientTopNavHeader',
                'membershipView',
            ];
            
            $data = $this->setRenderData('Membership');
            $data['membershipArray'] = $membershipArray;
            
            // Render the user view
            $this->renderView($view, $data);
        }
    }