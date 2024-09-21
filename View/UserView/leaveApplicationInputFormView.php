<<<<<<< HEAD
<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInUser();

    function getError($data, $key) {
        return isset($data[$key]) && $data[$key] !== '' ? htmlspecialchars($data[$key]) : '';
    }

    function displayErrorMessage($data, $key) {
        return isset($data[$key]) && $data[$key] !== '' ? '<div class="error-message">' . htmlspecialchars($data[$key]) . '</div>' : '';
    }

    function getStaffsOptions($staffs) {
        $options = '';
        foreach ($staffs as $staff) {
            $options .= '<option value="' . htmlspecialchars($staff["id"]) . '">' . htmlspecialchars($staff["username"]) . '</option>';
        }
        return $options;
    }

    function getWorkingScheduleOptions($workingSchedules, $selectedStaffId = null) {
        $options = '';
        foreach ($workingSchedules as $schedule) {
            if ($selectedStaffId && $schedule['staffId'] == $selectedStaffId) {
                $value = htmlspecialchars($schedule['working_date']) . '|' . htmlspecialchars($schedule['working_starting_time']) . '|' . htmlspecialchars($schedule['working_off_time']);
                $text = htmlspecialchars($schedule['working_date'] . ' - ' . $schedule['working_starting_time'] . ' to ' . $schedule['working_off_time']);
                $options .= '<option value="' . $value . '">' . $text . '</option>';
            }
        }
        return $options;
    }

    // Sample data for staff and working schedules
    $staffs = $data['staffsArray'];
    $workingSchedules = $data['workingSchedulesArray'];
    
?>

<script>
    // Store the working schedules in a JavaScript variable for easy access
    var workingSchedules = <?php echo json_encode($workingSchedules); ?>;

    // Function to update the working schedule options based on the selected staff
    function updateWorkingScheduleOptions() {
        var staffId = document.getElementById('staffId').value;
        var leaveScheduleDropdown = document.getElementById('leaveSchedule');

        // Disable the dropdown if no staff is selected
        if (staffId === "") {
            leaveScheduleDropdown.innerHTML = '<option value="">-- Select Working Schedule --</option>';
            leaveScheduleDropdown.disabled = true;
            return;
        }

        // Clear existing options
        leaveScheduleDropdown.innerHTML = '';

        // Add the default option
        var defaultOption = document.createElement('option');
        defaultOption.text = '-- Select Working Schedule --';
        defaultOption.value = '';
        leaveScheduleDropdown.add(defaultOption);

        // Populate with schedules matching the selected staff
        workingSchedules.forEach(function(schedule) {
            if (schedule.id == staffId) {
                var option = document.createElement('option');
                option.value = schedule.working_date + '|' + schedule.working_starting_time + '|' + schedule.working_off_time;
                option.text = schedule.working_date + ' - ' + schedule.working_starting_time + ' to ' + schedule.working_off_time;
                leaveScheduleDropdown.add(option);
            }
        });

        // Enable the dropdown if there are options
        leaveScheduleDropdown.disabled = leaveScheduleDropdown.options.length <= 1;
    }

</script>

<h1>Apply For New Leave</h1>

