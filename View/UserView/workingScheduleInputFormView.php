<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForLoggedInEditPermissionAdmin();

    function getError($data, $key) {
        return isset($data[$key]) && $data[$key] !== '' ? htmlspecialchars($data[$key]) : '';
    }

    function displayErrorMessage($data, $key) {
        return isset($data[$key]) && $data[$key] !== '' ? '<div class="error-message">' . htmlspecialchars($data[$key]) . '</div>' : '';
    }

    function getStaffsOptions($staffs, $selectedStaffID) {
        $options = '';
        foreach ($staffs as $staff) {
            $isSelected = ($staff["id"] == $selectedStaffID) ? "selected" : "";
            $options .= '<option value="' . htmlspecialchars($staff["id"]) . '" ' . $isSelected . '>' . htmlspecialchars($staff["username"]) . '</option>';
        }
        return $options;
    }

    function getSubmitButtonValue($data) {
        if (!isset($_SESSION['currentUserModel'])) {
            return 'addWorkingSchedule';
        }

        // Check if the user is an admin
        if ($_SESSION['currentUserModel']['role']['roleID'] != 1) {
            return 'addWorkingSchedule';
        }

        // Handle when the action and selected user are set
        if (isset($data['action']) && isset($data['selectedWorkingSchedule'])) {
            return $data['action'] == 'edit' ? 'adminEdit' : 'addWorkingSchedule';
        }

        return 'addWorkingSchedule';
    }

    function getSubmitButtonText($data) {
        if (!isset($_SESSION['currentUserModel'])) {
            return 'Add Working Schedule';
        }

        // Check if the user is an admin
        if ($_SESSION['currentUserModel']['role']['roleID'] != 1) {
            return 'Add Working Schedule';
        }

        // Handle when the action and selected user are set
        if (isset($data['action']) && isset($data['selectedWorkingSchedule'])) {
            return $data['action'] == 'edit' ? 'Edit Working Schedule' : 'Add Working Schedule';
        }

        return 'Add Working Schedule';
    }

    // Get roles
    $staffs = $data['staffsArray'];

    // Form HTML

?>

<?php 
    if(isset($data['action'])){
        if ($data['action'] == 'edit'){
            echo '<h1>Editing Working Schedule Form</h1>';
        }
    }else{
        echo '<h1>Create New Working Schedule Form</h1>';
    }
?>


<?php if (isset($data['selectedWorkingSchedule']) && isset($data['action'])): ?>
    <?php if ($data['action'] == 'edit' && isset($data['selectedWorkingSchedule']['workingOffTime'])): ?>
        <h4>Editing this working Schedule will might also cause its current attendance be clear and reset.</h4>
    <?php endif; ?>
<?php endif; ?>
   
