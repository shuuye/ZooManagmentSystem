<?php

    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInStaff();

    require_once __DIR__ . '/../../Model/XmlGenerator.php';
    require_once __DIR__ . '/../../Model/Xml/XSLTransformation.php';
    require_once __DIR__ . '/../../Model/User/AttendanceModel.php';
    require_once __DIR__ . '/../../Public/UserWebService/RestfulLocationService_Consume.php';
    require_once 'UserController.php';
    
    class StaffCtrl extends UserController{
        protected $id;
        protected $working_date;
        protected $working_starting_time;
        protected $working_off_time;
        protected $location;
        protected $photo;
        protected $attendance_date_time;
        protected $latitude;
        protected $longitude;
        protected $attendanceInputData;

        public function __construct() {
            parent::__construct();
            
        }
        
        public function staffWorkingScheduleWithAttendance(){
            $xmlGenerator = new XmlGenerator();
            //create the xml file
            $xmlGenerator->createXMLFileByTableName('attendance', 'attendances.xml', 'attendances', 'attendance', 'id');
            
            // Transform user dashboard with xsl
            $staffOwnWorkingScheduleWithAttendanceDetails = new XSLTransformation("Model/Xml/attendances.xml", "View/UserView/staffOwnWorkingScheduleWithAttendance.xsl");
            $staffOwnWorkingScheduleWithAttendanceDetails->setParameter('ownId', $_SESSION['currentUserModel']['id']);
            $transformedStaffOwnWorkingScheduleWithAttendanceDetailsOutput = $staffOwnWorkingScheduleWithAttendanceDetails->transform();
            
            
            $staffOwnAttendancePercentages = new XSLTransformation("Model/Xml/attendances.xml", "View/UserView/staffOwnAttendancePercentage.xsl");
            $staffOwnAttendancePercentages->setParameter('ownId', $_SESSION['currentUserModel']['id']);
            $transformedStaffOwnAttendancePercentagesOutput = $staffOwnAttendancePercentages->transform();
            
            $view = [
                'staffUserTopNav',
                'staffWorkingScheduleView',
                $transformedStaffOwnAttendancePercentagesOutput,
                $transformedStaffOwnWorkingScheduleWithAttendanceDetailsOutput,
            ];
            
            $data = $this->setRenderData('Staff Working Schedule And Attendance');
            // Render view
            $this->renderView($view, $data);
        }
        
        public function staffTakeWorkingAttendance(){
            $view = [
                'staffUserTopNav',
                'staffTakeAttendanceView',
            ];
            
            $attendanceModel = new AttendanceModel();
            $attendanceModelArray = $attendanceModel->getAllAttendance();

            if (isset($_SESSION['currentUserModel'])) {
                $currentUserId = $_SESSION['currentUserModel']['id'];

                // Filter the attendance array to only include rows where the 'id' matches the current user
                $staffOwnAttendanceArray = array_filter($attendanceModelArray, function($attendance) use ($currentUserId) {
                    return $attendance['id'] == $currentUserId;
                });
            }
            
            $data = $this->setRenderData('Staff Take Attendance');
            $data['staffOwnAttendanceArray'] = $staffOwnAttendanceArray;
            
            // Render the view
            $this->renderView($view, $data);
        }
        
        private function saveAttendancePhoto() {
            // Define the directory where the files will be uploaded
            $uploadDir = 'C:/xampp/htdocs/ZooManagementSystem/assests/UserImages/attendance_photos/';
            // Ensure the upload directory exists, if not, create it
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true); // Create directory with appropriate permissions
            }

            // Check if the file was uploaded without errors
            if (!isset($this->photo) || $this->photo['error'] !== UPLOAD_ERR_OK) {
                return "Failed to upload the file. Please try again.";
            }

            // Get original file name and temporary file path
            $fileTmpPath = $this->photo['tmp_name'];
            $originalFileName = basename($this->photo['name']); // Use basename to sanitize file name

            // Create a unique file name using the original file extension
            $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
            $newFileName = uniqid('attendance_', true) . '.' . $fileExtension;

            // Set the target file path
            $targetFilePath = $uploadDir . $newFileName;

            // Move the uploaded file from the temp directory to the upload directory
            if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                // File was successfully moved, return the path where the file was saved
                return $targetFilePath;
            } else {
                // Handle error if the file was not moved
                return "Failed to save the attendance photo. Please check file permissions.";
            }
        }
        
        private function validateAttendancePhoto() {
            // Check if the file is uploaded
            if (is_null($this->photo)) {
                return "Attendance photo is required to be uploaded.";
            }

            // Check file properties
            $fileTmpPath = $this->photo['tmp_name'];
            $fileName = $this->photo['name'];
            $fileSize = $this->photo['size'];
            $fileType = $this->photo['type'];
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
        private function validateAttendanceDateTime() {
            // Convert the working date and times to timestamps
            $working_date = $this->working_date;
            $working_starting_time = $this->working_starting_time;
            $working_off_time = $this->working_off_time;
            $attendance_date_time = $this->attendance_date_time;

            if (empty($working_date) || empty($working_starting_time) || empty($working_off_time) || empty($attendance_date_time)) {
                return 'Required fields are missing.';
            }

            // Combine date and time into full datetime strings
            $startDateTime = $working_date . ' ' . $working_starting_time;
            $endDateTime = $working_date . ' ' . $working_off_time;

            // Convert to timestamps
            $startTimestamp = strtotime($startDateTime);
            $endTimestamp = strtotime($endDateTime);
            $attendanceTimestamp = strtotime($attendance_date_time);
            
            // Check if attendance datetime is between start and end times
            if ($attendanceTimestamp < $startTimestamp || $attendanceTimestamp > $endTimestamp) {
                return 'Attendance datetime must be between the working start time and working off time.';
            }

            return ''; // No error
        }
        
        private function validateLocation() {
            // Retrieve latitude and longitude from POST data
            $latitude = $this->latitude;
            $longitude = $this->longitude;

            $errors = '';

            // Check if latitude and longitude are provided
            if (empty($latitude) || empty($longitude)) {
                $errors = 'Please Permit The Share Location Permission To Take Attendance.';
            } 

            // Return the errors, or an empty string if no errors
            return $errors;
        }

        private function setAttendanceTemp() {
            $this->id = $_POST['id'] ?? '';
            $this->working_date = $_POST['working_date'] ?? '';
            $this->working_starting_time = $_POST['working_starting_time'] ?? '';
            $this->working_off_time = $_POST['working_off_time'] ?? '';
            $this->attendance_date_time = date('Y-m-d H:i:s');

            // Get latitude and longitude from POST data
            $this->latitude = $_POST['latitude'] ?? '';
            $this->longitude = $_POST['longitude'] ?? '';

            if(!empty($this->latitude) && !empty($this->longitude)){
                // Your API key
                $apiKey = 'AIzaSyAGrUibzHEc-dAOaTfxpx2-ZBraBvuBHbo';

                // Create an instance of the class
                $locationService = new RestfulLocationService_Consume($apiKey);

                try {
                    // Get the full address
                    $fullAddress = $locationService->getFullAddress($this->latitude, $this->longitude);
                    $this->location = htmlspecialchars($fullAddress);
                } catch (Exception $e) {
                    echo "Error: " . htmlspecialchars($e->getMessage());
                }
            }
            
            // Handle file upload
            if (isset($_FILES['attendancePhoto']) && $_FILES['attendancePhoto']['error'] === UPLOAD_ERR_OK) {
                $this->photo = $_FILES['attendancePhoto'];
            } else {
                $this->photo = null; // If the file is not uploaded correctly
            }
        }

        private function checkAttendanceInput(){
            $data = [
                'attendancePhotoErr' => $this->validateAttendancePhoto(),
                'attendance_date_timeErr' => $this->validateAttendanceDateTime(),
                'locationErr' => $this->validateLocation()
            ];
            
            $this->attendanceInputData = $data;
        }
        
        private function checkEmptyAttendanceInputData() {
            foreach ($this->attendanceInputData as $key => $value) {
                if (!empty($value)) {
                    return false; // If any value is not empty, return false
                }
            }
            return true; // If all values are empty, return true
        }
        
        private function actionAttendanceTakenSuccessfully(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            header("Location: index.php?controller=user&action=staffTakeWorkingAttendance");
            exit();
            
        }
        
        private function attendanceTakeFailedAction(){
            $this->attendanceInputData['inputFormErr'] = 'Attendance Not Taken';
            $_SESSION['attendanceInputData'] = $this->attendanceInputData;
            header("Location: index.php?controller=user&action=staffTakeWorkingAttendance");
            exit();
        }
        
        private function afterAttendanceInput($validInput){
            $savedPhotoPath = null; // Initialize variable to store the saved photo path
            // Proceed with saving the evidence photo only if the input is valid
            if ($validInput) {
                // Save the evidence photo and capture the result (file path or error message)
                $savedPhotoPath = $this->saveAttendancePhoto();

                // Check if the evidence photo was saved successfully
                if (is_string($savedPhotoPath) && strpos($savedPhotoPath, 'Failed') === false) {
                    // Update $this->evidencePhoto with the saved photo path to be stored in the database
                    $this->photo = $savedPhotoPath;

                    $attendanceModel = new AttendanceModel();
                    $updatedStatus = $attendanceModel->updateStatusIDFromAttendanceByPrimaryKey($this->id, $this->working_date, $this->working_starting_time, $this->working_off_time, 2);
                    $updatePhoto = $attendanceModel->updateColumnFromAttendanceByPrimaryKey($this->id, $this->working_date, $this->working_starting_time, $this->working_off_time, 'photo', $this->photo);
                    $updateLocation = $attendanceModel->updateColumnFromAttendanceByPrimaryKey($this->id, $this->working_date, $this->working_starting_time, $this->working_off_time, 'location', $this->location);
                    $updateAttendanceDateTime = $attendanceModel->updateColumnFromAttendanceByPrimaryKey($this->id, $this->working_date, $this->working_starting_time, $this->working_off_time, 'attendance_date_time', $this->attendance_date_time);
                    
                    if($updatedStatus && $updatePhoto && $updateLocation && $updateAttendanceDateTime){
                        $this->actionAttendanceTakenSuccessfully();
                    }else{
                        $this->attendanceTakeFailedAction();
                    }
                    
                } else {
                    // If the photo failed to save, mark the input as invalid and handle failure
                    $this->attendanceInputData['attendancePhotoErr'] = "Failed to upload attendance photo.";
                    $this->attendanceTakeFailedAction();
                }
            } else {
                // If the input is invalid, handle failure
                $this->attendanceTakeFailedAction();
            }
        }
        
        public function takeAttendance(){
            $validInput = false;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->attendanceInputData = null;
                $this->setAttendanceTemp();
                $this->checkAttendanceInput();
                if($this->checkEmptyAttendanceInputData()){
                    $validInput = true;
                }
            }
            
            $this->afterAttendanceInput($validInput);
            
        }
        
        public function staffLeaveApplication(){
            $xmlGenerator = new XmlGenerator();
            $xmlGenerator->createXMLFileByTableName('leaveapplication', 'leaveApplications.xml', 'leaveApplications', 'leaveApplication', 'id');
            
            // Transform user dashboard
            $leaveAttendanceDetails = new XSLTransformation("Model/Xml/leaveApplications.xml", "View/UserView/staffOwnLeaveApplication.xsl");
            $leaveAttendanceDetails->setParameter('ownId', $_SESSION['currentUserModel']['id']);
            $transformedleaveAttendanceDetailsOutput = $leaveAttendanceDetails->transform();
            
            $view = [
                'staffUserTopNav',
                'staffLeaveApplicationView',
                $transformedleaveAttendanceDetailsOutput,
            ];
            
            $data = $this->setRenderData('Staff Leave Application');
            // Render the view
            $this->renderView($view, $data);
        }
        
        public function staffMainPanel(){
            
            $data = [
                'pageTitle' => 'Staff Main Panel Form',
            ];
                        
            $view = ['staffMainPanelView'];
            $this->renderView($view, $data);

        }
        
    }
    
