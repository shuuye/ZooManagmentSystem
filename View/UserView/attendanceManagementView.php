<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInAdmin();///only allow the logged in admin to access
    
    // Get the sort key, filter key, and search query from query parameters
    $sortKey = isset($_GET['sort']) ? $_GET['sort'] : 'working_date';
    $filterKey = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Filter the attendancesArray based on the filter key
    $filteredAttendance = array_filter($data['attendancesArray'], function($attendance) use ($filterKey) {
        $currentDate = new DateTime(); // Get the current date and time
        $working_date = new DateTime($attendance['working_date']); // Convert working date to DateTime object

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
    });

    if(!empty($searchQuery)){
       // Further filter based on search query
        $initialFilteredAttendances = array_filter($filteredAttendance, function($attendance) use ($searchQuery) {
            return stripos($attendance['id'], $searchQuery) !== false ||
                   stripos($attendance['working_date'], $searchQuery) !== false ||
                   stripos($attendance['working_starting_time'], $searchQuery) !== false ||
                   stripos($attendance['working_off_time'], $searchQuery) !== false ||
                   stripos($attendance['location'], $searchQuery) !== false ||
                   stripos($attendance['attendance_date_time'], $searchQuery) !== false ||
                   stripos($attendance['status_id'], $searchQuery) !== false;
            
        });

        // Check if the initial filter resulted in any results
        if (empty($initialFilteredAttendances)) {
            // If no results found, search in attendancesStatusArray
            $matchedAttendanceStatus = array_filter($data['attendancesStatusArray'], function($attendanceStatus) use ($searchQuery) {
                return stripos($attendanceStatus['statusName'], $searchQuery) !== false;
            });

            // Extract IDs of the matched attendances Status
            $matchedAttendanceStatusIds = array_column($matchedAttendanceStatus, 'status_id');

            // Filter AttendanceArray based on the matched attendances StatusIDs
            $filteredAttendance = array_filter($filteredAttendance, function($attendance) use ($matchedAttendanceStatusIds) {
                return in_array($attendance['status_id'], $matchedAttendanceStatusIds);
            });
        } else {
            // Use the initial filtered results
            $filteredAttendance = $initialFilteredAttendances;
        }
        
    }

    // Sorting logic remains unchanged
    // Sorting logic for 'statusName'
    if ($sortKey == 'statusName') {
        usort($data['attendancesStatusArray'], function($a, $b) use ($sortKey) {
            return strcmp($a[$sortKey], $b[$sortKey]);
        });
    } else {
        usort($filteredAttendance, function($a, $b) use ($sortKey) {
            return strcmp($a[$sortKey], $b[$sortKey]);
        });
    }
    
    // Function to get the status name by status_id
    function getStatusNameByID($statusArray, $status_id) {
        foreach ($statusArray as $status) {
            if ($status['status_id'] == $status_id) {
                return htmlspecialchars($status['statusName']);
            }
        }
        return 'Unknown';
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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
    </head>
    <body>
        
        <h3>Working Attendance Table</h3>
        <!-- Filter and Search Forms -->
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
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sortKey); ?>">
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>">
            </form>

            <form id="searchForm">
                <label for="search">Search:</label>
                <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search..." oninput="updateSearchUrl()">
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sortKey); ?>">
                <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filterKey); ?>">
            </form>
        </div>

        <div class="scrollable-container">
            <table id="workingAttendanceTable" class="userManagementTable">
                <thead>
                    <tr>
                        <?php 
                            if(isset($_SESSION['currentUserModel']['permissions']) && in_array('edit', $_SESSION['currentUserModel']['permissions'])){
                                echo '<th>Action</th>';
                            }
                        ?>
                        <th><a href="?controller=user&action=attendanceManagement&sort=id&filter=<?php echo htmlspecialchars($filterKey); ?>&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">ID &#8597;</a></th>
                        <th><a href="?controller=user&action=attendanceManagement&sort=statusName&filter=<?php echo htmlspecialchars($filterKey); ?>&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Status &#8597;</a></th>
                        <th><a href="?controller=user&action=attendanceManagement&sort=working_date&filter=<?php echo htmlspecialchars($filterKey); ?>&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Working Date &#8597;</a></th>
                        <th><a href="?controller=user&action=attendanceManagement&sort=working_starting_time&filter=<?php echo htmlspecialchars($filterKey); ?>&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Working Starting Time &#8597;</a></th>
                        <th><a href="?controller=user&action=attendanceManagement&sort=working_off_time&filter=<?php echo htmlspecialchars($filterKey); ?>&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Working Off Time &#8597;</a></th>
                        <th>Photo</th>
                        <th><a href="?controller=user&action=attendanceManagement&sort=location&filter=<?php echo htmlspecialchars($filterKey); ?>&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Location &#8597;</a></th>
                        <th><a href="?controller=user&action=attendanceManagement&sort=attendance_date_time&filter=<?php echo htmlspecialchars($filterKey); ?>&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Attendance Date Time &#8597;</a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $hasCompleteAttendance = !empty($filteredAttendance);

                    if ($hasCompleteAttendance): ?>
                        <?php foreach ($filteredAttendance as $attendance): ?>
                            <?php
                            $allAttributesPresent = !empty($attendance['id']) && 
                                                    !empty($attendance['working_date']) && 
                                                    !empty($attendance['working_starting_time']) && 
                                                    !empty($attendance['working_off_time']) && 
                                                    !empty($attendance['status_id']);
                            $hasEditPermission = isset($_SESSION['currentUserModel']['permissions']) && in_array('edit', $_SESSION['currentUserModel']['permissions']);
                            ?>

                            <?php if ($allAttributesPresent): ?>
                                <tr>
                                    <?php if($hasEditPermission): ?>
                                        <td>
                                            <a href="index.php?controller=user&action=editAttendanceStatus&id=<?php echo htmlspecialchars($attendance['id']); ?>
                                                &working_date=<?php echo htmlspecialchars($attendance['working_date']); ?>
                                                &working_starting_time=<?php echo htmlspecialchars($attendance['working_starting_time']); ?>
                                                &working_off_time=<?php echo htmlspecialchars($attendance['working_off_time']); ?>">Edit Attendance Status</a>
                                        </td>
                                    <?php endif; ?>
                                    <!-- Attendance Data -->
                                    <td><?php echo htmlspecialchars($attendance['id']); ?></td>
                                    <td>
                                        <span id="statusDisplay_<?php echo htmlspecialchars($attendance['id']); ?>" 
                                            style="color:<?php echo getStatusColor($attendance['status_id']); ?>"> 
                                          <?php echo getStatusNameByID($data['attendancesStatusArray'], $attendance['status_id']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($attendance['working_date']); ?></td>
                                    <td><?php echo htmlspecialchars($attendance['working_starting_time']); ?></td>
                                    <td><?php echo htmlspecialchars($attendance['working_off_time']); ?></td>
                                    <td>
                                        <?php if (!empty($attendance['photo'])): ?>
                                            <?php
                                            // Construct the full path to the image
                                            $imagePath = '/ZooManagementSystem/assests/UserImages/attendance_photos/' . htmlspecialchars(basename($attendance['photo']));
                                            header($imagePath);
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
                        <tr><td colspan="9">No working attendance available.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <script>
            function updateUrl() {
                var filterValue = document.getElementById('filter').value;
                var sortKey = encodeURIComponent(document.querySelector('input[name="sort"]').value);
                var searchQuery = encodeURIComponent(document.querySelector('input[name="search"]').value);

                // Build the new URL with the selected filter and current sort key
                var newUrl = `?controller=user&action=attendanceManagement&sort=${sortKey}&filter=${filterValue}&search=${searchQuery}`;

                // Redirect to the new URL
                window.location.href = newUrl;
            }

            function updateSearchUrl() {
                var searchQuery = encodeURIComponent(document.getElementById('search').value.trim());
                var sortKey = encodeURIComponent(document.querySelector('input[name="sort"]').value);
                var filterKey = encodeURIComponent(document.querySelector('input[name="filter"]').value);

                // Build the new URL with the search query, filter, and sort key
                var newUrl = `?controller=user&action=attendanceManagement&sort=${sortKey}&filter=${filterKey}&search=${searchQuery}`;

                // Redirect to the new URL
                window.location.href = newUrl;
            }
       </script>
        
    </body>
</html>

