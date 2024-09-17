<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../Config/webConfig.php';
$webConfig = new webConfig();
$webConfig->restrictAccessForNonLoggedInStaff();

function getError($data, $key) {
    return isset($data[$key]) && $data[$key] !== '' ? htmlspecialchars($data[$key]) : '';
}

function displayErrorMessage($data, $key) {
    return isset($data[$key]) && $data[$key] !== '' ? '<div class="error-message">' . htmlspecialchars($data[$key]) . '</div>' : '';
}

// Get the current date and time
$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');

// Get attendances for the staff
$attendances = $data['staffOwnAttendanceArray'];

// Check if today's attendance exists and if it's within valid working time
$attendanceToday = false;
$validTime = false;
$id = '';
$workingStartingTime = '';
$workingOffTime = '';
$workingDate = '';
$attendanceStatus = '';

foreach ($attendances as $attendance) {
    if ($attendance['workingDate'] === $currentDate) {
        $attendanceToday = true;
        $id = $attendance['id'];
        $workingStartingTime = $attendance['workingStartingTime'];
        $workingOffTime = $attendance['workingOffTime'];
        $workingDate = $attendance['workingDate'];
        $attendanceStatus = $attendance['statusID'];
        
        // Convert times to timestamps for comparison
        $startTime = strtotime($workingStartingTime);
        $offTime = strtotime($workingOffTime);
        $currentTimestamp = strtotime($currentTime);
        
        // Check if current time is between workingStartingTime and workingOffTime
        if ($currentTimestamp >= $startTime && $currentTimestamp <= $offTime) {
            $validTime = true;
        }
        break;
    }
}
?>

<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(setPosition, showError);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function setPosition(position) {
        document.getElementById('latitude').value = position.coords.latitude;
        document.getElementById('longitude').value = position.coords.longitude;
    }

    function showError(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                alert("User denied the request for Geolocation.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Location information is unavailable.");
                break;
            case error.TIMEOUT:
                alert("The request to get user location timed out.");
                break;
            case error.UNKNOWN_ERROR:
                alert("An unknown error occurred.");
                break;
        }
    }

    // Call getLocation when the page loads
    window.onload = function() {
        getLocation();
    };
</script>

<h2 style="text-align: center;">Take Attendance Here</h2>

<?php if ($attendanceToday): ?>
    <?php if ($validTime): ?>
        <?php if ($attendanceStatus != 2): ?>
            <!-- If there's work today and the current time is valid, display "Take Attendance" -->
            <h3 style="text-align: center; margin-top: 30px;">Take Attendance For : <?php echo $workingDate; ?> from <?php echo $workingStartingTime; ?> to <?php echo $workingOffTime; ?></h3>

            <form action="index.php?controller=user&action=takeAttendance" method="POST" enctype="multipart/form-data">
                <style>
                    .error {
                        color: red;
                    }
                </style>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="workingStartingTime" value="<?php echo htmlspecialchars($workingStartingTime, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="workingOffTime" value="<?php echo htmlspecialchars($workingOffTime, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="workingDate" value="<?php echo htmlspecialchars($workingDate, ENT_QUOTES, 'UTF-8'); ?>">

                <!-- Hidden fields for latitude and longitude -->
                <input type="hidden" id="latitude" name="latitude" value="">
                <input type="hidden" id="longitude" name="longitude" value="">

                <table style="margin: 50px auto;">
                    <tr>
                        <td style="text-align: center; font-size: 25pt "><label for="attendancePhoto">Attendance Photo:</label></td>
                    </tr>
                    <tr>
                        <td>
                            <input type="file" 
                                   id="attendancePhoto" 
                                   name="attendancePhoto" 
                                   accept="image/jpeg, image/png, image/gif"
                                   required
                                   style="padding-left: 100px;margin-top: 30px;">
                        </td>
                    </tr>
                    <tr>
                        <td class="error">
                            <?php if (isset($_SESSION['attendanceInputData'])): ?>
                                <?php echo getError($_SESSION['attendanceInputData'], 'attendancePhotoErr'); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="error">
                            <?php if (isset($_SESSION['attendanceInputData'])): ?>
                                <?php echo getError($_SESSION['attendanceInputData'], 'locationErr'); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="error"><?php if (isset($_SESSION['attendanceInputData'])): ?>
                            <?php echo '<p style="margin-top: 30px; center; background-color: red; color:white; text-align: center;">' . getError($_SESSION['attendanceInputData'], 'inputFormErr') . '</p>'?>
                            <?php unset($_SESSION['attendanceInputData']); ?>
                        <?php endif; ?>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center;">
                            <button type="submit" name="submitAs" value="takeCurrentAttendance" style="margin-top: 80px;">
                                Take Attendance
                            </button>
                        </td>
                    </tr>
                </table>
            </form>
        <?php else: ?>
            <!-- If it's attendance is taken, display an error message with working schedule -->
            <div class="attendance-message" style="text-align: center; font-size: 18pt; color: green;">
                The Attendance is Taken 
                <br> Working Schedule: <?php echo $workingDate; ?> from <?php echo $workingStartingTime; ?> to <?php echo $workingOffTime; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <!-- If it's outside of working hours, display an error message with working schedule -->
        <div class="attendance-message" style="text-align: center; font-size: 18pt; color: red;">
            You are outside of the valid working time! 
            <br> Working Schedule: <?php echo $workingDate; ?> from <?php echo $workingStartingTime; ?> to <?php echo $workingOffTime; ?>
        </div>
    <?php endif; ?>
<?php else: ?>
    <!-- If there's no work today, display "You Have No Work Today" -->
    <div class="attendance-message" style="text-align: center; font-size: 18pt; color: red;">You Have No Work Today</div>
<?php endif; ?>
