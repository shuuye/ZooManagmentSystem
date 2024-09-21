<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForLoggedInEditPermissionAdmin();//only allow the admin that have permission to edit
    
?>

<html>
    <body>
        <form action="index.php?controller=user&action=confirmLeaveApplicationDeletion" method="POST">
            <!-- Hidden input to pass user ID -->
            <?php 
                $leaveApplicationToRemove = $data['leaveApplicationToRemove'];
            ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($leaveApplicationToRemove['id']); ?>">
            <input type="hidden" name="leaveDate" value="<?php echo htmlspecialchars($leaveApplicationToRemove['leaveDate']); ?>">
            <input type="hidden" name="leaveStartTime" value="<?php echo htmlspecialchars($leaveApplicationToRemove['leaveStartTime']); ?>">
            <input type="hidden" name="leaveEndTime" value="<?php echo htmlspecialchars($leaveApplicationToRemove['leaveEndTime']); ?>">
            <input type="hidden" name="evidencePhoto" value="<?php echo htmlspecialchars($leaveApplicationToRemove['evidencePhoto']); ?>">
            
            <table>
                <tr>
                    <td colspan="2"><h4>Are you sure to delete the Leave Application:</h4></td>
                </tr>
                <tr>
                    <td>Staff ID:</td>
                    <td><?php echo htmlspecialchars($leaveApplicationToRemove['id']); ?></td>
                </tr>
                <tr>
                    <td>Leave Date:</td>
                    <td><?php echo htmlspecialchars($leaveApplicationToRemove['leaveDate']); ?></td>
                </tr>
                <tr>
                    <td>Leave Starting Time:</td>
                    <td><?php echo htmlspecialchars($leaveApplicationToRemove['leaveStartTime']); ?></td>
                </tr>
                <tr>
                    <td>Leave End Time:</td>
                    <td><?php echo htmlspecialchars($leaveApplicationToRemove['leaveEndTime']); ?></td>
                </tr>
                <tr>
                    <td>Evidence Photo:</td>
                    <td><?php if (!empty($leaveApplicationToRemove['evidencePhoto'])): ?>
                            <?php
                            // Construct the full path to the image
                            $imagePath = '/ZooManagementSystem/assests/UserImages/evidence_photos/' . htmlspecialchars(basename($leaveApplicationToRemove['evidencePhoto']));
                            header($imagePath);
                            // Check if the image file exists
                            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $imagePath)): ?>
                                <img src="<?php echo $imagePath; ?>" alt="Evidence Photo" width="100" height="100">
                            <?php else: ?>
                                <p>Image not found.</p>
                            <?php endif; ?>
                        <?php else: ?>
                            No Evidence Photo Provided
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>Leave Approve Status:</td>
                    <td>
                        <?php 
                            if (isset($leaveApplicationToRemove['approved'])) {
                                echo $leaveApplicationToRemove['approved'] == 1 ? 'Approved' : 'Not Approved'; 
                            } else {
                                echo 'Empty'; // In case the value is not set
                            }
                        ?>
                    </td>
                </tr>

            </table>

            <table>
                <tr>
                    <td>
                        <button type="submit" name="submitconfirmLeaveApplicationDeletionConfirmation">
                            Confirm
                        </button>

                    </td>
                    <td>
                        <a href="index.php?controller=user&action=leaveApplicationManagement&sort=leaveDate&filter=week" id="cancelLink"><button type="button">
                            Cancel
                        </button></a>
                    </td>
                </tr>
            </table>
        </form>
        
    </body>
</html>