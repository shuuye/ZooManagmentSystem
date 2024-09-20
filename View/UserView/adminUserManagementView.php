<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInAdmin();

    // Get the sort key and search query from query parameters
    $sortKey = isset($_GET['sort']) ? $_GET['sort'] : 'id';
    $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

    if (!empty($searchQuery)) {
        $filteredAdmin = array_filter($data['adminsArray'], function($admin) use ($searchQuery) {
            // Check if search query matches id, username, or adminType
            $basicMatch = stripos($admin['id'], $searchQuery) !== false ||
                          stripos($admin['username'], $searchQuery) !== false ||
                          stripos($admin['adminType'], $searchQuery) !== false;

            // Check if search query matches any of the permissions in the array
            $permissionsMatch = false;
            if (is_array($admin['permissions'])) {
                foreach ($admin['permissions'] as $permission) {
                    if (stripos($permission, $searchQuery) !== false) {
                        $permissionsMatch = true;
                        break; // Stop searching once a match is found
                    }
                }
            }

            // Return true if either basicMatch or permissionsMatch is true
            return $basicMatch || $permissionsMatch;
        });
    } else {
        $filteredAdmin = $data['adminsArray'];
    }

    // Sorting logic remains unchanged
    // Sorting logic for 'roleName'
    usort($filteredAdmin, function($a, $b) use ($sortKey) {
        // If sorting by 'permissions', convert the array to a string for comparison
        if ($sortKey === 'permissions') {
            // Convert permissions arrays into comma-separated strings
            $permissionsA = is_array($a['permissions']) ? implode(', ', $a['permissions']) : '';
            $permissionsB = is_array($b['permissions']) ? implode(', ', $b['permissions']) : '';

            return strcmp($permissionsA, $permissionsB);
        }

        // Otherwise, default sorting for other keys
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
        
        <h3>Admin Table</h3>
        
        <div>
            <form id="searchForm">
                <!-- Search Bar -->
                <label for="search">Search:</label>
                <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search..." oninput="updateSearchUrl()" />

                <!-- Hidden inputs for sort and filter -->
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sortKey); ?>">
            </form>
        </div>
        
        <div class="scrollable-container">
            <table id="adminTable" class="userManagementTable">
                <thead>
                    <tr>
                        <th colspan="3">Action</th>
                        <th><a href="?controller=user&action=adminUserManagement&sort=id&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">ID &#8597;</a></th>
                        <th><a href="?controller=user&action=adminUserManagement&sort=username&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Username &#8597;</a></th>
                        <th><a href="?controller=user&action=adminUserManagement&sort=adminType&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Admin Type &#8597;</a></th>
                        <th><a href="?controller=user&action=adminUserManagement&sort=permissions&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Permissions &#8597;</a></th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Determine if there are any admins with a valid id
                        $hasCompleteAdmins = !empty($filteredAdmin);
                        ?>

                        <?php if ($hasCompleteAdmins): ?>
                            <?php foreach ($filteredAdmin as $admin): ?>
                                <?php
                                $adminIDPresent = !empty($admin['id']);
                                // Check if the user has 'edit' permission
                                $hasEditPermission = isset($_SESSION['currentUserModel']['permissions']) && in_array('edit', $_SESSION['currentUserModel']['permissions']);
                                $hasManageAdminPermission = isset($_SESSION['currentUserModel']['permissions']) && in_array('manage admin', $_SESSION['currentUserModel']['permissions']);
                                ?>

                                <?php if ($adminIDPresent): ?>
                                    <tr>
                                        <!-- Determine colspan based on manage admin permission -->
                                        <td colspan="<?php echo $hasManageAdminPermission ? '1' : '3'; ?>">
                                            <a href="javascript:void(0)" onclick="showUserDetails(<?php echo htmlspecialchars($admin['id']); ?>)">Details</a>
                                        </td>

                                        <?php if ($hasManageAdminPermission): ?>
                                            <td>
                                                <a href="index.php?controller=user&action=editAdmin&id=<?php echo htmlspecialchars($admin['id']); ?>">Edit</a>
                                            </td>

                                            <td>
                                                <a href="index.php?controller=user&action=deleteUser&id=<?php echo htmlspecialchars($admin['id']); ?>">Delete</a>
                                            </td>
                                        <?php endif; ?>

                                        <!-- Admin User Data -->
                                        <td><?php echo htmlspecialchars($admin['id']); ?></td>
                                        <td><?php echo htmlspecialchars($admin['username']); ?></td>

                                        <!-- Admin Details Data -->
                                        <td><?php echo htmlspecialchars($admin['adminType']); ?></td>
                                        <td>
                                            <?php
                                            // Check if 'permissions' is an array and join its values with a comma
                                            if (isset($admin['permissions']) && is_array($admin['permissions'])) {
                                                echo htmlspecialchars(implode(', ', $admin['permissions']));
                                            } else {
                                                echo 'No permissions assigned';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7">No admin available.</td></tr>
                        <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- User Details Table -->
        <div class="userDetailsTable" id="userDetailsTable">
            <h3>User Details</h3>
            <table class="userManagementTable">
                <thead>
                    <tr>
                        <?php if ($hasManageAdminPermission): ?>
                            <th colspan="2">Action</th>
                        <?php endif; ?>
                            
                        <th>ID</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Full Name</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Registration Date</th>
                        <th>Last Login Date Time</th>
                        <th>Last Log Out Date Time</th>
                        <th>Role Name</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php if ($hasManageAdminPermission): ?>
                            <td>
                                <a href="index.php?controller=user&action=editUser&id=">Edit</a>
                            </td>
                            <td>
                                <a href="index.php?controller=user&action=deleteUser&id=">Delete</a>
                            </td>
                        <?php endif; ?>
                        <td id="id"></td>
                        <td id="username"></td>
                        <td id="password"></td>
                        <td id="full_name"></td>
                        <td id="phone_number"></td>
                        <td id="email"></td>
                        <td id="registrationDate"></td>
                        <td id="lastLoginDateTime"></td>
                        <td id="lastLogOutDateTime"></td>
                        <td id="roleName"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <?php
        if (isset($_SESSION['currentUserModel']) && isset($_SESSION['currentUserModel']['role']['roleID']) && isset($_SESSION['currentUserModel']['permissions'])) {
            if ($_SESSION['currentUserModel']['role']['roleID'] == 1 && in_array('edit', $_SESSION['currentUserModel']['permissions'])) {
                echo '
                <a href="index.php?controller=user&action=signUp"> + Create A New User</a>';
            } 
        } ?>

        <script>
            function updateSearchUrl() {
                var searchQuery = encodeURIComponent(document.getElementById('search').value.trim());
                var sortKey = encodeURIComponent(document.querySelector('input[name="sort"]').value);

                // Build the new URL with the search query, filter, and sort key
                var newUrl = `?controller=user&action=adminUserManagement&sort=${sortKey}&search=${searchQuery}`;

                // Redirect to the new URL
                window.location.href = newUrl;
            }
            
            // User Data from PHP
            const userArray = <?php echo json_encode($data['usersArray']); ?>; // Include the users array

            // Function to show user details
            function showUserDetails(userId) {
                // Find the user in the user array
                const userData = userArray.find(user => user.id == userId);

                if (userData) {
                    // Show user details table
                    document.getElementById('userDetailsTable').style.display = 'block';

                    // Directly access properties without getters
                    document.getElementById('id').textContent = userData.id;
                    document.getElementById('username').textContent = userData.username;
                    document.getElementById('password').textContent = userData.password.length > 25 ? userData.password.slice(0, 25) + '...' : userData.password;
                    document.getElementById('full_name').textContent = userData.full_name;
                    document.getElementById('phone_number').textContent = userData.phone_number;
                    document.getElementById('email').textContent = userData.email;
                    document.getElementById('registrationDate').textContent = userData.registrationDateTime;
                    document.getElementById('lastLoginDateTime').textContent = userData.lastLoginDateTime || 'Not Logged In Yet';
                    document.getElementById('lastLogOutDateTime').textContent = userData.lastLogOutDateTime || 'Not Logged In Yet';
                    document.getElementById('roleName').textContent = userData.role.roleName;
                    
                    // Update the Edit and Delete links with the userId
                    document.querySelector('#userDetailsTable a[href*="editUser"]').href = `index.php?controller=user&action=editUser&id=${userId}`;
                    document.querySelector('#userDetailsTable a[href*="deleteUser"]').href = `index.php?controller=user&action=deleteUser&id=${userId}`;
                } else {
                    // Hide user details table if no user found
                    document.getElementById('userDetailsTable').style.display = 'none';
                }
            }

        </script>

    </body>
</html>

