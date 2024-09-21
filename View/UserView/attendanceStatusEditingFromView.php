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
?>

<h1>Attendance Status Editing Form</h1>
<form action="index.php?controller=user&action=updateAttendanceStatus" method="POST">
    <table>
        <?php 

                if (isset($data['selectedAttendance']) && isset($data['action'])) {
                    if ($data['action'] == 'edit' && isset($data['selectedAttendance']['id'])) {
                        // Correctly output the hidden input field
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($data['selectedAttendance']['id']) . '">';
                    }
                }
                ?>
        <?php if ((isset($data['action']) && isset($data['selectedAttendance'])) || (isset($data['action']) && $data['action'] == 'edit')): ?>
                <tr>
                    <td><label for="id">Staff ID:</label></td>
                    <td>
                        <input type="text" id="id" name="id" 
                               autocomplete="off" 
                               required 
                               value="<?php echo htmlspecialchars($data['selectedAttendance']['id']) ?>"
                               disabled
                               >
                    </td>
                </tr>
        <?php endif; ?>
        
        
        <?php 
                if (isset($data['selectedAttendance']) && isset($data['action'])) {
                    if ($data['action'] == 'edit' && isset($data['selectedAttendance']['working_date'])) {
                        // Correctly output the hidden input field
                        echo '<input type="hidden" name="working_date" value="' . htmlspecialchars($data['selectedAttendance']['working_date']) . '">';
                    }
                }
                ?>
        <?php if ((isset($data['action']) && isset($data['selectedAttendance'])) || (isset($data['action']) && $data['action'] == 'edit')): ?>
                <tr>
                    <td><label for="working_date">Working Date:</label></td>
                    <td>
                        <input type="text" id="working_date" name="working_date" 
                               autocomplete="off" 
                               required 
                               value="<?php echo htmlspecialchars($data['selectedAttendance']['working_date']) ?>"
                               disabled
                               >
                    </td>
                </tr>
        <?php endif; ?>
                
        <?php 
                if (isset($data['selectedAttendance']) && isset($data['action'])) {
                    if ($data['action'] == 'edit' && isset($data['selectedAttendance']['working_starting_time'])) {
                        // Correctly output the hidden input field
                        echo '<input type="hidden" name="working_starting_time" value="' . htmlspecialchars($data['selectedAttendance']['working_starting_time']) . '">';
                    }
                }
                ?>
        <?php if ((isset($data['action']) && isset($data['selectedAttendance'])) || (isset($data['action']) && $data['action'] == 'edit')): ?>
                <tr>
                    <td><label for="working_starting_time">Working Starting Time:</label></td>
                    <td>
                        <input type="text" id="working_starting_time" name="working_starting_time" 
                               autocomplete="off" 
                               required 
                               value="<?php echo htmlspecialchars($data['selectedAttendance']['working_starting_time']) ?>"
                               disabled
                               >
                    </td>
                </tr>
        <?php endif; ?>
                
        <?php 
                if (isset($data['selectedAttendance']) && isset($data['action'])) {
                    if ($data['action'] == 'edit' && isset($data['selectedAttendance']['working_off_time'])) {
                        // Correctly output the hidden input field
                        echo '<input type="hidden" name="working_off_time" value="' . htmlspecialchars($data['selectedAttendance']['working_off_time']) . '">';
                    }
                }
                ?>
        <?php if ((isset($data['action']) && isset($data['selectedAttendance'])) || (isset($data['action']) && $data['action'] == 'edit')): ?>
                <tr>
                    <td><label for="working_off_time">Working Off Time:</label></td>
                    <td>
                        <input type="text" id="working_off_time" name="working_off_time" 
                               autocomplete="off" 
                               required 
                               value="<?php echo htmlspecialchars($data['selectedAttendance']['working_off_time']) ?>"
                               disabled
                               >
                    </td>
                </tr>
        <?php endif; ?>
        
        
        <?php
            // Determine the default attendance status
            $defaultAttendanceStatus = isset($data['selectedAttendance']['status_id']) ? $data['selectedAttendance']['status_id'] : 1;

            // Check if the user is authenticated and has the appropriate permissions
            if (isset($_SESSION['currentUserModel']) && isset($_SESSION['currentUserModel']['role']['roleID']) && isset($_SESSION['currentUserModel']['adminType']) && isset($_SESSION['currentUserModel']['permissions'])) {
                if ($_SESSION['currentUserModel']['role']['roleID'] == 1 && in_array('edit', $_SESSION['currentUserModel']['permissions'])) {
                    // Generate the dropdown list
                    echo '<label for="status_id">Select Attendance Status:</label>';
                    echo '<select name="status_id" id="status_id">';

                    // Iterate over attendanceStatusArray to populate the dropdown
                    foreach ($data['attendanceStatusArray'] as $status) {
                        $selected = ($status['status_id'] == $defaultAttendanceStatus) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($status['status_id']) . '" ' . $selected . '>' . htmlspecialchars($status['statusName']) . '</option>';
                    }

                    echo '</select>';
                } else {
                    echo '<input type="hidden" name="status_id" value="' . htmlspecialchars($defaultAttendanceStatus) . '">';
                }
            } else {
                // Default role for guests or non-authenticated users
                echo '<input type="hidden" name="status_id" value="' . htmlspecialchars($defaultAttendanceStatus) . '">';
            }
        ?>
        
        <tr>
            <td>
                <button type="submit" name="submitAs" value="editAdminDetails">
                    Edit
                </button>
                
            </td>
            <td>
                <a href="index.php?controller=user&action=attendanceManagement&sort=working_date&filter=week" id="cancelLink"><button type="button">
                    Cancel
                </button></a>
            </td>
        </tr>
    </table>
</form>
<script>

</script>
<?php
echo displayErrorMessage($data, 'errorMessage');
?>
