<?php

    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInUser();

    require_once 'StaffUserManagementCtrl.php';
    require_once __DIR__ . '/../../Model/User/LeaveApplicationModel.php';
    
    class LeaveApplicationManagementCtrl extends StaffUserManagementCtrl{
        private $id;
        private $reason;
        private $evidencePhoto;
        private $leaveDate;
        private $leaveStartTime;
        private $leaveEndTime;
        private $leaveApplicationInputData;
        
        public function addLeaveApplicationIntoDB($id, $reason, $evidencePhoto, $leaveDate, $leaveStartTime, $leaveEndTime){
            $leaveApplicationModel = new LeaveApplicationModel();
            $leaveApplicationModel->addLeaveApplicationIntoDB($id, $reason, $evidencePhoto, $leaveDate, $leaveStartTime, $leaveEndTime);
        }
        
        public function removeLeaveApplication($id, $leaveDate, $leaveStartTime, $leaveEndTime){
            $leaveApplicationModel = new LeaveApplicationModel();
            $removed = $leaveApplicationModel->removeLeaveApplicationFromDB($id, $leaveDate, $leaveStartTime, $leaveEndTime);
            
            return $removed;
        }
        
        public function confirmLeaveApplicationDeletion(){
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $leaveDate = isset($_POST['leaveDate']) ? $_POST['leaveDate'] : null;
            $leaveStartTime = isset($_POST['leaveStartTime']) ? $_POST['leaveStartTime'] : null;
            $leaveEndTime = isset($_POST['leaveEndTime']) ? $_POST['leaveEndTime'] : null;
            $evidencePhoto = isset($_POST['evidencePhoto']) ? $_POST['evidencePhoto'] : null;

            // Ensure $evidencePhoto is a valid file
            if ($evidencePhoto && file_exists($evidencePhoto)) {
                // Attempt to delete the file
                if (unlink($evidencePhoto)) {
                    echo "Photo removed successfully.";
                } else {
                    echo "Failed to remove the photo.";
                }
            } else {
                echo "Photo does not exist.";
            }

            // Proceed with the removal of the leave application
            if ($id && $leaveDate && $leaveStartTime && $leaveEndTime) {
                $removed = $this->removeLeaveApplication($id, $leaveDate, $leaveStartTime, $leaveEndTime);

                if ($removed) {
                    $_SESSION['leaveApplicationRemovedSuccessfully'] = 'Leave Application Removed Successfully.';
                    header("Location: index.php?controller=user&action=leaveApplicationManagement&sort=leaveDate&filter=week");
                    exit(); // Ensure script ends after redirect
                }
            } else {
                // Handle the case where required parameters are missing
                echo "Missing required parameters.";
            }
        }
        
        private function deleteLeaveApplicationConfirmatinMsg($leaveApplicationToRemove){
            $data = [
                'pageTitle' => 'Delete Leave Application Confirmation',
                'leaveApplicationToRemove' => $leaveApplicationToRemove,
                'action' => 'delete',
            ];
                        
            $view = ['deleteLeaveApplicationConfirmationView'];
            $this->renderView($view, $data);
        }
        
        public function deleteLeaveApplication(){
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            $leaveDate = isset($_GET['leaveDate']) ? $_GET['leaveDate'] : null;
            $leaveStartTime = isset($_GET['leaveStartTime']) ? $_GET['leaveStartTime'] : null;
            $leaveEndTime = isset($_GET['leaveEndTime']) ? $_GET['leaveEndTime'] : null;
            
            $leaveApplicationModel = new LeaveApplicationModel();
            $leaveApplicationToRemove = $leaveApplicationModel->getLeaveApplicationFromDB($id, $leaveDate, $leaveStartTime, $leaveEndTime);
            
            $this->deleteLeaveApplicationConfirmatinMsg($leaveApplicationToRemove);
            
        }
        
        public function addLeaveApplication(){
            $result = $this->processUsers();
            $workingSchedulesArray = $this->processWorkingSchedule();
            
            //set role for each data
            $staffsArray = $result['staffsArray'];
            
            //set render data (set the user, customer, admin)
            $data = $this->setRenderData('Leave Application Management Panel');
            $data['workingSchedulesArray'] = $workingSchedulesArray['workingSchedulesArray'];
            $data['staffsArray'] = $staffsArray;
            $view = ['leaveApplicationInputFormView'];
            //display/render the user view

            $this->renderView($view,$data);
        }

        public function leaveApplicationManagement(){
            $leaveApplicationsArray = $this->processLeaveApplication();
            
            //set render data (set the user, customer, admin)
            $data = $this->setRenderData('Leave Application Management Panel');
            $data['leaveApplicationsArray'] = $leaveApplicationsArray['leaveApplicationsArray'];
            $view = ['adminTopNavHeader','userManagementTopNav','staffManagementTopNav','leaveApplicationManagementView'];
            //display/render the user view

            $this->renderView($view,$data);
        }
        
        public function updateLeaveApprovalStatus() {
            // Retrieve and validate parameters
            $params = $this->getLeaveApprovalParameters();

            if ($this->areParametersValid($params)) {
                $this->processLeaveApprovalUpdate($params);
            } else {
                // Handle the case where required parameters are missing
                echo "Missing required parameters.";
            }
        }

        private function getLeaveApprovalParameters() {
            return [
                'id' => isset($_GET['id']) ? $_GET['id'] : null,
                'leaveDate' => isset($_GET['leaveDate']) ? $_GET['leaveDate'] : null,
                'leaveStartTime' => isset($_GET['leaveStartTime']) ? $_GET['leaveStartTime'] : null,
                'leaveEndTime' => isset($_GET['leaveEndTime']) ? $_GET['leaveEndTime'] : null,
                'approved' => isset($_GET['approved']) ? filter_var($_GET['approved'], FILTER_VALIDATE_BOOLEAN) : null
            ];
        }

        private function areParametersValid($params) {
            return $params['id'] && $params['leaveDate'] && $params['leaveStartTime'] && $params['leaveEndTime'] && $params['approved'] !== null;
        }

        private function processLeaveApprovalUpdate($params) {
            $leaveApplicationModel = new LeaveApplicationModel();
            $attendanceModel = new AttendanceModel();

            if ($leaveApplicationModel->updateApprovedStatusFromLeaveApplicationInDB(
                $params['id'],
                $params['leaveDate'],
                $params['leaveStartTime'],
                $params['leaveEndTime'],
                $params['approved']
            )) {
                $statusID = $params['approved'] ? 4 : 1;
                $attendanceModel->updateStatusIDFromAttendanceByPrimaryKey(
                    $params['id'],
                    $params['leaveDate'],
                    $params['leaveStartTime'],
                    $params['leaveEndTime'],
                    $statusID
                );

                header("Location: index.php?controller=user&action=leaveApplicationManagement&sort=leaveDate&filter=week");
                exit(); // Always use exit() after header redirection
            }
        }
        
        private function actionAfterLeaveAppliedSuccessfully(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['leaveAppliedSuccessfully'] = 'Leave Applied Successfully.';
            if ($_SESSION['currentUserModel']['role']['roleID'] == 3){
                header("Location: index.php?controller=user&action=staffLeaveApplication");
            }else{
                header("Location: index.php?controller=user&action=leaveApplicationManagement&sort=leaveDate&filter=week");
            }
            
            exit();
            
        }
        
        private function leaveAppliedFailedAction(){
            $this->leaveApplicationInputData['inputFormErr'] = 'Leave Applied Failed';
            $_SESSION['leaveApplicationInputData'] = $this->leaveApplicationInputData;
            header("Location: index.php?controller=user&action=addLeaveApplication");
            exit();
        }
        
        private function saveEvidencePhoto() {
            // Define the directory where the files will be uploaded
            $uploadDir = 'C:/xampp/htdocs/ZooManagementSystem/assests/UserImages/evidence_photos/';
            // Ensure the upload directory exists, if not, create it
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true); // Create directory with appropriate permissions
            }

            // Check if the file was uploaded without errors
            if (!isset($this->evidencePhoto) || $this->evidencePhoto['error'] !== UPLOAD_ERR_OK) {
                return "Failed to upload the file. Please try again.";
            }

            // Get original file name and temporary file path
            $fileTmpPath = $this->evidencePhoto['tmp_name'];
            $originalFileName = basename($this->evidencePhoto['name']); // Use basename to sanitize file name

            // Create a unique file name using the original file extension
            $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
            $newFileName = uniqid('evidence_', true) . '.' . $fileExtension;

            // Set the target file path
            $targetFilePath = $uploadDir . $newFileName;

            // Move the uploaded file from the temp directory to the upload directory
            if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                // File was successfully moved, return the path where the file was saved
                return $targetFilePath;
            } else {
                // Handle error if the file was not moved
                return "Failed to save the evidence photo. Please check file permissions.";
            }
        }
        
        private function afterLeaveApplicationInput($validInput){
            $savedPhotoPath = null; // Initialize variable to store the saved photo path

            // Proceed with saving the evidence photo only if the input is valid
            if ($validInput) {
                // Save the evidence photo and capture the result (file path or error message)
                $savedPhotoPath = $this->saveEvidencePhoto();

                // Check if the evidence photo was saved successfully
                if (is_string($savedPhotoPath) && strpos($savedPhotoPath, 'Failed') === false) {
                    // Update $this->evidencePhoto with the saved photo path to be stored in the database
                    $this->evidencePhoto = $savedPhotoPath;

                    // Create new leave application in the database with the saved photo path
                    $this->addLeaveApplicationIntoDB($this->id, $this->reason, $this->evidencePhoto, $this->leaveDate, $this->leaveStartTime, $this->leaveEndTime);

                    // Handle actions after successfully applying the leave
                    $this->actionAfterLeaveAppliedSuccessfully();
                } else {
                    // If the photo failed to save, mark the input as invalid and handle failure
                    $this->leaveApplicationInputData['evidencePhotoErr'] = "Failed to upload evidence photo.";
                    $this->leaveAppliedFailedAction();
                }
            } else {
                // If the input is invalid, handle failure
                $this->leaveAppliedFailedAction();
            }
        }
        
        private function validateEvidencePhoto() {
            // Check if the file is uploaded
            if (is_null($this->evidencePhoto)) {
                return "Evidence photo is required to be uploaded.";
            }

            // Check file properties
            $fileTmpPath = $this->evidencePhoto['tmp_name'];
            $fileName = $this->evidencePhoto['name'];
            $fileSize = $this->evidencePhoto['size'];
            $fileType = $this->evidencePhoto['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Allowed file extensions
            $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');

            // Check file extension
            if (!in_array($fileExtension, $allowedfileExtensions)) {
                return "Upload failed. Allowed file types: jpg, jpeg, png, gif.";
            }

            // Check file size (limit set to 2MB)
            if ($fileSize > 2097152) { // 2MB limit
                return "The file size exceeds the limit of 2MB.";
            }

            return null; // Return null if validation passed
        }
        
        private function validateReason(){
            if(empty($this->reason)){
                return "Reason is required to be filled up.";
            }
        }
        
        private function validateStaffId(){
            if(empty($this->id)){
                return "A staff for leave is required.";
            }
        }
        
        private function validateLeaveSchedule(){
            if(empty($_POST['leaveSchedule'])){
                return "The Leave Schedule is required.";
            }
        }
        
        private function checkLeaveApplicationInput() {
            $data = [
                'reasonErr' => $this->validateReason(),
                'evidencePhotoErr' => $this->validateEvidencePhoto(),
                'staffIdErr' => $this->validateStaffId(),
                'leaveScheduleErr' => $this->validateLeaveSchedule(),
            ];

            $leaveApplicationModel = new LeaveApplicationModel();
            if ($leaveApplicationModel->isLeaveExistInLeaveApplicationDB($this->id, $this->leaveDate, $this->leaveStartTime, $this->leaveEndTime)) {
                $data['leaveApplicationExistedErr'] = 'Leave for ' . $this->leaveDate . ' from ' . $this->leaveStartTime . ' to ' . $this->leaveEndTime . ' is already applied!';
            }

            $this->leaveApplicationInputData = $data;
        }
        
        private function checkEmptyLeaveApplicationInputData() {
            foreach ($this->leaveApplicationInputData as $key => $value) {
                if (!empty($value)) {
                    return false; // If any value is not empty, return false
                }
            }
            return true; // If all values are empty, return true
        }
        
        private function setApplyLeaveInputTemp() {
            $this->id = $_POST['staffId'] ?? '';
            $this->reason = $_POST['reason'] ?? '';

            // Handle file upload
            if (isset($_FILES['evidencePhoto']) && $_FILES['evidencePhoto']['error'] === UPLOAD_ERR_OK) {
                $this->evidencePhoto = $_FILES['evidencePhoto'];
            } else {
                $this->evidencePhoto = null; // If the file is not uploaded correctly
            }

            list($this->leaveDate, $this->leaveStartTime, $this->leaveEndTime) = explode('|', $_POST['leaveSchedule']);
        }
        
        public function applyNewLeave(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $validInput = false;
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->leaveApplicationInputData = null;
                $this->setApplyLeaveInputTemp();
                $this->checkLeaveApplicationInput();
                if($this->checkEmptyLeaveApplicationInputData()){
                    $validInput = true;
                }
                
            }
            
            $this->afterLeaveApplicationInput($validInput);
        }
    }

