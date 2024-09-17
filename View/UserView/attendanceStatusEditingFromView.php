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
                    if ($data['action'] == 'edit' && isset($data['selectedAttendance']['workingDate'])) {
                        // Correctly output the hidden input field
                        echo '<input type="hidden" name="workingDate" value="' . htmlspecialchars($data['selectedAttendance']['workingDate']) . '">';
                    }
                }
                ?>
        <?php if ((isset($data['action']) && isset($data['selectedAttendance'])) || (isset($data['action']) && $data['action'] == 'edit')): ?>
                <tr>
                    <td><label for="workingDate">Working Date:</label></td>
                    <td>
                        <input type="text" id="workingDate" name="workingDate" 
                               autocomplete="off" 
                               required 
                               value="<?php echo htmlspecialchars($data['selectedAttendance']['workingDate']) ?>"
                               disabled
                               >
                    </td>
                </tr>
        <?php endif; ?>
                
        <?php 
                if (isset($data['selectedAttendance']) && isset($data['action'])) {
                    if ($data['action'] == 'edit' && isset($data['selectedAttendance']['workingStartingTime'])) {
                        // Correctly output the hidden input field
                        echo '<input type="hidden" name="workingStartingTime" value="' . htmlspecialchars($data['selectedAttendance']['workingStartingTime']) . '">';
                    }
                }
                ?>
        <?php if ((isset($data['action']) && isset($data['selectedAttendance'])) || (isset($data['action']) && $data['action'] == 'edit')): ?>
                <tr>
                    <td><label for="workingStartingTime">Working Starting Time:</label></td>
                    <td>
                        <input type="text" id="workingStartingTime" name="workingStartingTime" 
                               autocomplete="off" 
                               required 
                               value="<?php echo htmlspecialchars($data['selectedAttendance']['workingStartingTime']) ?>"
                               disabled
                               >
                    </td>
                </tr>
        <?php endif; ?>
                
        <?php 
                if (isset($data['selectedAttendance']) && isset($data['action'])) {
                    if ($data['action'] == 'edit' && isset($data['selectedAttendance']['workingOffTime'])) {
                        // Correctly output the hidden input field
                        echo '<input type="hidden" name="workingOffTime" value="' . htmlspecialchars($data['selectedAttendance']['workingOffTime']) . '">';
                    }
                }
                ?>
        <?php if ((isset($data['action']) && isset($data['selectedAttendance'])) || (isset($data['action']) && $data['action'] == 'edit')): ?>
                <tr>
                    <td><label for="workingOffTime">Working Off Time:</label></td>
                    <td>
                        <input type="text" id="workingOffTime" name="workingOffTime" 
                               autocomplete="off" 
                               required 
                               value="<?php echo htmlspecialchars($data['selectedAttendance']['workingOffTime']) ?>"
                               disabled
                               >
                    </td>
                </tr>
        <?php endif; ?>
        
        
        <?php
            // Determine the default attendance status
            $defaultAttendanceStatus = isset($data['selectedAttendance']['statusID']) ? $data['selectedAttendance']['statusID'] : 1;

            // Check if the user is authenticated and has the appropriate permissions
            if (isset($_SESSION['currentUserModel']) && isset($_SESSION['currentUserModel']['role']['roleID']) && isset($_SESSION['currentUserModel']['adminType']) && isset($_SESSION['currentUserModel']['permissions'])) {
                if ($_SESSION['currentUserModel']['role']['roleID'] == 1 && in_array('edit', $_SESSION['currentUserModel']['permissions'])) {
                    // Generate the dropdown list
                    echo '<label for="statusID">Select Attendance Status:</label>';
                    echo '<select name="statusID" id="statusID">';

                    // Iterate over attendanceStatusArray to populate the dropdown
                    foreach ($data['attendanceStatusArray'] as $status) {
                        $selected = ($status['statusID'] == $defaultAttendanceStatus) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($status['statusID']) . '" ' . $selected . '>' . htmlspecialchars($status['statusName']) . '</option>';
                    }

                    echo '</select>';
                } else {
                    echo '<input type="hidden" name="statusID" value="' . htmlspecialchars($defaultAttendanceStatus) . '">';
                }
            } else {
                // Default role for guests or non-authenticated users
                echo '<input type="hidden" name="statusID" value="' . htmlspecialchars($defaultAttendanceStatus) . '">';
            }
        ?>
        
        <tr>
            <td>
                <button type="submit" name="submitAs" value="editAdminDetails">
                    Edit
                </button>
                
            </td>
            <td>
                <a href="index.php?controller=user&action=attendanceManagement&sort=workingDate&filter=week" id="cancelLink"><button type="button">
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
