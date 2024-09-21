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
                        'working_date' => $schedule['working_date'],
                        'working_starting_time' => $schedule['working_starting_time'],
                        'working_off_time' => $schedule['working_off_time']
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
                        'working_date' => $attendance['working_date'],
                        'working_starting_time' => $attendance['working_starting_time'],
                        'working_off_time' => $attendance['working_off_time'],
                        'photo' => $attendance['photo'],
                        'location' => $attendance['location'],
                        'attendance_date_time' => $attendance['attendance_date_time'],
                        'status_id' => $attendance['status_id'],
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
                        'status_id' => $attendancesStatus['status_id'],
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