<form action="index.php?controller=user&action=submitWorkingScheduleInputForm" method="POST">
    <style>
        .error{
            color:red;
        }
    </style>
    <table>
        <?php
            // Handle staff selection based on user permissions
            if (isset($_SESSION['currentUserModel']) && isset($_SESSION['currentUserModel']['role']['roleID']) && isset($_SESSION['currentUserModel']['permissions'])) {
                if ($_SESSION['currentUserModel']['role']['roleID'] == 1 && in_array('edit', $_SESSION['currentUserModel']['permissions'])) {
                    $selectedStaffID = isset($data['selectedWorkingSchedule']['id']) ? $data['selectedWorkingSchedule']['id'] : '';
                    echo '
                    <tr>
                        <td>Staff:</td>
                        <td>
                            <select name="staffId">
                                ' . getStaffsOptions($staffs, $selectedStaffID) . '
                            </select>
                        </td>
                    </tr>';
                } 
            } 
        ?>
        
        <tr>
            <td><label for="workingDate">Working Date:</label></td>
            <td>
                <input type="date" 
                        id="workingDate" 
                        name="workingDate" 
                        required
                        value="<?php 
                           if (isset($data['selectedWorkingSchedule']) && isset($data['action'])) {
                               if ($data['action'] == 'edit' && isset($data['selectedWorkingSchedule']['workingDate'])) {
                                   echo htmlspecialchars($data['selectedWorkingSchedule']['workingDate']);
                               }
                           }
                       ?>"
                 >
            </td>
            <td class="error"><?php if (isset($data['workingScheduleInputData'])): ?>
                <?php echo getError($data['workingScheduleInputData'], 'workingDateErr'); ?>
            <?php endif; ?>
            </td>
        </tr>
        
        <tr>
            <td><label for="workingStartingTime">Working Starting Time:</label></td>
            <td>
                <input type="time" 
                       id="workingStartingTime" 
                       name="workingStartingTime" 
                       required
                       value="<?php 
                          if (isset($data['selectedWorkingSchedule']) && isset($data['action'])) {
                              if ($data['action'] == 'edit' && isset($data['selectedWorkingSchedule']['workingStartingTime'])) {
                                  echo htmlspecialchars(date('H:i', strtotime($data['selectedWorkingSchedule']['workingStartingTime']))); 
                              }
                          }
                      ?>"
                >
            </td>
            <td class="error"><?php if (isset($data['workingScheduleInputData'])): ?>
                <?php echo getError($data['workingScheduleInputData'], 'workingStartingTimeErr'); ?>
            <?php endif; ?>
            </td>
        </tr>
        
        <tr>
            <td><label for="workingOffTime">Working Off Time:</label></td>
            <td>
                <input type="time" 
                       id="workingOffTime" 
                       name="workingOffTime" 
                       required
                       value="<?php 
                          if (isset($data['selectedWorkingSchedule']) && isset($data['action'])) {
                              if ($data['action'] == 'edit' && isset($data['selectedWorkingSchedule']['workingOffTime'])) {
                                  echo htmlspecialchars(date('H:i', strtotime($data['selectedWorkingSchedule']['workingOffTime'])));
                              }
                          }
                      ?>"
                >
            </td>
            <td class="error"><?php if (isset($data['workingScheduleInputData'])): ?>
                <?php echo getError($data['workingScheduleInputData'], 'workingOffTimeErr'); ?>
            <?php endif; ?>
            </td>
        </tr>
        
        <tr>
            <td class="error"><?php if (isset($data['workingScheduleInputData'])): ?>
                <?php echo getError($data['workingScheduleInputData'], 'workingScheduleExistedErr'); ?>
            <?php endif; ?>
            </td>
            <td class="error"><?php if (isset($data['workingScheduleInputData'])): ?>
                <?php echo getError($data['workingScheduleInputData'], 'inputFormErr'); ?>
            <?php endif; ?>
            </td>
        </tr>
        
        <tr>
            <td>
                <button type="submit" name="submitAs" value="<?php echo htmlspecialchars(getSubmitButtonValue($data)); ?>">
                    <?php echo htmlspecialchars(getSubmitButtonText($data)); ?>
                </button>
            </td>
            <td>
                
                <a href="index.php?controller=user&action=workingScheduleManagement&sort=workingDate&filter=week" id="cancelLink"><button type="button">
                    Cancel
                </button></a>
            </td>

        </tr>
        
        <?php 
            if (isset($data['selectedWorkingSchedule']) && isset($data['action'])) {
                if ($data['action'] == 'edit') {
                    // Correctly output the hidden input field
                    echo '<input type="hidden" name="oldId" value="' . htmlspecialchars($data['selectedWorkingSchedule']['id']) . '">';
                    echo '<input type="hidden" name="oldWorkingDate" value="' . htmlspecialchars($data['selectedWorkingSchedule']['workingDate']) . '">';
                    echo '<input type="hidden" name="oldWorkingStartingTime" value="' . htmlspecialchars($data['selectedWorkingSchedule']['workingStartingTime']) . '">';
                    echo '<input type="hidden" name="oldWorkingOffTime" value="' . htmlspecialchars($data['selectedWorkingSchedule']['workingOffTime']) . '">';
                }
            }
        ?>
    </table>
</form>

<?php
echo displayErrorMessage($data, 'errorMessage');
?>
