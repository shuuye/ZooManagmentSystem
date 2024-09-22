<?php
    /*Author Name: Chew Wei Seng*/

    // URL of the API endpoint
    $url = 'http://localhost:8080/attendanceDetails';

    // Fetch the JSON data
    $jsonData = file_get_contents($url);

    // Decode the JSON data into an associative array
    $attendanceData = json_decode($jsonData, true);
    
    // Get the sort key, filter key, search query from query parameters
    $filterKey = isset($_GET['filter']) ? $_GET['filter'] : 'month';
    $selectedStaff = isset($_GET['staff']) ? $_GET['staff'] : '';
    $sortKey = isset($_GET['sort']) ? $_GET['sort'] : 'working_date';


    // Only redirect if there are missing parameters
    if (!isset($_GET['staff']) || !isset($_GET['filter'])) {
        header('Location: ?staff=' . urlencode($selectedStaff) . '&filter=' . urlencode($filterKey));
        exit; // Ensure no further code is executed after redirection
    }

    // Filter the workingSchedulesArray based on the filter key
    $filteredAttendance = array_filter($attendanceData, function($attendance) use ($filterKey, $selectedStaff) {
        $currentDate = new DateTime(); // Get the current date and time
        if (!empty($selectedStaff) && $attendance['full_name'] !== $selectedStaff) {
            return false; // Filter by selected staff if provided
        }
        if (empty($attendance['working_date'])) {
            return true; // or handle the `null` date case appropriately
        } else {
            $working_date = new DateTime($attendance['working_date']);
            switch ($filterKey) {
                case 'today':
                    return $working_date->format('Y-m-d') === $currentDate->format('Y-m-d');
                case 'week':
                    return $working_date->format('W') === $currentDate->format('W') && $working_date->format('Y') === $currentDate->format('Y');
                case 'month':
                    return $working_date->format('Y-m') === $currentDate->format('Y-m');
                case 'year':
                    return $working_date->format('Y') === $currentDate->format('Y');
                case 'all':
                default:
                    return true; // No filter applied
            }
        }
    });
    
    // Sorting logic
    if ($sortKey == 'statusName') {
        usort($filteredAttendance, function($a, $b) {
            $statusNameA = getStatusNameByID($a['status_id']);
            $statusNameB = getStatusNameByID($b['status_id']);
            return strcmp($statusNameA, $statusNameB);
        });
    } else {
        usort($filteredAttendance, function($a, $b) use ($sortKey) {
            return strcmp($a[$sortKey], $b[$sortKey]);
        });
    }

    // Get unique staff names for the dropdown
    $staffNames = array_unique(array_column($attendanceData, 'full_name'));

    // Function to get the status name by status_id
    function getStatusNameByID($status_id) {
        switch ($status_id) {
            case 1:
                return 'Pending';
            case 2:
                return 'Present';
            case 3:
                return 'Absent';
            case 4:
                return 'Leave';
            default:
                return '';
        }
    }

    // Function to get the color based on status_id
    function getStatusColor($status_id) {
        switch ($status_id) {
            case 1:
                return 'blue';
            case 2:
                return 'green';
            case 3:
                return 'red';
            case 4:
                return 'yellow';
            default:
                return 'black';
        }
    }
    
    // Initialize counters
    $totalPresent = 0;
    $totalAbsent = 0;
    $totalLeave = 0;
    $totalAttendance = 0;
    
    // Calculate the counts for Present, Absent, and Leave
    foreach ($filteredAttendance as $attendance) {
        if($attendance['status_id'] != 1){
            $totalAttendance++;
        }
        
        if ($attendance['status_id'] === 2) {
            $totalPresent++;
        } elseif ($attendance['status_id'] === 3) {
            $totalAbsent++;
        } elseif ($attendance['status_id'] === 4) {
            $totalLeave++;
        }
    }

    // Calculate the percentage of attendance (for Present status)
    $totalPercentage = $totalAttendance > 0 ? (($totalPresent + $totalLeave) / $totalAttendance) * 100 : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Details</title>
    <style>
        body{
            background-color: lightcyan;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .attendanceDetailsTable {
            background-color: white;
            border-radius: 5px;
            border: 5px solid grey;
            display: none;
            margin: 20px auto;
            max-width: 1000px;
        }
        .attendanceTable {
            font-size: 10pt;
            width: 100%;
            border-collapse: collapse;
        }
        .attendanceTable th, .attendanceTable td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .attendanceTable th {
            background-color: #f2f2f2;
        }
        .topnav {
            overflow: hidden;
            background-color: darkblue;
            height: 60px;
            color: #f2f2f2;
            text-align: center;
            font-size: 40pt;
        }

    </style>
</head>
<body>
    <header>
    <div class="topnav">
        HR Attendance Tracker
    </div>
    
    <h1 style="text-align: center; border: 1px solid gray;">Total Staff Count: <?php echo count($staffNames); ?></h1> <!--display the total of staff-->

    <h3 style="text-align: center;">See the Staff Attendance: </h3> 
    <!--dropdown to select a staff-->
    <form method="GET" action="" style="text-align: center;">
        <label for="staff">Select Staff:</label>
        <select name="staff" id="staff" onchange="this.form.submit()">
            <option value="">--Select A Staff--</option>
            <?php foreach ($staffNames as $staff): ?>
                <option value="<?php echo htmlspecialchars($staff); ?>" <?php if ($selectedStaff === $staff) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($staff); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filterKey); ?>">
    </form>

    <div class="attendanceDetailsTable" id="attendanceDetailsTable" style="display: <?php echo !empty($selectedStaff) ? 'block' : 'none'; ?>">
        <div>
            <form method="GET" action="" id="filterForm">
                <label for="filter">Filter by:</label>
                <select name="filter" id="filter" onchange="updateUrl()">
                    <option value="all" <?php if ($filterKey === 'all') echo 'selected'; ?>>All</option>
                    <option value="today" <?php if ($filterKey === 'today') echo 'selected'; ?>>Today</option>
                    <option value="week" <?php if ($filterKey === 'week') echo 'selected'; ?>>This Week</option>
                    <option value="month" <?php if ($filterKey === 'month') echo 'selected'; ?>>This Month</option>
                    <option value="year" <?php if ($filterKey === 'year') echo 'selected'; ?>>This Year</option>
                </select>
                <input type="hidden" name="staff" value="<?php echo htmlspecialchars($selectedStaff); ?>">
            </form>
            
        </div>
        
        <h3>Attendance Details</h3>
        
        <table style="margin: 50px auto; border-collapse: collapse; width: 50%;">
            <tr>
                <th style="color: green; text-align:center; padding-right: 20px;">Present</th>
                <th style="color: red; text-align:center; padding-right: 20px;">Absent</th>
                <th style="color: yellow; text-align:center; padding-right: 20px;">Leave</th>
                <th style="text-align:center;">Total Percentage</th>
            </tr>
            <tr>
                <!-- Display the counts for Present, Absent, Leave -->
                <td style="color: green; text-align:center; padding-right: 20px;"><?php echo $totalPresent; ?></td>
                <td style="color: red; text-align:center; padding-right: 20px;"><?php echo $totalAbsent; ?></td>
                <td style="color: yellow; text-align:center; padding-right: 20px;"><?php echo $totalLeave; ?></td>

                <!-- Conditionally display the total percentage in different colors -->
                <td style="text-align:center;">
                    <?php if ($totalPercentage < 80): ?>
                        <span style="color: red;"><?php echo number_format($totalPercentage, 2); ?>%</span>
                    <?php else: ?>
                        <span style="color: green;"><?php echo number_format($totalPercentage, 2); ?>%</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        
        <table class="attendanceTable">
            <thead>
                <tr>
                    <th><a href="?sort=full_name&filter=<?php echo htmlspecialchars($filterKey); ?>&staff=<?php echo htmlspecialchars($selectedStaff); ?>" class="full-clickable">Full Name &#8597;</a></th>
                    <th><a href="?sort=statusName&filter=<?php echo htmlspecialchars($filterKey); ?>&staff=<?php echo htmlspecialchars($selectedStaff); ?>"" class="full-clickable">Status &#8597;</a></th>
                    <th><a href="?sort=phone_number&filter=<?php echo htmlspecialchars($filterKey); ?>&staff=<?php echo htmlspecialchars($selectedStaff); ?>"" class="full-clickable">Phone Number &#8597;</a></th>
                    <th><a href="?sort=email&filter=<?php echo htmlspecialchars($filterKey); ?>&staff=<?php echo htmlspecialchars($selectedStaff); ?>"" class="full-clickable">Email &#8597;</a></th>
                    <th><a href="?sort=position&filter=<?php echo htmlspecialchars($filterKey); ?>&staff=<?php echo htmlspecialchars($selectedStaff); ?>"" class="full-clickable">Position &#8597;</a></th>
                    <th><a href="?sort=working_date&filter=<?php echo htmlspecialchars($filterKey); ?>&staff=<?php echo htmlspecialchars($selectedStaff); ?>"" class="full-clickable">Working Date &#8597;</a></th>
                    <th><a href="?sort=working_starting_time&filter=<?php echo htmlspecialchars($filterKey); ?>&staff=<?php echo htmlspecialchars($selectedStaff); ?>"" class="full-clickable">Starting Time &#8597;</a></th>
                    <th><a href="?sort=working_off_time&filter=<?php echo htmlspecialchars($filterKey); ?>&staff=<?php echo htmlspecialchars($selectedStaff); ?>"" class="full-clickable">Off Time &#8597;</a></th>
                    <th>Photo</th>
                    <th><a href="?sort=location&filter=<?php echo htmlspecialchars($filterKey); ?>&staff=<?php echo htmlspecialchars($selectedStaff); ?>"" class="full-clickable">Location &#8597;</a></th>
                    <th><a href="?sort=attendance_date_time&filter=<?php echo htmlspecialchars($filterKey); ?>&staff=<?php echo htmlspecialchars($selectedStaff); ?>"" class="full-clickable">Attendance Date Time &#8597;</a></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $hasCompleteAttendance = !empty($filteredAttendance);

                if ($hasCompleteAttendance): ?>
                    <?php foreach ($filteredAttendance as $attendance): ?>
                        <?php
                        $allAttributesPresent = !empty($attendance['working_date']) && 
                                                !empty($attendance['working_starting_time']) && 
                                                !empty($attendance['working_off_time']) && 
                                                !empty($attendance['status_id']);
                        ?>
                        <?php if ($allAttributesPresent): ?>

                            <tr>
                                <td><?php echo htmlspecialchars($attendance['full_name']); ?></td>
                                <td style="color: <?php echo getStatusColor($attendance['status_id']); ?>">
                                    <?php echo getStatusNameByID($attendance['status_id']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($attendance['phone_number']); ?></td>
                                <td><?php echo htmlspecialchars($attendance['email']); ?></td>
                                <td><?php echo htmlspecialchars($attendance['position']); ?></td>
                                <td><?php echo htmlspecialchars($attendance['working_date'] ?? 'No Date Provided'); ?></td>
                                <td><?php echo htmlspecialchars($attendance['working_starting_time'] ?? 'No Start Time'); ?></td>
                                <td><?php echo htmlspecialchars($attendance['working_off_time'] ?? 'No Off Time'); ?></td>
                                <td>
                                    <?php if (!empty($attendance['photo'])): ?>
                                        <?php
                                        // Construct the full path to the image
                                        $imagePath = '/ZooManagementSystem/assests/UserImages/attendance_photos/' . htmlspecialchars(basename($attendance['photo']));
                                        //header($imagePath);
                                        // Check if the image file exists
                                        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $imagePath)): ?>
                                            <img src="<?php echo $imagePath; ?>" alt="Attendance Photo" width="100" height="100">
                                        <?php else: ?>
                                            <p>Image not found.</p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        No Evidence Photo Provided
                                    <?php endif; ?>
                                </td>
                                <td><?php echo !empty($attendance['location']) ? htmlspecialchars($attendance['location']) : 'No Location Provided'; ?></td>
                                <td><?php echo !empty($attendance['attendance_date_time']) ? htmlspecialchars($attendance['attendance_date_time']) : 'No Attendance Date Time Taken'; ?></td>
                            </tr>

                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11">No attendance data found for the selected staff and filter.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function updateUrl() {
            const filterSelect = document.getElementById('filter');
            const selectedFilter = filterSelect.value;
            const currentUrl = window.location.href;
            const newUrl = currentUrl.replace(/(filter=)[^&]+/, '$1' + selectedFilter);
            window.location.href = newUrl;
        }

    </script>
</body>
</html>
