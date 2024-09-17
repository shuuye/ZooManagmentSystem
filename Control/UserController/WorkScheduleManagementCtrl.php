<?php

    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInUser();

    require_once 'StaffUserManagementCtrl.php';
    require_once 'AttendanceManagementCtrl.php';
    require_once __DIR__ . '/../../Model/User/WorkingScheduleModel.php';
    require_once __DIR__ . '/../../Model/User/AttendanceModel.php';

    class WorkScheduleManagementCtrl extends StaffUserManagementCtrl{
        protected $staffId;
        protected $workingDate;
        protected $workingStartingTime;
        protected $workingOffTime;
        
        protected $workingScheduleInputData;
        
        public function __construct() {
            parent::__construct();

        }
        
        public function workingScheduleManagement(){
            $result = $this->processUsers();
            
            //set role for each data
            $staffsArray = $result['staffsArray'];
            $workingSchedulesArray = $this->processWorkingSchedule();
            $attendancesArray = $this->processWorkingAttendance();
            $attendancesStatusArray = $this->processAttendanceStatus();
            
            //set render data (set the user, customer, admin)
            $data = $this->setRenderData('Working Schedule Management Panel');
            $data['staffsArray'] = $staffsArray;
            $data['workingSchedulesArray'] = $workingSchedulesArray['workingSchedulesArray'];
            $data['attendancesArray'] = $attendancesArray['attendancesArray'];
            $data['attendancesStatusArray'] = $attendancesStatusArray['attendancesStatusArray'];
            $view = ['adminTopNavHeader','userManagementTopNav','staffManagementTopNav','workingScheduleManagementView'];
            //display/render the user view

            $this->renderView($view,$data);
        }
        
        public function editWorkingSchedule() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (isset($_SESSION['workingScheduleInputData'])) {
                $this->workingScheduleInputData = $_SESSION['workingScheduleInputData'];
                unset($_SESSION['workingScheduleInputData']); // Clear the session data
            }

            $staffId = isset($_GET['id']) ? $_GET['id'] : null;
            $workingDate = isset($_GET['workingDate']) ? $_GET['workingDate'] : null;
            $workingStartingTime = isset($_GET['workingStartingTime']) ? $_GET['workingStartingTime'] : null;
            $workingOffTime = isset($_GET['workingOffTime']) ? $_GET['workingOffTime'] : null;

            // Fetch the working schedule from the database
            $workingScheduleModel = new WorkingScheduleModel();
            $workingScheduleDetails = $workingScheduleModel->getWorkingScheduleByPrimaryKey($staffId, $workingDate, $workingStartingTime, $workingOffTime);

            if (empty($workingScheduleDetails)) {
                // If no matching schedule is found, pass an empty array or default values
                $selectedWorkingSchedule = [
                    'id' => $staffId,
                    'workingDate' => $workingDate, // Keep the user's input for the working date
                    'workingStartingTime' => $workingStartingTime,
                    'workingOffTime' => $workingOffTime
                ];
                // Optionally, you can set a flag to indicate that no data was found
                $noDataFound = true;
            } else {
                // If data is found, use it
                $selectedWorkingSchedule = $workingScheduleDetails[0];
                $noDataFound = false;
            }

            // Pass to view
            $data = [
                'pageTitle' => 'Working Schedule Management Panel',
                'selectedWorkingSchedule' => $selectedWorkingSchedule, // Use either fetched data or the empty structure
                'staffsArray' => $this->processUsers()['staffsArray'],
                'action' => 'edit',
                'noDataFound' => $noDataFound, // Pass the flag to the view
                'workingScheduleInputData' => $this->workingScheduleInputData
            ];

            $view = ['workingScheduleInputFormView'];
            $this->renderView($view, $data);
        }

        
        public function createNewWorkSchedule(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if (isset($_SESSION['workingScheduleInputData'])) {
                $this->workingScheduleInputData = $_SESSION['workingScheduleInputData'];
                unset($_SESSION['workingScheduleInputData']); // Clear the session data
            }
            
            $result = $this->processUsers();
            
            //set role for each data
            $staffsArray = $result['staffsArray'];
            
            //set render data (set the user, customer, admin)
            $data = $this->setRenderData('Working Schedule Management Panel');
            $data['staffsArray'] = $staffsArray;
            $data['workingScheduleInputData'] = $this->workingScheduleInputData;
            $view = ['workingScheduleInputFormView'];
            //display/render the user view

            $this->renderView($view,$data);
        }
        
        private function deleteWorkingScheduleConfirmatinMsg($workingScheduleDetails){
            $staff = $this->getUserDetailsByIDFromDB($workingScheduleDetails['id']);
            $attendanceModel = new AttendanceModel();
            $attendanceSelected = $attendanceModel->getAttendanceByPrimaryKey($workingScheduleDetails['id'], $workingScheduleDetails['workingDate'], $workingScheduleDetails['workingStartingTime'], $workingScheduleDetails['workingOffTime']);
            $data = [
                'pageTitle' => 'Delete Work Schedule Confirmation',
                'selectedWorkingScheduleDetails' => $workingScheduleDetails,
                'scheduleOwner' => $staff,
                'attendanceSelected' => $attendanceSelected,
                'action' => 'delete',
            ];
                        
            $view = ['deleteWorkingScheduleConfirmationView'];
            $this->renderView($view, $data);
        }
        
        public function deleteWorkingSchedule() {
            $workingSchedule = $this->getWorkingScheduleDetails();
            
            $workingScheduleModel = new WorkingScheduleModel();
            $workingScheduleDetails = $workingScheduleModel->getWorkingScheduleByPrimaryKey($workingSchedule['staffId'], $workingSchedule['workingDate'], $workingSchedule['workingStartingTime'], $workingSchedule['workingOffTime']);
            $this->deleteWorkingScheduleConfirmatinMsg($workingScheduleDetails[0]);
        }

        private function getWorkingScheduleDetails() {
            // Check if each GET parameter is set and retrieve them
            $staffId = isset($_GET['id']) ? $_GET['id'] : null;
            $workingDate = isset($_GET['workingDate']) ? $_GET['workingDate'] : null;
            $workingStartingTime = isset($_GET['workingStartingTime']) ? $_GET['workingStartingTime'] : null;
            $workingOffTime = isset($_GET['workingOffTime']) ? $_GET['workingOffTime'] : null;

            // Check if all parameters are provided
            if ($staffId && $workingDate && $workingStartingTime && $workingOffTime) {
                // Return the details if all parameters are set
                return [
                    'staffId' => $staffId,
                    'workingDate' => $workingDate,
                    'workingStartingTime' => $workingStartingTime,
                    'workingOffTime' => $workingOffTime
                ];
            } else {
                // Handle the case where some parameters are missing
                return "Missing required parameters.";
            }
            
        }
        
        private function afterDelete($deleteFailed = false){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if($deleteFailed){
                $_SESSION['deleteFailed'] = 'Working Schedule Deletion Failed.';
            }else{
                $_SESSION['deleteSuccess'] = 'Working Schedule Deleted Successfully.';
            }
            
            header("Location: index.php?controller=user&action=workingScheduleManagement&sort=workingDate&filter=week");
            exit();
        }
        
        private function removeWorkingScheduleData($workingScheduleDetails) {
            $deleteFail = false;

            // Remove from user table
            $workingScheduleModel = new WorkingScheduleModel();
            $deleteFail = !$workingScheduleModel->isPrimaryKeyExistInWorkingScheduleDB($workingScheduleDetails['id'], $workingScheduleDetails['workingDate'], $workingScheduleDetails['workingStartingTime'], $workingScheduleDetails['workingOffTime']) ? true : $deleteFail;
            
            if (!$deleteFail) {
                $workingScheduleModel->removeWorkingScheduleByPrimaryKey($workingScheduleDetails['id'], $workingScheduleDetails['workingDate'], $workingScheduleDetails['workingStartingTime'], $workingScheduleDetails['workingOffTime']);
            }

            // Finalize deletion process
            $this->afterDelete($deleteFail);
        }
        
        public function confirmWorkScheduleDeletion(){
            // Check for POST data first (since form uses POST)
            if (!$this->isPostRequest()) {
                return; // Exit early if not a POST request
            }
            
            $this->setWorkingScheduleTemp();
            
            $workingScheduleModel = new WorkingScheduleModel();
            $workingScheduleDetails = $workingScheduleModel->getWorkingScheduleByPrimaryKey($this->staffId, $this->workingDate, $this->workingStartingTime, $this->workingOffTime);

            $this->removeWorkingScheduleData($workingScheduleDetails[0]);
        }
        
        private function isPostRequest() {
            return $_SERVER['REQUEST_METHOD'] === 'POST';
        }
        
        private function validateWorkingDate() {
            $today = new DateTime();
            $workingDate = new DateTime($this->workingDate ?? '');

            // Check if the working date is set
            if (empty($this->workingDate)) {
                return 'Working date is required.';
            }

            // Check if the working date is at least 1 day after today
            if ($workingDate <= $today->modify('+1 day')) {
                return 'Working date must be at least 1 day after today.';
            }

            // If no errors, return null or empty string
            return '';
        }

        private function validateWorkingStartingTime() {
            $startTime = DateTime::createFromFormat('H:i', '08:00');
            $endTime = DateTime::createFromFormat('H:i', '22:00');
            $workingStartingTime = DateTime::createFromFormat('H:i', $this->workingStartingTime ?? '');

            // Check if the starting time is set
            if (empty($this->workingStartingTime)) {
                return 'Working starting time is required.';
            }

            // Check if the starting time is within the allowed range
            if ($workingStartingTime < $startTime || $workingStartingTime > $endTime) {
                return 'Working starting time must be between 08:00 AM and 10:00 PM.';
            }

            // If no errors, return null or empty string
            return '';
        }

        private function validateWorkingOffTime() {
            $startTime = DateTime::createFromFormat('H:i', '12:00');
            $endTime = DateTime::createFromFormat('H:i', '22:00');
            $workingStartingTime = DateTime::createFromFormat('H:i', $this->workingStartingTime ?? '');
            $workingOffTime = DateTime::createFromFormat('H:i', $this->workingOffTime ?? '');

            // Check if the working off time is set
            if (empty($this->workingOffTime)) {
                return 'Working off time is required.';
            }

            // Check if the working off time is within the allowed range
            if ($workingOffTime < $startTime || $workingOffTime > $endTime) {
                return 'Working off time must be between 12:00 PM and 10:00 PM.';
            }

            // Check if working off time is at least 2 hours after starting time
            $interval = $workingOffTime->diff($workingStartingTime);
            if ($interval->h < 2) {
                return 'Working off time must be at least 2 hours after working starting time.';
            }

            // If no errors, return null or empty string
            return '';
        }

        private function checkWorkingScheduleInput() {
            $data = [
                'workingDateErr' => $this->validateWorkingDate(),
                'workingStartingTimeErr' => $this->validateWorkingStartingTime(),
                'workingOffTimeErr' => $this->validateWorkingOffTime(),
            ];

            $workingScheduleModel = new WorkingScheduleModel();
            if($workingScheduleModel->isPrimaryKeyExistInWorkingScheduleDB($this->staffId, $this->workingDate, $this->workingStartingTime, $this->workingOffTime)){
                $data['workingScheduleExistedErr'] = 'Working Schedule is Already Existed!';
            }
            $this->workingScheduleInputData = $data;
        }
        
        private function setWorkingScheduleTemp(){
            $this->staffId = $_POST['staffId'] ?? '';
            $this->workingDate = $_POST['workingDate'] ?? '';
            $this->workingStartingTime = $_POST['workingStartingTime'] ?? '';
            $this->workingOffTime = $_POST['workingOffTime'] ?? '';
            
        }
        
        private function setOldWorkingScheduleTemp(){
            return [
                "id" => $_POST['oldId'] ?? '',
                "workingDate" => $_POST['oldWorkingDate'] ?? '',
                "workingStartingTime" => $_POST['oldWorkingStartingTime'] ?? '',
                "workingOffTime" => $_POST['oldWorkingOffTime'] ?? '',
            ];
        }
        
        private function checkEmptyWorkingScheduleInputData() {
            foreach ($this->workingScheduleInputData as $key => $value) {
                if (!empty($value)) {
                    return false; // If any value is not empty, return false
                }
            }
            return true; // If all values are empty, return true
        }
        
        private function addDefaultAttendanceForNewWorkingSchedule(){
            $attendanceCtrl = new AttendanceManagementCtrl();
            $attendanceCtrl->addDefaultAttendanceToDB($this->staffId, $this->workingDate, $this->workingStartingTime, $this->workingOffTime);
        }
        
        private function removeAttendanceForDeletedWorkingSchedule(){
            $attendanceCtrl = new AttendanceManagementCtrl();
            $attendanceCtrl->removeAttendanceForDeletedWorkingSchedule($this->staffId, $this->workingDate, $this->workingStartingTime, $this->workingOffTime);
        }
        
        private function actionAfterWorkingScheduleAddedSuccessfully(){
            
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['workingScheduleAddedSuccess'] = 'Working Schedule Added Successfully.';
            header("Location: index.php?controller=user&action=workingScheduleManagement&sort=workingDate&filter=week");
            exit();
            
        }
        
        private function actionAfterEditedSuccessfully(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['workingScheduleEditedSuccess'] = 'Working Schedule Edited Successfully.';
            header("Location: index.php?controller=user&action=workingScheduleManagement&sort=workingDate&filter=week");
            exit();
            
        }
        
        private function workingScheduleAddedFailedAction(){
            $this->workingScheduleInputData['inputFormErr'] = 'Working Schedule Creation Failed';
            $_SESSION['workingScheduleInputData'] = $this->workingScheduleInputData;
            header("Location: index.php?controller=user&action=createNewWorkSchedule");
            exit();
        }
        
        private function editingWorkingScheduleFailedAction(){
            $this->workingScheduleInputData['inputFormErr'] = 'Working Schedule Editing Failed';
            $_SESSION['workingScheduleInputData'] = $this->workingScheduleInputData;
            header("Location: index.php?controller=user&action=editWorkingSchedule&id=$this->staffId&workingDate=$this->workingDate&workingStartingTime=$this->workingStartingTime&workingOffTime=$this->workingOffTime");
            exit();
        }
        
        private function afterAddWorkingScheduleInput($validInput){
            //only render view when there is error
            if($validInput){
                // create new schedule to db
                $workingScheduleModel = new WorkingScheduleModel();
                $lastestNewWorkingSchedule = $workingScheduleModel->addWorkingScheduleIntoDB($this->staffId, $this->workingDate, $this->workingStartingTime, $this->workingOffTime);
                
                if($lastestNewWorkingSchedule != false){ // created working Schedule successfully
                    $this->addDefaultAttendanceForNewWorkingSchedule(); //add default attendance when the working schedule is added
                    $this->actionAfterWorkingScheduleAddedSuccessfully();
                    
                }else{
                    $this->workingScheduleAddedFailedAction();
                }
            }else{
                $this->workingScheduleAddedFailedAction();
            }
            
        }
        
        private function updateWorkingSchedule($oldWorkingSchedule) {
            // Check if any primary key fields have changed
            $isStaffIdChanged = $this->staffId !== $oldWorkingSchedule['id'];
            $isWorkingDateChanged = $this->workingDate !== $oldWorkingSchedule['workingDate'];
            $isWorkingStartingTimeChanged = $this->workingStartingTime !== $oldWorkingSchedule['workingStartingTime'];
            $isWorkingOffTimeChanged = $this->workingOffTime !== $oldWorkingSchedule['workingOffTime'];

            // If any primary key field has changed, we need to delete and re-insert the record
            if ($isStaffIdChanged || $isWorkingDateChanged || $isWorkingStartingTimeChanged || $isWorkingOffTimeChanged) {
                // Remove the old working schedule
                $workingScheduleModel = new WorkingScheduleModel();
                
                $workingScheduleModel->removeWorkingScheduleByPrimaryKey(
                    $oldWorkingSchedule['id'],
                    $oldWorkingSchedule['workingDate'],
                    $oldWorkingSchedule['workingStartingTime'],
                    $oldWorkingSchedule['workingOffTime']
                );
                
                $attendanceModel = new AttendanceModel();
                if($attendanceModel->isPrimaryKeyExistInAttendanceDB($oldWorkingSchedule['id'], $oldWorkingSchedule['workingDate'], $oldWorkingSchedule['workingStartingTime'], $oldWorkingSchedule['workingOffTime'])){
                    //if the attendance for the working schedule is exist, is should be remove too
                    $this->removeAttendanceForDeletedWorkingSchedule($oldWorkingSchedule['id'], $oldWorkingSchedule['workingDate'], $oldWorkingSchedule['workingStartingTime'], $oldWorkingSchedule['workingOffTime']);
                }
                
                // Add the new working schedule with the updated values
                if (!$workingScheduleModel->isPrimaryKeyExistInWorkingScheduleDB($this->staffId, $this->workingDate, $this->workingStartingTime, $this->workingOffTime)) {
                    $workingScheduleModel->addWorkingScheduleIntoDB(
                        $this->staffId, 
                        $this->workingDate, 
                        $this->workingStartingTime, 
                        $this->workingOffTime
                    );
                    $this->addDefaultAttendanceForNewWorkingSchedule();//add default attendance when the working schedule is added
                }
            }
        }
        
        private function afterWorkingScheduleEditingInput($validInput) {
            if (!$validInput) {
                $this->editingWorkingScheduleFailedAction();
                return;
            }

            $oldWorkingSchedule = $this->setOldWorkingScheduleTemp();
            
            // Update data for the editing WorkingSchedule
            $this->updateWorkingSchedule($oldWorkingSchedule);
            // Execute post-edit action
            $this->actionAfterEditedSuccessfully();
        }
        
        public function submitWorkingScheduleInputForm(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $validInput = false;
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->workingScheduleInputData = null;
                $this->setWorkingScheduleTemp();
                $this->checkWorkingScheduleInput();
                
                if($this->checkEmptyWorkingScheduleInputData()){
                    $validInput = true;
                }
                
            }
            
            // if is registering 
            if($_POST['submitAs'] === 'addWorkingSchedule'){
                $this->afterAddWorkingScheduleInput($validInput);
            }else{
                $this->afterWorkingScheduleEditingInput($validInput);
            }
        }
        
    }