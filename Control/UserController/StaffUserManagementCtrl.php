<?php

    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInUser();

    require_once __DIR__ . '/../../Model/User/WorkingScheduleModel.php';
    require_once __DIR__ . '/../../Model/User/AttendanceModel.php';
    require_once __DIR__ . '/../../Model/User/AttendanceStatusModel.php';
    require_once __DIR__ . '/../../Model/User/LeaveApplicationModel.php';
    require_once 'UserController.php';

    class StaffUserManagementCtrl extends UserController{
                
        public function __construct() {
            parent::__construct();

        }
        
        protected function processWorkingSchedule(){
            $workingScheduleModel = new WorkingScheduleModel();

            // Fetch all working schedules from the database
            $workingSchedulesModelArray = $workingScheduleModel->getAllWorkingSchedule();
            
            $workingSchedulesArray = [];
            
            if($workingSchedulesModelArray != false){
                foreach ($workingSchedulesModelArray as $schedule) {
                    $workingSchedulesArray[] = [
                        'id' => $schedule['id'],
                        'workingDate' => $schedule['workingDate'],
                        'workingStartingTime' => $schedule['workingStartingTime'],
                        'workingOffTime' => $schedule['workingOffTime']
                    ];
                }
            }
            
            // Return the array as part of a structured response
            return [
                'workingSchedulesArray' => $workingSchedulesArray
            ];
        }  
        
        protected function processWorkingAttendance(){
            $attendanceModel = new AttendanceModel();

            // Fetch all working schedules from the database
            $attendanceModelArray = $attendanceModel->getAllAttendance();
            
            $attendancesArray = [];
            
            if($attendanceModelArray != false){
                foreach ($attendanceModelArray as $attendance) {
                    $attendancesArray[] = [
                        'id' => $attendance['id'],
                        'workingDate' => $attendance['workingDate'],
                        'workingStartingTime' => $attendance['workingStartingTime'],
                        'workingOffTime' => $attendance['workingOffTime'],
                        'photo' => $attendance['photo'],
                        'location' => $attendance['location'],
                        'attendanceDateTime' => $attendance['attendanceDateTime'],
                        'statusID' => $attendance['statusID'],
                    ];
                }
            }
            
            // Return the array as part of a structured response
            return [
                'attendancesArray' => $attendancesArray
            ];
        } 
        
        protected function processAttendanceStatus(){
            $attendanceStatusModel = new AttendanceStatusModel();

            // Fetch all working schedules from the database
            $attendanceStatusModelArray = $attendanceStatusModel->getAllAttendanceStatus();
            
            $attendancesStatusArray = [];
            
            if($attendanceStatusModelArray != false){
                foreach ($attendanceStatusModelArray as $attendancesStatus) {
                    $attendancesStatusArray[] = [
                        'statusID' => $attendancesStatus['statusID'],
                        'statusName' => $attendancesStatus['statusName']
                    ];
                }
            }
            
            // Return the array as part of a structured response
            return [
                'attendancesStatusArray' => $attendancesStatusArray
            ];
        } 
        
        protected function processLeaveApplication(){
            $leaveApplicationModel = new LeaveApplicationModel();

            // Fetch all working schedules from the database
            $leaveApplicationModelArray = $leaveApplicationModel->getAllLeaveApplicationFromDB();
            
            $leaveApplicationsArray = [];
            
            if($leaveApplicationModelArray != false){
                foreach ($leaveApplicationModelArray as $leaveApplication) {
                    $leaveApplicationsArray[] = [
                        'id' => $leaveApplication['id'],
                        'reason' => $leaveApplication['reason'],
                        'evidencePhoto' => $leaveApplication['evidencePhoto'],
                        'leaveDate' => $leaveApplication['leaveDate'],
                        'leaveStartTime' => $leaveApplication['leaveStartTime'],
                        'leaveEndTime' => $leaveApplication['leaveEndTime'],
                        'approved' => $leaveApplication['approved']
                    ];
                }
            }
            
            // Return the array as part of a structured response
            return [
                'leaveApplicationsArray' => $leaveApplicationsArray
            ];
        } 
        
        public function editStaff($id = 0){
            $staff = $this->setStaffDetails($this->setUserObj($id));
            
            $data = [
                'pageTitle' => 'Staff Editing Form',
                'selectedUser' => $staff,
                'action' => 'edit',
            ];
                        
            $view = ['staffDetailInputFormView'];
            $this->renderView($view, $data);

            //alter the staff Details
        }

        public function staffUserManagement() {
            $this->index();
        }
        
        public function index(){
            $result = $this->processUsers();
            
            //set role for each data
            $usersArray = $result['usersArray'];
            $staffsArray = $result['staffsArray'];
            
            //set render data (set the user, customer, admin)
            $data = $this->setRenderData('User Management Panel');
            $data['usersArray'] = $usersArray;
            $data['staffsArray'] = $staffsArray;
            $view = ['adminTopNavHeader','userManagementTopNav','staffManagementTopNav','staffUserManagementView'];
            //display/render the user view

            $this->renderView($view,$data);
            
        }
    }