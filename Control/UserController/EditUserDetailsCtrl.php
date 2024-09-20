<?php

    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForLoggedInEditPermissionAdmin();

    require_once 'UserController.php';
    require_once __DIR__ . '/../../Model/User/CustomerModel.php';
    require_once __DIR__ . '/../../Model/User/AdminModel.php';
    
    class EditUserDetailsCtrl extends UserController{
        
        private function afterEdit($editedRole = 0){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            switch ($editedRole) {
                case 1:
                    $_SESSION['editSuccess'] = 'Admin Details Edited Successfully.';
                    break;
                case 2:
                    $_SESSION['editSuccess'] = 'Customer Details Edited Successfully.';
                    break;
                case 3:
                    $_SESSION['editSuccess'] = 'Staff Details Edited Successfully.';
                    break;
            }
            
            
            $navigateWho = isset($_SESSION['currentUserModel']) ? $_SESSION['currentUserModel']['role']['roleID'] : null;

            $this->navigateUserTo($navigateWho);
        }
        
        private function updateCurrentAdminSession($adminType){
            $_SESSION['currentUserModel']['adminType'] = $adminType;
            switch ($adminType){
                case 'ReadOnlyAdmin':
                    $_SESSION['currentUserModel']['permissions'] = null;
                    $_SESSION['currentUserModel']['permissions'] = ['read'];
                    break;
                case 'EditorAdmin':
                    $_SESSION['currentUserModel']['permissions'] = null;
                    $_SESSION['currentUserModel']['permissions'] = ['read' , 'edit'];
                    break;
                case 'SuperAdmin':
                    $_SESSION['currentUserModel']['permissions'] = null;
                    $_SESSION['currentUserModel']['permissions'] = ['read' , 'edit', 'manage admin'];
                    break;
            }
        }
        
        public function submitAdminDetailsForm() {
            if (!$this->isPostRequest()) {
                return; // Exit early if not a POST request
            }

            $id = $this->getPostData('id');
            $adminType = $this->getPostData('adminType');

            $userDetails = $this->getUserDetailsByIDFromDB($id);
            $this->initializeSession();

            if ($this->isAdminRole($userDetails['roleID']) && !$this->hasAdminEditPermission()) {
                $this->redirectNoPermission();
            }

            $this->updateAdminType($id, $adminType);
            $this->updateSessionIfNecessary($id, $adminType);

            $this->afterEdit(1);
        }

        private function isPostRequest() {
            return $_SERVER['REQUEST_METHOD'] === 'POST';
        }

        private function getPostData($key) {
            return isset($_POST[$key]) ? htmlspecialchars($_POST[$key]) : '';
        }

        private function updateAdminType($id, $adminType) {
            $adminModel = new AdminModel();

            if ($adminModel->getAdminTypeByID($id) !== null) {
                $adminModel->updateDBColumnByID('type', $adminType, $id);
            }
        }

        private function updateSessionIfNecessary($id, $adminType) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (isset($_SESSION['currentUserModel'])) {
                if ($_SESSION['currentUserModel']['id'] == $id && $_SESSION['currentUserModel']['adminType'] != $adminType) {
                    $this->updateCurrentAdminSession($adminType);
                }
            }
        }

        public function submitCustomerDetailsForm(){
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return; // Exit early if not a POST request
            }
            // Retrieve form data from $_POST
            $id = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : '';
            $membershipID = isset($_POST['membershipID']) ? htmlspecialchars($_POST['membershipID']) : '';

            $customerModel = new CustomerModel();
            
            if($customerModel->getMembershipIDByID($id) != null){
                    $customerModel->updateDBColumnByID('membershipID', $membershipID, $id);
            }

            $this->afterEdit(3);
        }
        
        public function submitStaffDetailsForm(){
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return; // Exit early if not a POST request
            }
            // Retrieve form data from $_POST
            $id = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : '';
            $position = isset($_POST['position']) ? htmlspecialchars($_POST['position']) : '';

            $staffModel = new StaffModel();
            
            if($staffModel->getPositionByID($id) != null){
                    $staffModel->updateDBColumnByID('position', $position, $id);
            }

            $this->afterEdit(2);
        }
                
        private function initializeSession() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        }

        private function isAdminRole($roleID) {
            return $roleID == 1;
        }

        private function hasAdminEditPermission() {
            return isset($_SESSION['currentUserModel']['role']['roleID']) &&
                   $_SESSION['currentUserModel']['role']['roleID'] == 1 &&
                   in_array('manage admin', $_SESSION['currentUserModel']['permissions']);
        }

        private function redirectNoPermission() {
            $_SESSION['editingFailed'] = 'Admin Details Editing Failed, U have no permission to manage admin.';
            header('Location: index.php?controller=user&action=userManagementMainPanel');
            exit;
        }
    }