<form action="index.php?controller=user&action=applyNewLeave" method="POST" enctype="multipart/form-data">
    <style>
        .error{
            color:red;
        }
    </style>
    <table>
        <tr>
            <td>Staff:</td>
            <td>
                <?php if ($_SESSION['currentUserModel']['role']['roleID'] == 3): ?>
                    <!-- Display only the current user's ID if their role is 2 -->
                    <?php echo htmlspecialchars($_SESSION['currentUserModel']['id'], ENT_QUOTES, 'UTF-8'); ?>
                    <input type="hidden" name="staffId" id="staffId" value="<?php echo htmlspecialchars($_SESSION['currentUserModel']['id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <script>
                        // Manually call updateWorkingScheduleOptions when staffId is set for role 2
                        document.addEventListener("DOMContentLoaded", function() {
                            updateWorkingScheduleOptions();
                        });
                    </script>
                <?php else: ?>
                    <!-- If the role is not 2, display the dropdown -->
                    <select name="staffId" id="staffId" onchange="updateWorkingScheduleOptions()">
                        <option value="">-- Select Staff --</option>
                        <?php echo getStaffsOptions($staffs); ?>
                    </select>
                <?php endif; ?>
            </td>
            <td class="error">
                <?php if (isset($_SESSION['leaveApplicationInputData'])): ?>
                    <?php echo getError($_SESSION['leaveApplicationInputData'], 'staffIdErr'); ?>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>Date And Time To Leave:</td>
            <td>
                <select name="leaveSchedule" id="leaveSchedule" disabled>
                    <option value="">-- Select Working Schedule --</option>
                </select>
            </td>
            <td class="error"><?php if (isset($_SESSION['leaveApplicationInputData'])): ?>
                <?php echo getError($_SESSION['leaveApplicationInputData'], 'leaveScheduleErr'); ?>
            <?php endif; ?>
            </td>
        </tr>
       
        <tr>
            <td><label for="reason">Reason:</label></td>
            <td>
                <input type="text" 
                        id="reason" 
                        name="reason" 
                        required
                 >
            </td>
            <td class="error"><?php if (isset($_SESSION['leaveApplicationInputData'])): ?>
                <?php echo getError($_SESSION['leaveApplicationInputData'], 'reasonErr'); ?>
            <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td><label for="evidencePhoto">Evidence Photo:</label></td>
            <td>
                <input type="file" 
                       id="evidencePhoto" 
                       name="evidencePhoto" 
                       accept="image/jpeg, image/png, image/gif"
                       required
                >
            </td>
            <td class="error">
                <?php if (isset($_SESSION['leaveApplicationInputData'])): ?>
                    <?php echo getError($_SESSION['leaveApplicationInputData'], 'evidencePhotoErr'); ?>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="error"><?php if (isset($_SESSION['leaveApplicationInputData'])): ?>
                <?php echo getError($_SESSION['leaveApplicationInputData'], 'leaveApplicationExistedErr'); ?>
            <?php endif; ?>
            </td>
            <td class="error"><?php if (isset($_SESSION['leaveApplicationInputData'])): ?>
                <?php echo getError($_SESSION['leaveApplicationInputData'], 'inputFormErr'); ?>
                <?php unset($_SESSION['leaveApplicationInputData']); ?>
            <?php endif; ?>
            </td>
        </tr>
        
        <tr>
            <td>
                <button type="submit" name="submitAs" value="applyLeave">
                    Apply
                </button>
            </td>
            <td>
                <?php if ($_SESSION['currentUserModel']['role']['roleID'] == 3): ?>
                    <a href="index.php?controller=user&action=staffLeaveApplication" id="cancelLink"><button type="button">
                        Cancel
                    </button></a>
                <?php else: ?>
                    <a href="index.php?controller=user&action=leaveApplicationManagement&sort=leaveDate&filter=week" id="cancelLink"><button type="button">
                        Cancel
                    </button></a>
                <?php endif; ?>
            </td>

        </tr>
        
    </table>
    
</form>


<?php
echo displayErrorMessage($data, 'errorMessage');
?>
=======
<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInUser();//only allow the logged in user to access

    function getError($data, $key) {
        return isset($data[$key]) && $data[$key] !== '' ? htmlspecialchars($data[$key]) : '';
    }

    function displayErrorMessage($data, $key) {
        return isset($data[$key]) && $data[$key] !== '' ? '<div class="error-message">' . htmlspecialchars($data[$key]) . '</div>' : '';
    }

    function getStaffsOptions($staffs) {
        $options = '';
        foreach ($staffs as $staff) {
            $options .= '<option value="' . htmlspecialchars($staff["id"]) . '">' . htmlspecialchars($staff["username"]) . '</option>';
        }
        return $options;
    }

    function getWorkingScheduleOptions($workingSchedules, $selectedStaffId = null) {
        $options = '';
        foreach ($workingSchedules as $schedule) {
            if ($selectedStaffId && $schedule['staffId'] == $selectedStaffId) {
                $value = htmlspecialchars($schedule['working_date']) . '|' . htmlspecialchars($schedule['working_starting_time']) . '|' . htmlspecialchars($schedule['working_off_time']);
                $text = htmlspecialchars($schedule['working_date'] . ' - ' . $schedule['working_starting_time'] . ' to ' . $schedule['working_off_time']);
                $options .= '<option value="' . $value . '">' . $text . '</option>';
            }
        }
        return $options;
    }

    // Sample data for staff and working schedules
    $staffs = $data['staffsArray'];
    $workingSchedules = $data['workingSchedulesArray'];
    
?>

<script>
    // Store the working schedules in a JavaScript variable for easy access
    var workingSchedules = <?php echo json_encode($workingSchedules); ?>;

    // Function to update the working schedule options based on the selected staff
    function updateWorkingScheduleOptions() {
        var staffId = document.getElementById('staffId').value;
        var leaveScheduleDropdown = document.getElementById('leaveSchedule');

        // Disable the dropdown if no staff is selected
        if (staffId === "") {
            leaveScheduleDropdown.innerHTML = '<option value="">-- Select Working Schedule --</option>';
            leaveScheduleDropdown.disabled = true;
            return;
        }

        // Clear existing options
        leaveScheduleDropdown.innerHTML = '';

        // Add the default option
        var defaultOption = document.createElement('option');
        defaultOption.text = '-- Select Working Schedule --';
        defaultOption.value = '';
        leaveScheduleDropdown.add(defaultOption);

        // Populate with schedules matching the selected staff
        workingSchedules.forEach(function(schedule) {
            if (schedule.id == staffId) {
                var option = document.createElement('option');
                option.value = schedule.working_date + '|' + schedule.working_starting_time + '|' + schedule.working_off_time;
                option.text = schedule.working_date + ' - ' + schedule.working_starting_time + ' to ' + schedule.working_off_time;
                leaveScheduleDropdown.add(option);
            }
        });

        // Enable the dropdown if there are options
        leaveScheduleDropdown.disabled = leaveScheduleDropdown.options.length <= 1;
    }

