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
        $filteredCustomer = array_filter($data['customersArray'], function($customer) use ($searchQuery) {
            // Check if search query matches id, username, or adminType
            return stripos($customer['id'], $searchQuery) !== false ||
                    stripos($customer['username'], $searchQuery) !== false ||
                    stripos($customer['membership']['membershipID'], $searchQuery) !== false ||
                    stripos($customer['membership']['membershipType'], $searchQuery) !== false ||
                    stripos($customer['membership']['fee'], $searchQuery) !== false ||
                    stripos($customer['membership']['discountOffered'], $searchQuery) !== false;

        });
    } else {
        $filteredCustomer = $data['customersArray'];
    }

    // Sorting logic remains unchanged
    usort($filteredCustomer, function($a, $b) use ($sortKey) {
        switch ($sortKey) {
            // Sorting by membershipID
            case 'membershipID':
                return strcmp($a['membership']['membershipID'], $b['membership']['membershipID']);

            // Sorting by membershipType
            case 'membershipType':
                return strcmp($a['membership']['membershipType'], $b['membership']['membershipType']);

            // Sorting by fee
            case 'fee':
                return $a['membership']['fee'] <=> $b['membership']['fee'];

            // Sorting by discountOffered
            case 'discountOffered':
                return $a['membership']['discountOffered'] <=> $b['membership']['discountOffered'];

            default:
                // Default sorting for other keys outside the 'membership' array
                return strcmp($a[$sortKey], $b[$sortKey]);
        }
    });

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            .full-clickable {
                display: block;
                width: 100%;
                height: 100%;
                text-align: center;
                text-decoration: none;
                color: inherit;
            }

            th a.full-clickable:hover {
                background-color: darkgray; /* Optional: Add hover effect */
            }
        </style>
    </head>
    <body>
        
        <h3>Customer Table</h3>
        
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
            <table id="customerTable" class="userManagementTable">
                <thead>
                    <tr>
                        <th colspan="3">Action</th>
                        <th><a href="?controller=user&action=customerUserManagement&sort=id&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">ID &#8597;</a></th>
                        <th><a href="?controller=user&action=customerUserManagement&sort=username&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Username &#8597;</a></th>
                        <th><a href="?controller=user&action=customerUserManagement&sort=membershipID&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Membership ID &#8597;</a></th>
                        <th><a href="?controller=user&action=customerUserManagement&sort=membershipType&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Membership Type &#8597;</a></th>
                        <th><a href="?controller=user&action=customerUserManagement&sort=fee&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Fees &#8597;</a></th>
                        <th><a href="?controller=user&action=customerUserManagement&sort=discountOffered&search=<?php echo htmlspecialchars($searchQuery); ?>" class="full-clickable">Discount Offered (%) &#8597;</a></th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Determine if there are any customers with a valid id
                        $hasCompleteCustomers = !empty($filteredCustomer);
                        
                        ?>

                        <?php if ($hasCompleteCustomers): ?>
                            <?php foreach ($filteredCustomer as $customer): ?>
                                <tr>
                                    <?php
                                    $customerIDPresent = !empty($customer['id']);
                                    // Check if the user has 'edit' permission
                                    $hasEditPermission = isset($_SESSION['currentUserModel']['permissions']) && in_array('edit', $_SESSION['currentUserModel']['permissions']);
                                    ?>

                                    <?php if ($customerIDPresent): ?>
                                        <!-- Determine colspan based on edit permission -->
                                        <td colspan="<?php echo $hasEditPermission ? '1' : '3'; ?>">
                                            <a href="javascript:void(0)" onclick="showUserDetails(<?php echo htmlspecialchars($customer['id']); ?>)">Details</a>
                                        </td>

                                        <?php if ($hasEditPermission): ?>
                                            <td>
                                                <a href="index.php?controller=user&action=editCustomer&id=<?php echo htmlspecialchars($customer['id']); ?>">Edit</a>
                                            </td>

                                            <td>
                                                <a href="index.php?controller=user&action=deleteUser&id=<?php echo htmlspecialchars($customer['id']); ?>">Delete</a>
                                            </td>
                                        <?php endif; ?>

                                        <!-- Customer Data -->
                                        <td><?php echo htmlspecialchars($customer['id']); ?></td>
                                        <td><?php echo htmlspecialchars($customer['username']); ?></td>

                                        <!-- Membership Data -->
                                        <td><?php echo htmlspecialchars($customer['membership']['membershipID']); ?></td>
                                        <td><?php echo htmlspecialchars($customer['membership']['membershipType']); ?></td>
                                        <td><?php echo htmlspecialchars(number_format($customer['membership']['fee'], 2)); ?></td>
                                        <td><?php echo htmlspecialchars(number_format($customer['membership']['discountOffered'], 2)); ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="9">No customer available.</td></tr>
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
                        <?php if ($hasEditPermission): ?>
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
                        <?php if ($hasEditPermission): ?>
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
                var newUrl = `?controller=user&action=customerUserManagement&sort=${sortKey}&search=${searchQuery}`;

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

