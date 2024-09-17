<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForLoggedInEditPermissionAdmin();
    
    // Form HTML
?>

<html>
    <body>
        <form action="index.php?controller=user&action=confirmWorkScheduleDeletion" method="POST">
            <!-- Hidden input to pass user ID -->
            <?php 
                $workingSchedule = $data['selectedWorkingScheduleDetails'];
                $scheduleOwner = $data['scheduleOwner'];
            ?>
            <input type="hidden" name="staffId" value="<?php echo htmlspecialchars($workingSchedule['id']); ?>">
            <input type="hidden" name="workingDate" value="<?php echo htmlspecialchars($workingSchedule['workingDate']); ?>">
            <input type="hidden" name="workingStartingTime" value="<?php echo htmlspecialchars($workingSchedule['workingStartingTime']); ?>">
            <input type="hidden" name="workingOffTime" value="<?php echo htmlspecialchars($workingSchedule['workingOffTime']); ?>">
            
            <table>
                <tr>
                    <td colspan="2"><h4>Are you sure to delete the Working Schedule:</h4></td>
                </tr>
                <tr>
                    <td>Staff ID:</td>
                    <td><?php echo htmlspecialchars($scheduleOwner['id']); ?></td>
                </tr>
                <tr>
                    <td>Username:</td>
                    <td><?php echo htmlspecialchars($scheduleOwner['username']); ?></td>
                </tr>
                <tr>
                    <td>Working Date:</td>
                    <td><?php echo htmlspecialchars($workingSchedule['workingDate']); ?></td>
                </tr>
                <tr>
                    <td>Working Starting Time:</td>
                    <td><?php echo htmlspecialchars($workingSchedule['workingStartingTime']); ?></td>
                </tr>
                <tr>
                    <td>Working Off Time:</td>
                    <td><?php echo htmlspecialchars($workingSchedule['workingOffTime']); ?></td>
                </tr>

            </table>

            
            <h4 style="color: red">Deleting this Working Schedule will also delete the attendance for the working date time</h4>
            <table>
                <tr>
                    <td>Photo:</td>
                    <td><?php echo empty($attendanceSelected['photo']) ? 'No Photo Provided' : htmlspecialchars($attendanceSelected['photo']); ?></td>
                </tr>
                <tr>
                    <td>Location:</td>
                    <td><?php echo empty($attendanceSelected['location']) ? 'No Location Provided' : htmlspecialchars($attendanceSelected['location']); ?></td>
                </tr>
                <tr>
                    <td>Attendance Date Time:</td>
                    <td><?php echo empty($attendanceSelected['attendanceDateTime']) ? 'No Attendance Date Time Taken' : htmlspecialchars($attendanceSelected['attendanceDateTime']); ?></td>
                </tr>
                <tr>
                    <td>Attendance Status:</td>
                    <td>
                        <?php 
                            if (empty($attendanceSelected['statusID'])) {
                                echo 'Empty'; 
                            } else {
                                switch ($attendanceSelected['statusID']) {
                                    case 1:
                                        echo '<span style="color: blue;">Pending</span>';
                                        break;
                                    case 2:
                                        echo '<span style="color: green;">Present</span>';
                                        break;
                                    case 3:
                                        echo '<span style="color: red;">Absent</span>';
                                        break;
                                    case 4:
                                        echo '<span style="color: yellow;">Leave</span>';
                                        break;
                                    default:
                                        echo 'Unknown Status'; 
                                }
                            }
                        ?>
                    </td>
                </tr>
            </table>
            
            <table>
                <tr>
                    <td>
                        <button type="submit" name="submitWorkScheduleDeletionConfirmation">
                            Confirm
                        </button>

                    </td>
                    <td>
                        <a href="index.php?controller=user&action=workingScheduleManagement&sort=workingDate&filter=week" id="cancelLink"><button type="button">
                            Cancel
                        </button></a>
                    </td>
                </tr>
            </table>
        </form>
        
    </body>
</html>