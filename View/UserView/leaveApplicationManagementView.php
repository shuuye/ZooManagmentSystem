<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInAdmin();
    
    $leaveAppliedSuccessfully = isset($_SESSION['leaveAppliedSuccessfully']) ? $_SESSION['leaveAppliedSuccessfully'] : '';
    $leaveApplicationRemovedSuccessfully = isset($_SESSION['leaveApplicationRemovedSuccessfully']) ? $_SESSION['leaveApplicationRemovedSuccessfully'] : '';
    
    // Get the sort key, filter key, and search query from query parameters
    $sortKey = isset($_GET['sort']) ? $_GET['sort'] : 'workingDate';
    $filterKey = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Filter the workingSchedulesArray based on the filter key
    $filteredLeaveApplication = array_filter($data['leaveApplicationsArray'], function($leaveApplication) use ($filterKey) {
        $currentDate = new DateTime(); // Get the current date and time
        $leaveDate = new DateTime($leaveApplication['leaveDate']); // Convert working date to DateTime object

        switch ($filterKey) {
            case 'today':
                return $leaveDate->format('Y-m-d') === $currentDate->format('Y-m-d');
            case 'week':
                return $leaveDate->format('W') === $currentDate->format('W') && $leaveDate->format('Y') === $currentDate->format('Y');
            case 'month':
                return $leaveDate->format('Y-m') === $currentDate->format('Y-m');
            case 'year':
                return $leaveDate->format('Y') === $currentDate->format('Y');
            case 'all':
            default:
                return true; // No filter applied
        }
    });

    if(!empty($searchQuery)){
       // Further filter based on search query
        $initialFilteredLeaveApplication = array_filter($filteredLeaveApplication, function($leaveApplication) use ($searchQuery) {
            return stripos($leaveApplication['id'], $searchQuery) !== false ||
                   stripos($leaveApplication['reason'], $searchQuery) !== false ||
                   stripos($leaveApplication['leaveDate'], $searchQuery) !== false ||
                   stripos($leaveApplication['leaveStartTime'], $searchQuery) !== false ||
                   stripos($leaveApplication['leaveEndTime'], $searchQuery) !== false ;
                   
        });

        $filteredLeaveApplication = $initialFilteredLeaveApplication;
    }

    // Sorting logic remains unchanged
    usort($filteredLeaveApplication, function($a, $b) use ($sortKey) {
        return strcmp($a[$sortKey], $b[$sortKey]);
    });
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <style>
            .successSessionMsg{
                color: white; 
                border: 1px solid green; 
                width: 30%; 
                height: 40px;
                background-color: green;
                align-items: center;
                display: flex;
                justify-content: space-around;
            }
            .failedSessionMsg{
                color: white; 
                border: 1px solid red; 
                width: 30%; 
                height: 40px;
                background-color: red;
                align-items: center;
                display: flex;
                justify-content: space-around;
            }
        </style>
        <?php if (!empty($leaveAppliedSuccessfully)): ?>
            <div class="successSessionMsg">
                <?php echo htmlspecialchars($leaveAppliedSuccessfully); ?>
            </div>
            <?php unset($_SESSION['leaveAppliedSuccessfully']); // Clear the session message after displaying ?>
        <?php endif; ?>
        <?php if (!empty($leaveApplicationRemovedSuccessfully)): ?>
            <div class="successSessionMsg">
                <?php echo htmlspecialchars($leaveApplicationRemovedSuccessfully); ?>
            </div>
            <?php unset($_SESSION['leaveApplicationRemovedSuccessfully']); // Clear the session message after displaying ?>
        <?php endif; ?>
        
        <h3>Leave Application Table</h3>
        
        <!-- Filter dropdown -->
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
                
                <!-- Pass the current sort key as a hidden input -->
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sortKey); ?>">
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>">
            </form>
            
            <form id="searchForm">
                <!-- Search Bar -->
                <label for="search">Search:</label>
                <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search..." oninput="updateSearchUrl()" />

                <!-- Hidden inputs for sort and filter -->
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sortKey); ?>">
                <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filterKey); ?>">
            </form>
        </div>
        

        <div class="scrollable-container">
            <table id="leaveApplicationTable" class="userManagementTable">
                <thead>
                    <tr>
                        <?php
                        if (isset($_SESSION['currentUserModel']) && isset($_SESSION['currentUserModel']['role']['roleID']) && isset($_SESSION['currentUserModel']['permissions'])) {
                            if ($_SESSION['currentUserModel']['role']['roleID'] == 1 && in_array('edit', $_SESSION['currentUserModel']['permissions'])) {
                                echo '
                                <th>Action</th>';
                            } 
                        } ?>
                        <th><a href="?controller=user&action=leaveApplicationManagement&sort=id&filter=<?php echo htmlspecialchars($filterKey); ?>&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">ID &#8597;</a></th>
                        <th><a href="?controller=user&action=leaveApplicationManagement&sort=reason&filter=<?php echo htmlspecialchars($filterKey); ?>&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Reason &#8597;</a></th>
                        <th>Evidence Photo</th>
                        <th><a href="?controller=user&action=leaveApplicationManagement&sort=leaveDate&filter=<?php echo htmlspecialchars($filterKey); ?>&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Leave Date &#8597;</a></th>
                        <th><a href="?controller=user&action=leaveApplicationManagement&sort=leaveStartTime&filter=<?php echo htmlspecialchars($filterKey); ?>&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Leave Starting Time &#8597;</a></th>
                        <th><a href="?controller=user&action=leaveApplicationManagement&sort=leaveEndTime&filter=<?php echo htmlspecialchars($filterKey); ?>&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Leave End Time &#8597;</a></th>
                        <th><a href="?controller=user&action=leaveApplicationManagement&sort=approved&filter=<?php echo htmlspecialchars($filterKey); ?>&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">is Approved? &#8597;</a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Determine if there are any working schedules with all required attributes
                    $hasCompleteLeaveApplivation = !empty($filteredLeaveApplication);

                    if ($hasCompleteLeaveApplivation): ?>
                        <?php foreach ($filteredLeaveApplication as $leaveApplication): ?>
                            <?php
                            $allAttributesPresent = !empty($leaveApplication['id']) && 
                                                    !empty($leaveApplication['reason']) && 
                                                    !empty($leaveApplication['leaveDate'])&& 
                                                    !empty($leaveApplication['leaveStartTime'])&& 
                                                    !empty($leaveApplication['leaveEndTime']);
                            $hasEditPermission = isset($_SESSION['currentUserModel']['permissions']) && in_array('edit', $_SESSION['currentUserModel']['permissions']);
                            
                            ?>

                            <?php if ($allAttributesPresent): ?>
                                <tr>
                                    <?php if ($hasEditPermission): ?>
                                        <td>
                                            <a href="index.php?controller=user&action=deleteLeaveApplication&id=<?php echo htmlspecialchars($leaveApplication['id']); ?>
                                                &leaveDate=<?php echo htmlspecialchars($leaveApplication['leaveDate']); ?>
                                                &leaveStartTime=<?php echo htmlspecialchars($leaveApplication['leaveStartTime']); ?>
                                                &leaveEndTime=<?php echo htmlspecialchars($leaveApplication['leaveEndTime']); ?>
                                            ">Delete</a>
                                        </td>
                                    <?php endif; ?>
                                        
                                    <!-- leave Application Data -->
                                    <td><?php echo htmlspecialchars($leaveApplication['id']); ?></td>
                                   
                                    <td><?php echo htmlspecialchars($leaveApplication['reason']); ?></td>
                                    <td>
                                        <?php if (!empty($leaveApplication['evidencePhoto'])): ?>
                                            <?php
                                            // Construct the full path to the image
                                            $imagePath = '/ZooManagementSystem/assests/UserImages/evidence_photos/' . htmlspecialchars(basename($leaveApplication['evidencePhoto']));
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

                                    <td><?php echo htmlspecialchars($leaveApplication['leaveDate']); ?></td>
                                    <td><?php echo htmlspecialchars($leaveApplication['leaveStartTime']); ?></td>
                                    <td><?php echo htmlspecialchars($leaveApplication['leaveEndTime']); ?></td>
                                    <td>
                                        <!-- Checkbox for 'approved' status -->
                                        <input type="checkbox" 
                                               <?php echo $leaveApplication['approved'] ? 'checked' : ''; ?>
                                               <?php echo $hasEditPermission ? '' : 'disabled'; ?> 
                                               onchange="updateLeaveApprovalStatus(
                                                    '<?php echo $leaveApplication['id']; ?>',
                                                    '<?php echo $leaveApplication['leaveDate']; ?>',
                                                    '<?php echo $leaveApplication['leaveStartTime']; ?>',
                                                    '<?php echo $leaveApplication['leaveEndTime']; ?>',
                                                    this.checked
                                               )"
                                        />
                                    </td>
                                    
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">No leave application applied.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
        
        <?php
        
        if (isset($_SESSION['currentUserModel']) && isset($_SESSION['currentUserModel']['role']['roleID']) && isset($_SESSION['currentUserModel']['permissions'])) {
            if ($_SESSION['currentUserModel']['role']['roleID'] == 1 && in_array('edit', $_SESSION['currentUserModel']['permissions'])) {
                echo '
                <a href="index.php?controller=user&action=addLeaveApplication"> + Create A Leave Application</a>';
            } 
        } ?>
         
        <script>
            function updateUrl() {
                var filterValue = document.getElementById('filter').value;
                var sortKey = encodeURIComponent(document.querySelector('input[name="sort"]').value);
                var searchQuery = encodeURIComponent(document.querySelector('input[name="search"]').value);

                // Build the new URL with the selected filter and current sort key
                var newUrl = `?controller=user&action=leaveApplicationManagement&sort=${sortKey}&filter=${filterValue}&search=${searchQuery}`;

                // Redirect to the new URL
                window.location.href = newUrl;
            }

            function updateSearchUrl() {
                var searchQuery = encodeURIComponent(document.getElementById('search').value.trim());
                var sortKey = encodeURIComponent(document.querySelector('input[name="sort"]').value);
                var filterKey = encodeURIComponent(document.querySelector('input[name="filter"]').value);

                // Build the new URL with the search query, filter, and sort key
                var newUrl = `?controller=user&action=leaveApplicationManagement&sort=${sortKey}&filter=${filterKey}&search=${searchQuery}`;

                // Redirect to the new URL
                window.location.href = newUrl;
            }
            
            // Function to redirect with the parameters
            function updateLeaveApprovalStatus(id, leaveDate, leaveStartTime, leaveEndTime, approved) {
                // Convert the approved value to boolean (1 for checked, 0 for unchecked)
                var approvedValue = approved ? 1 : 0;

                // Build the URL with the passed parameters
                var url = 'index.php?controller=user&action=updateLeaveApprovalStatus' +
                          '&id=' + encodeURIComponent(id) +
                          '&leaveDate=' + encodeURIComponent(leaveDate) +
                          '&leaveStartTime=' + encodeURIComponent(leaveStartTime) +
                          '&leaveEndTime=' + encodeURIComponent(leaveEndTime) +
                          '&approved=' + approvedValue;

                // Redirect to the URL
                window.location.href = url;
            }
        </script>

    </body>
</html>