</script>

<h1>Apply For New Leave</h1>

<form action="index.php?controller=user&action=applyNewLeave" method="POST" enctype="multipart/form-data">
    <style>
        .error{
            color:red;
        }
    </style>
    <table>
        <tr>
            <td>Staff:</td>
            <td>
                <?php if ($_SESSION['currentUserModel']['role']['roleID'] == 3): ?>
                    <!-- Display only the current user's ID if their role is 3 -->
                    <?php echo htmlspecialchars($_SESSION['currentUserModel']['id'], ENT_QUOTES, 'UTF-8'); ?>
                    <input type="hidden" name="staffId" id="staffId" value="<?php echo htmlspecialchars($_SESSION['currentUserModel']['id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <script>
                        // Manually call updateWorkingScheduleOptions when staffId is set for role 2
                        document.addEventListener("DOMContentLoaded", function() {
                            updateWorkingScheduleOptions();
                        });
                    </script>
                <?php else: ?>
                    <!-- If the role is not 3, display the dropdown -->
                    <select name="staffId" id="staffId" onchange="updateWorkingScheduleOptions()">
                        <option value="">-- Select Staff --</option>
                        <?php echo getStaffsOptions($staffs); ?>
                    </select>
                <?php endif; ?>
            </td>
            <td class="error">
                <?php if (isset($_SESSION['leaveApplicationInputData'])): ?>
                    <?php echo getError($_SESSION['leaveApplicationInputData'], 'staffIdErr'); ?>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>Date And Time To Leave:</td>
            <td>
                <select name="leaveSchedule" id="leaveSchedule" disabled>
                    <option value="">-- Select Working Schedule --</option>
                </select>
            </td>
            <td class="error"><?php if (isset($_SESSION['leaveApplicationInputData'])): ?>
                <?php echo getError($_SESSION['leaveApplicationInputData'], 'leaveScheduleErr'); ?>
            <?php endif; ?>
            </td>
        </tr>
       
        <tr>
            <td><label for="reason">Reason:</label></td>
            <td>
                <input type="text" 
                        id="reason" 
                        name="reason" 
                        required
                 >
            </td>
            <td class="error"><?php if (isset($_SESSION['leaveApplicationInputData'])): ?>
                <?php echo getError($_SESSION['leaveApplicationInputData'], 'reasonErr'); ?>
            <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td><label for="evidencePhoto">Evidence Photo:</label></td>
            <td>
                <input type="file" 
                       id="evidencePhoto" 
                       name="evidencePhoto" 
                       accept="image/jpeg, image/png, image/gif"
                       required
                >
            </td>
            <td class="error">
                <?php if (isset($_SESSION['leaveApplicationInputData'])): ?>
                    <?php echo getError($_SESSION['leaveApplicationInputData'], 'evidencePhotoErr'); ?>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="error"><?php if (isset($_SESSION['leaveApplicationInputData'])): ?>
                <?php echo getError($_SESSION['leaveApplicationInputData'], 'leaveApplicationExistedErr'); ?>
            <?php endif; ?>
            </td>
            <td class="error"><?php if (isset($_SESSION['leaveApplicationInputData'])): ?>
                <?php echo getError($_SESSION['leaveApplicationInputData'], 'inputFormErr'); ?>
                <?php unset($_SESSION['leaveApplicationInputData']); ?>
            <?php endif; ?>
            </td>
        </tr>
        
        <tr>
            <td>
                <button type="submit" name="submitAs" value="applyLeave">
                    Apply
                </button>
            </td>
            <td>
                <?php if ($_SESSION['currentUserModel']['role']['roleID'] == 3): ?>
                    <a href="index.php?controller=user&action=staffLeaveApplication" id="cancelLink"><button type="button">
                        Cancel
                    </button></a>
                <?php else: ?>
                    <a href="index.php?controller=user&action=leaveApplicationManagement&sort=leaveDate&filter=week" id="cancelLink"><button type="button">
                        Cancel
                    </button></a>
                <?php endif; ?>
            </td>

        </tr>
        
    </table>
    
</form>


<?php
echo displayErrorMessage($data, 'errorMessage');
?>
>>>>>>> 6ea1a893419d37eb823b4036368cf06f04acc540
