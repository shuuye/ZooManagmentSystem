<?php

    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForLoggedInEditPermissionAdmin();

    require_once __DIR__ . '/../../Model/User/CustomerModel.php';
    require_once __DIR__ . '/../../Model/User/AdminModel.php';
    require_once 'UserController.php';
    
    class DeleteDifferentTypeUsersCtrl extends UserController{
        
        private function afterDelete($deleteFailed = false){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if($deleteFailed){
                $_SESSION['deleteFailed'] = 'User Deletion Failed.';
            }else{
                $_SESSION['deleteSuccess'] = 'User Deleted Successfully.';
            }
            
            $navigateWho = isset($_SESSION['currentUserModel']) ? $_SESSION['currentUserModel']['role']['roleID'] : null;

            $this->navigateUserTo($navigateWho);
        }
        
        private function deleteConfirmatinMsg($userDetails){
            $data = [
                'pageTitle' => 'Delete Confirmation',
                'selectedUser' => $userDetails,
                'action' => 'delete',
            ];
                        
            $view = ['deleteConfirmationMsgView'];
            $this->renderView($view, $data);

        }
        
        private function removeUserData($userDetails, $userId) {
            $deleteFail = false;

            // Remove role-specific data
            $deleteFail = $this->removeRoleSpecificData($userDetails['roleID'], $userId) ? false : true;

            // Remove from user table
            $deleteFail = !$this->isExistInUserDB($userDetails['id'], 'id') ? true : $deleteFail;
            if (!$deleteFail) {
                $this->removeUserByID($userId);
            }

            // Finalize deletion process
            $this->afterDelete($deleteFail);
        }

        private function removeRoleSpecificData($roleID, $userId) {
            switch ($roleID) {
                case 1:
                    return $this->removeAdminData($userId);
                case 2:
                    return $this->removeCustomerData($userId);
                case 3:
                    return $this->removeStaffData($userId);
                default:
                    return false;
            }
        }

        private function removeAdminData($userId) {
            $adminModel = new AdminModel();
            return $adminModel->isExistInAdminDB($userId, 'id') ? $adminModel->removeAdminByID($userId) : false;
        }

        private function removeCustomerData($userId) {
            $customerModel = new CustomerModel();
            return $customerModel->isExistInCustomerDB($userId, 'id') ? $customerModel->removeCustomerByID($userId) : false;
        }

        private function removeStaffData($userId) {
            $staffModel = new StaffModel();
            return $staffModel->isExistInStaffDB($userId, 'id') ? $staffModel->removeStaffByID($userId) : false;
        }
        
        public function confirmDeletion($id = 0){
            // Check for POST data first (since form uses POST)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userId = isset($_POST['userID']) ? $_POST['userID'] : null;
            }
            // Fallback to GET if POST is not set
            elseif ($id == 0) {
                $userId = isset($_GET['id']) ? $_GET['id'] : null;
            } else {
                $userId = $id;
            }
            $userDetails = $this->getUserDetailsByIDFromDB($userId);
            $this->initializeSession();

            if ($this->isAdminRole($userDetails['roleID'])) {
                if (!$this->hasAdminDeletePermission()) {
                    $this->redirectNoPermission();
                }
            }
            $this->removeUserData($userDetails, $userId);
        }
        
        public function deleteUser($id = 0) {
            $userId = $this->getUserId($id);

            $userDetails = $this->getUserDetailsByIDFromDB($userId);

            $this->deleteConfirmatinMsg($userDetails);
        }

        private function getUserId($id) {
            return ($id == 0) ? (isset($_GET['id']) ? $_GET['id'] : null) : $id;
        }

        private function initializeSession() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        }

        private function isAdminRole($roleID) {
            return $roleID == 1;
        }

        private function hasAdminDeletePermission() {
            return isset($_SESSION['currentUserModel']['role']['roleID']) &&
                   $_SESSION['currentUserModel']['role']['roleID'] == 1 &&
                   in_array('manage admin', $_SESSION['currentUserModel']['permissions']);
        }

        private function redirectNoPermission() {
            $_SESSION['deleteFailed'] = 'Deletion Failed, U have no permission to manage admin.';
            header('Location: index.php?controller=user&action=userManagementMainPanel');
            exit;
        }
    }

