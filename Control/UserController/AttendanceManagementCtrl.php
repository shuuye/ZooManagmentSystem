<?php

    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInAdmin();

    require_once 'StaffUserManagementCtrl.php';
    require_once __DIR__ . '/../../Model/User/WorkingScheduleModel.php';
    require_once __DIR__ . '/../../Model/User/AttendanceModel.php';
    
    class AttendanceManagementCtrl extends StaffUserManagementCtrl{
        
        public function addDefaultAttendanceToDB($id, $workingDate, $workingStartingTime, $workingOffTime){
            $attendanceModel = new AttendanceModel();
            $attendanceModel->addDefaultAttendanceIntoDB($id, $workingDate, $workingStartingTime, $workingOffTime);
        }
        
        public function removeAttendanceForDeletedWorkingSchedule($id, $workingDate, $workingStartingTime, $workingOffTime){
            $attendanceModel = new AttendanceModel();
            $attendanceModel->removeAttendanceByPrimaryKey($id, $workingDate, $workingStartingTime, $workingOffTime);
        }
        
        public function updateAttendanceStatus() {
            // Get all the parameters from the URL
            $id = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : null;
            $workingDate = isset($_POST['workingDate']) ? htmlspecialchars($_POST['workingDate']) : null;
            $workingStartingTime = isset($_POST['workingStartingTime']) ? htmlspecialchars($_POST['workingStartingTime']) : null;
            $workingOffTime = isset($_POST['workingOffTime']) ? htmlspecialchars($_POST['workingOffTime']) : null;
            $statusID = isset($_POST['statusID']) ? htmlspecialchars($_POST['statusID']) : null;

            // Check if all necessary parameters are available
            if ($id && $workingDate && $workingStartingTime && $workingOffTime && $statusID) {
                // Call the model function to update the attendance status using the primary key and statusID
                $attendanceModel = new AttendanceModel();
                $updated=true;
                
                if($attendanceModel->isPrimaryKeyExistInAttendanceDB($id, $workingDate, $workingStartingTime, $workingOffTime)){
                    $updated = $attendanceModel->updateStatusIDFromAttendanceByPrimaryKey($id, $workingDate, $workingStartingTime, $workingOffTime, $statusID);
                }
                if($updated){
                    header("Location: index.php?controller=user&action=attendanceManagement&sort=workingDate&filter=week");
                }
                
            } else {
                // Handle the error in case any parameter is missing
                echo "Error: Missing required parameters to update attendance status.";
                exit();
            }
        }
                
        public function editAttendanceStatus() {
            // Existing code
            $id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : null;
            $workingDate = isset($_GET['workingDate']) ? htmlspecialchars($_GET['workingDate']) : null;
            $workingStartingTime = isset($_GET['workingStartingTime']) ? htmlspecialchars($_GET['workingStartingTime']) : null;
            $workingOffTime = isset($_GET['workingOffTime']) ? htmlspecialchars($_GET['workingOffTime']) : null;
            $statusID = isset($_GET['statusID']) ? htmlspecialchars($_GET['statusID']) : null;

            $attendanceModel = new AttendanceModel();
            $selectedAttendance = $attendanceModel->getAttendanceByPrimaryKey($id, $workingDate, $workingStartingTime, $workingOffTime);

            $attendanceStatusArray = $this->processAttendanceStatus();

            $data = [
                'pageTitle' => 'Attendance Status Editing Form',
                'selectedAttendance' => $selectedAttendance,
                'attendanceStatusArray' => $attendanceStatusArray['attendancesStatusArray'],
                'action' => 'edit',
            ];

            $view = ['attendanceStatusEditingFromView'];
            $this->renderView($view, $data);
        }

        public function attendanceManagement(){
            //set role for each data
            $attendancesArray = $this->processWorkingAttendance();
            $attendancesStatusArray = $this->processAttendanceStatus();
            
            //set render data (set the user, customer, admin)
            $data = $this->setRenderData('Attendance Management Panel');
            $data['attendancesArray'] = $attendancesArray['attendancesArray'];
            $data['attendancesStatusArray'] = $attendancesStatusArray['attendancesStatusArray'];
            $view = ['adminTopNavHeader','userManagementTopNav','staffManagementTopNav','attendanceManagementView'];
            //display/render the user view

            $this->renderView($view,$data);
        }
    }

