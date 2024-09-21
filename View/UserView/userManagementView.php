<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInAdmin();//only allow the logged in admin to access

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Get the sort key and search query from query parameters
    $sortKey = isset($_GET['sort']) ? $_GET['sort'] : 'id';
    $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';


    if(!empty($searchQuery)){
        $filteredUsers = array_filter($data['usersArray'], function($user) use ($searchQuery) {
            return stripos($user['id'], $searchQuery) !== false ||
                   stripos($user['username'], $searchQuery) !== false ||
                   stripos($user['password'], $searchQuery) !== false ||
                   stripos($user['full_name'], $searchQuery) !== false ||
                   stripos($user['phone_number'], $searchQuery) !== false ||
                   stripos($user['email'], $searchQuery) !== false ||
                   stripos($user['registrationDateTime'], $searchQuery) !== false ||
                   stripos($user['lastLoginDateTime'], $searchQuery) !== false ||
                   stripos($user['lastLogOutDateTime'], $searchQuery) !== false ||
                   stripos($user['role']['roleName'], $searchQuery) !== false;
        });
    }else{
        $filteredUsers = $data['usersArray'];
    }

    // Sorting logic remains unchanged
    // Sorting logic for 'roleName'
    usort($filteredUsers, function($a, $b) use ($sortKey) {
        // If sorting by 'roleName', we access the nested 'roleName' inside 'role'
        if ($sortKey === 'roleName') {
            return strcmp($a['role']['roleName'], $b['role']['roleName']);
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
        
        <h3>User Table</h3>
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
            <table id="userTable" class="userManagementTable">
                <thead>
                    <tr>
                        <th colspan="3">Action</th>
                        <th><a href="?controller=user&action=userManagement&sort=id&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">ID &#8597;</a></th>
                        <th><a href="?controller=user&action=userManagement&sort=username&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Username &#8597;</a></th>
                        <th><a href="?controller=user&action=userManagement&sort=password&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Password &#8597;</a></th>
                        <th><a href="?controller=user&action=userManagement&sort=full_name&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Full Name &#8597;</a></th>
                        <th><a href="?controller=user&action=userManagement&sort=phone_number&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Phone Number &#8597;</a></th>
                        <th><a href="?controller=user&action=userManagement&sort=email&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Email &#8597;</a></th>
                        <th><a href="?controller=user&action=userManagement&sort=registrationDateTime&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Registration Date &#8597;</a></th>
                        <th><a href="?controller=user&action=userManagement&sort=lastLoginDateTime&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Last Login Date Time &#8597;</a></th>
                        <th><a href="?controller=user&action=userManagement&sort=lastLogOutDateTime&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Last Log Out Date Time &#8597;</a></th>
                        <th><a href="?controller=user&action=userManagement&sort=roleName&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Role Name &#8597;</a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $hasCompleteUser = !empty($filteredUsers);
                        ?>
                        <?php if ($hasCompleteUser): ?>
                            <?php foreach ($filteredUsers as $user): ?>
                                <?php
                                $userIDPresent = !empty($user['id']);

                                // Check if the user has 'edit' permission
                                $hasEditPermission = isset($_SESSION['currentUserModel']['permissions']) && in_array('edit', $_SESSION['currentUserModel']['permissions']);
                                $hasManageAdminPermission = isset($_SESSION['currentUserModel']['permissions']) && in_array('manage admin', $_SESSION['currentUserModel']['permissions']);
                                ?>

                                <?php if ($userIDPresent): ?>
                                    <tr>
                                        <!-- Determine colspan based on role and permissions -->
                                        <td colspan="<?php 
                                            if ($user['role']['roleID'] == 1) {
                                                echo $hasManageAdminPermission ? '1' : '3'; 
                                            } else {
                                                echo $hasEditPermission ? '1' : '3';
                                            }
                                        ?>">
                                            <a href="javascript:void(0)" onclick="showUserDetails(<?php echo htmlspecialchars($user['id']); ?>, '<?php echo htmlspecialchars($user['role']['roleName']); ?>')">Details</a>
                                        </td>

                                        <?php if ($user['role']['roleID'] == 1 && $hasManageAdminPermission): ?>
                                            <td>
                                                <a href="index.php?controller=user&action=editUser&id=<?php echo htmlspecialchars($user['id']); ?>">Edit</a>
                                            </td>

                                            <td>
                                                <a href="index.php?controller=user&action=deleteUser&id=<?php echo htmlspecialchars($user['id']); ?>">Delete</a>
                                            </td>
                                        <?php elseif ($hasEditPermission): ?>
                                            <?php if ($user['role']['roleID'] != 1): ?>
                                                <td>
                                                    <a href="index.php?controller=user&action=editUser&id=<?php echo htmlspecialchars($user['id']); ?>">Edit</a>
                                                </td>
                                                <td>
                                                    <a href="index.php?controller=user&action=deleteUser&id=<?php echo htmlspecialchars($user['id']); ?>">Delete</a>
                                                </td>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td><?php
                                            $password = $user['password'];
                                            echo htmlspecialchars(strlen($password) > 25 ? substr($password, 0, 25) . '...' : $password);
                                            ?></td>
                                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['registrationDateTime']); ?></td>
                                        <td><?php echo !empty($user['lastLoginDateTime']) ? htmlspecialchars($user['lastLoginDateTime']) : 'Not Logged In Yet'; ?></td>
                                        <td><?php echo !empty($user['lastLogOutDateTime']) ? htmlspecialchars($user['lastLogOutDateTime']) : 'Not Logged Out Yet'; ?></td>
                                        <td><?php echo htmlspecialchars($user['role']['roleName']); ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="13">No user available.</td></tr>
                        <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Admin Details Table -->
        <div class="adminDetailsTable" id="adminDetailsTable">
            <h3>Admin Details</h3>
            <table class="userManagementTable">
                <thead>
                    <tr>
                        <?php if ($hasManageAdminPermission): ?>
                            <th colspan="2">Action</th>
                        <?php endif; ?>
                        <th>ID</th>
                        <th>Admin Type</th>
                        <th>Permissions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php if ($hasManageAdminPermission): ?>
                            <td>
                                <a href="index.php?controller=user&action=editAdmin&id=">Edit</a> <!-- href will be updated dynamically -->
                            </td>
                            <td>
                                <a href="index.php?controller=user&action=deleteUser&id=">Delete</a> <!-- href will be updated dynamically -->
                            </td>
                        <?php endif; ?>
                        <td id="adminId"></td>
                        <td id="adminType"></td>
                        <td id="adminPermissions"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Customer Details Table -->
        <div class="customerDetailsTable" id="customerDetailsTable">
            <h3>Customer Details</h3>
            <table class="userManagementTable">
                <thead>
                    <tr>
                        <?php if ($hasEditPermission): ?>
                            <th colspan="2">Action</th>
                        <?php endif; ?>
                        <th>ID</th>
                        <th>Membership ID</th>
                        <th>Membership Type</th>
                        <th>Fee</th>
                        <th>Discount Offered (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php if ($hasEditPermission): ?>
                            <td>
                                <a href="index.php?controller=user&action=editCustomer&id=">Edit</a> <!-- href will be updated dynamically -->
                            </td>
                            <td>
                                <a href="index.php?controller=user&action=deleteUser&id=">Delete</a> <!-- href will be updated dynamically -->
                            </td>
                        <?php endif; ?>
                            
                        <td id="customerId"></td>
                        <td id="membershipId"></td>
                        <td id="membershipType"></td>
                        <td id="membershipFee"></td>
                        <td id="membershipDiscount"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Staff Details Table -->
        <div class="staffDetailsTable" id="staffDetailsTable">
            <h3>Staff Details</h3>
            <table class="userManagementTable">
                <thead>
                    <tr>
                        <?php if ($hasEditPermission): ?>
                            <th colspan="2">Action</th>
                        <?php endif; ?>
                        <th>ID</th>
                        <th>Position</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php if ($hasEditPermission): ?>
                            <td>
                                <a href="index.php?controller=user&action=editStaff&id=">Edit</a> <!-- href will be updated dynamically -->
                            </td>
                            <td>
                                <a href="index.php?controller=user&action=deleteUser&id=">Delete</a> <!-- href will be updated dynamically -->
                            </td>
                        <?php endif; ?>
                            
                        <td id="staffId"></td>
                        <td id="position"></td>
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
                var newUrl = `?controller=user&action=userManagement&sort=${sortKey}&search=${searchQuery}`;

                // Redirect to the new URL
                window.location.href = newUrl;
            }
            
            // Admin and Customer Data from PHP
            const adminsArray = <?php echo json_encode($data['adminsArray']); ?>;
            const customersArray = <?php echo json_encode($data['customersArray']); ?>;
            const staffsArray = <?php echo json_encode($data['staffsArray']); ?>;

            // Function to show user details based on role
            function showUserDetails(userId, roleName) {
                document.getElementById('adminDetailsTable').style.display = 'none';  // Ensure admin table is hidden
                document.getElementById('customerDetailsTable').style.display = 'none';  // Ensure customer table is hidden
                document.getElementById('staffDetailsTable').style.display = 'none';  // Ensure staff table is hidden

                if (roleName === 'admin') {
                    // Show admin details if role is admin
                    showAdminDetails(userId);
                } else if (roleName === 'customer') {
                    // Show customer details if role is customer
                    showCustomerDetails(userId);
                }
                else if (roleName === 'staff') {
                    // Show customer details if role is customer
                    showStaffDetails(userId);
                }
            }

            // Function to show admin details
            function showAdminDetails(userId) {
                const adminData = adminsArray.find(admin => admin.id == userId);

                if (adminData) {
                    // Display the admin details table
                    document.getElementById('adminDetailsTable').style.display = 'block';

                    // Populate the admin details
                    document.getElementById('adminId').textContent = adminData.id;
                    document.getElementById('adminType').textContent = adminData.adminType;
                    document.getElementById('adminPermissions').textContent = adminData.permissions.join(', ');

                    // Update the Edit and Delete links with the userId
                    document.querySelector('#adminDetailsTable a[href*="editAdmin"]').href = `index.php?controller=user&action=editAdmin&id=${userId}`;
                    document.querySelector('#adminDetailsTable a[href*="deleteUser"]').href = `index.php?controller=user&action=deleteUser&id=${userId}`;
                }
            }

            // Function to show customer details
            function showCustomerDetails(userId) {
                const customerData = customersArray.find(customer => customer.id == userId);

                if (customerData) {
                    // Display the customer details table
                    document.getElementById('customerDetailsTable').style.display = 'block';

                    // Populate the customer details
                    document.getElementById('customerId').textContent = customerData.id;
                    document.getElementById('membershipId').textContent = customerData.membership.membershipID;
                    document.getElementById('membershipType').textContent = customerData.membership.membershipType;
                    document.getElementById('membershipFee').textContent = customerData.membership.fee;
                    document.getElementById('membershipDiscount').textContent = customerData.membership.discountOffered;

                    // Update the Edit and Delete links with the userId
                    document.querySelector('#customerDetailsTable a[href*="editCustomer"]').href = `index.php?controller=user&action=editCustomer&id=${userId}`;
                    document.querySelector('#customerDetailsTable a[href*="deleteUser"]').href = `index.php?controller=user&action=deleteUser&id=${userId}`;
                }
            }
            
            // Function to show staff details
            function showStaffDetails(userId) {
                const staffData = staffsArray.find(staff => staff.id == userId);

                if (staffData) {
                    // Display the admin details table
                    document.getElementById('staffDetailsTable').style.display = 'block';

                    // Populate the admin details
                    document.getElementById('staffId').textContent = staffData.id;
                    document.getElementById('position').textContent = staffData.position;

                    // Update the Edit and Delete links with the userId
                    document.querySelector('#staffDetailsTable a[href*="editStaff"]').href = `index.php?controller=user&action=editStaff&id=${userId}`;
                    document.querySelector('#staffDetailsTable a[href*="deleteUser"]').href = `index.php?controller=user&action=deleteUser&id=${userId}`;
                }
            }

        </script>

    </body>
</html>
