<?php 

    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInUser();
    
    $resetSuccess = isset($_SESSION['resetSuccess']) ? $_SESSION['resetSuccess'] : '';

    function getError($data, $key) {
        return isset($data[$key]) && $data[$key] !== '' ? htmlspecialchars($data[$key]) : '';
    }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if(isset($_SESSION['currentUserModel']) && $_SESSION['currentUserModel']['role']['roleID'] == 2){
        include 'View/clientTopNavHeader.php';
    }elseif(isset($_SESSION['currentUserModel']) && $_SESSION['currentUserModel']['role']['roleID'] == 1){
        include 'View/adminTopNavHeader.php';
    }elseif(isset($_SESSION['currentUserModel']) && $_SESSION['currentUserModel']['role']['roleID'] == 3){
        include 'View/UserView/staffUserTopNav.php';
    }
        
?>
<style>
    .error{
        color:red;
    }
    </style>
<h1>User Profile</h1>

<?php
// Assuming $_SESSION['currentUserModel'] contains the user data
if (isset($_SESSION['currentUserModel'])) {
    $userData = $_SESSION['currentUserModel'];
    
    // Mask the password with 8 asterisks
    $maskedPassword = str_repeat('*', 8);
    ?>
    
    <table style="text-align: left;">
        <tr>
            <th>Username:</th>
            <td><?php echo htmlspecialchars($userData['username']); ?></td>
        </tr>
        <tr>
            <th>Password:</th>
            <td><?php echo $maskedPassword; ?></td>
            <td><a href="#" onclick="toggleRow('passwordChangeForm')">reset</a></td> <!-- Modify onclick to toggle password form -->
        </tr>
        <?php 
            if(isset($_SESSION['userInputData']['inputFormErr'])){
                echo '<tr>
                        <th></th>
                        <td style="color:red;">' . htmlspecialchars($_SESSION['userInputData']['inputFormErr']) . '</td>
                        <td></td>
                    </tr>';
            }elseif(isset($_SESSION['resetSuccess'])){
                echo '<tr>
                        <th></th>
                        <td style="color:green;">' . htmlspecialchars($_SESSION['resetSuccess']) . '</td>
                        <td></td>
                    </tr>';
                unset($_SESSION['resetSuccess']);
            }
        ?>
        <tr>
            <th>Full Name:</th>
            <td><?php echo htmlspecialchars($userData['full_name']); ?></td>
            <td><a href="#" onclick="toggleRow('full_nameChangeForm')">change</a></td>
        </tr>
        <?php 
            if(isset($_SESSION['userInputData']['inputFullNameErr'])){
                echo '<tr>
                        <th></th>
                        <td style="color:red;">' . htmlspecialchars($_SESSION['userInputData']['inputFullNameErr']) . '</td>
                        <td></td>
                    </tr>';
            }elseif(isset($_SESSION['resetFullNameSuccess'])){
                echo '<tr>
                        <th></th>
                        <td style="color:green;">' . htmlspecialchars($_SESSION['resetFullNameSuccess']) . '</td>
                        <td></td>
                    </tr>';
                unset($_SESSION['resetFullNameSuccess']);
            }
        ?>
        <tr>
            <th>Phone Number:</th>
            <td><?php echo htmlspecialchars($userData['phone_number']); ?></td>
            <td><a href="#" onclick="toggleRow('phone_numberChangeForm')">change</a></td>
        </tr>
        <?php 
            if(isset($_SESSION['userInputData']['inputPhoneNumberErr'])){
                echo '<tr>
                        <th></th>
                        <td style="color:red;">' . htmlspecialchars($_SESSION['userInputData']['inputPhoneNumberErr']) . '</td>
                        <td></td>
                    </tr>';
            }elseif(isset($_SESSION['resetPhoneNumberSuccess'])){
                echo '<tr>
                        <th></th>
                        <td style="color:green;">' . htmlspecialchars($_SESSION['resetPhoneNumberSuccess']) . '</td>
                        <td></td>
                    </tr>';
                unset($_SESSION['resetPhoneNumberSuccess']);
            }
        ?>
        <tr>
            <th>Email:</th>
            <td><?php echo htmlspecialchars($userData['email']); ?></td>
        </tr>
        <?php
            if ($_SESSION['currentUserModel']['role']['roleID'] == 1) :
                // Fetch the admin type and permissions
                $adminType = htmlspecialchars($_SESSION['currentUserModel']['adminType']);
                $permissions = $_SESSION['currentUserModel']['permissions']; // Assuming this is an array

                // Convert permissions array to a comma-separated string
                $permissionsString = htmlspecialchars(implode(', ', $permissions));

                echo '<tr>
                        <th>Admin Type:</th>
                        <td>' . $adminType . '</td>
                      </tr>
                      <tr>
                        <th>Permission:</th>
                        <td>' . $permissionsString . '</td>
                      </tr>';
            elseif ($_SESSION['currentUserModel']['role']['roleID'] == 2) :
                $membershipType = htmlspecialchars($_SESSION['currentUserModel']['membership']['membershipType']);
                $membershipDiscountOffered = htmlspecialchars($_SESSION['currentUserModel']['membership']['discountOffered']);
                echo '<tr>
                        <th>Membership Type:</th>
                        <td>' . $membershipType . '</td>
                      </tr>
                      <tr>
                        <th>Discount Offered:</th>
                        <td>' . $membershipDiscountOffered . '%</td>
                      </tr>';
            elseif ($_SESSION['currentUserModel']['role']['roleID'] == 3) :
                $position = htmlspecialchars($_SESSION['currentUserModel']['position']);
                echo '<tr>
                        <th>Position:</th>
                        <td>' . $position . '</td>
                      </tr>
                      ';
            else :
                echo "Unknown Role";
            endif;
        ?>

    </table>

    <!-- Password Change Form -->
    <div class="passwordChangeForm" id="passwordChangeForm" style="display: <?php if(isset($_SESSION['userInputData']['inputFormErr'])){
                                                                                        echo htmlspecialchars('block');
                                                                                    }else{
                                                                                        echo htmlspecialchars('none');
                                                                                    }
                                                                            ?>; border: 1px solid black;"> <!-- Initially hidden -->
                                                                                    
        <h3>Password Changing Form</h3>
        <form action="index.php?controller=user&action=submitResetPasswordForm" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($userData['id']); ?>"/>
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>"/>
            <table class="userManagementTable" style="text-align: left;">
                <tbody>
                    <tr>
                        <th>Current Password:</th>
                        <td><input type="password" name="currentPassword" id="currentPassword" /></td>
                        <td class="error"><?php if (isset($_SESSION['userInputData'])): ?>
                            <?php echo getError($_SESSION['userInputData'], 'currentPasswordErr'); ?>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <th>New Password:</th>
                        <td><input type="password" name="newPassword" id="newPassword" /></td>
                        <td class="error"><?php if (isset($_SESSION['userInputData'])): ?>
                            <?php echo getError($_SESSION['userInputData'], 'newPasswordErr'); ?>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <th>Confirm Password:</th>
                        <td><input type="password" name="confirmPassword" id="confirmPassword" /></td>
                        <td class="error"><?php if (isset($_SESSION['userInputData'])): ?>
                            <?php echo getError($_SESSION['userInputData'], 'confirmPasswordErr'); ?>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <td><button type="submit">Submit</button></td>
                        <td><button type="button" onclick="clearAndHideForm('passwordChangeForm')">Cancel</button></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    
    <!-- Full Name Change Form -->
    <div class="full_nameChangeForm" id="full_nameChangeForm" style="display: <?php if(isset($_SESSION['userInputData']['inputFullNameErr'])){
                                                                                        echo htmlspecialchars('block');
                                                                                    }else{
                                                                                        echo htmlspecialchars('none');
                                                                                    }
                                                                            ?>; border: 1px solid black;"> <!-- Initially hidden -->
        <h3>Full Name Changing Form</h3>
        <form action="index.php?controller=user&action=submitResetFullNameForm" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($userData['id']); ?>"/>
            
            <table class="userManagementTable" style="text-align: left;">
                <tbody>
                    <tr>
                        <th>Current Full Name:</th>
                        <td><?php echo htmlspecialchars($userData['full_name']); ?></td>
                    </tr>
                    <tr>
                        <th>New Full Name:</th>
                        <td><input type="text" id="newFullName" name="newFullName"/></td>
                        <td class="error"><?php if (isset($_SESSION['userInputData'])): ?>
                            <?php echo getError($_SESSION['userInputData'], 'newFullNameErr'); ?>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <td><button>Submit</button></td>
                        <td><button type="button" onclick="clearAndHideForm('full_nameChangeForm')">Cancel</button></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    
    <!-- Phone Number Change Form -->
    <div class="phone_numberChangeForm" id="phone_numberChangeForm" style="display: <?php if(isset($_SESSION['userInputData']['inputPhoneNumberErr'])){
                                                                                        echo htmlspecialchars('block');
                                                                                    }else{
                                                                                        echo htmlspecialchars('none');
                                                                                    }
                                                                            ?>; border: 1px solid black;"> <!-- Initially hidden -->
        <h3>Phone Number Changing Form</h3>
        <form action="index.php?controller=user&action=submitResetPhoneNumberForm" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($userData['id']); ?>"/>
            <table class="userManagementTable" style="text-align: left;">
                <tbody>
                    <tr>
                        <th>Current Phone Number:</th>
                        <td><?php echo htmlspecialchars($userData['phone_number']); ?></td>
                    </tr>
                    <tr>
                        <th>New Phone Number:</th>
                        <td><input type="text" id="newPhoneNumber" name="newPhoneNumber"/></td>
                        <td class="error"><?php if (isset($_SESSION['userInputData'])): ?>
                            <?php echo getError($_SESSION['userInputData'], 'newPhoneNumberErr'); ?>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <td><button>Submit</button></td>
                        <td><button type="button" onclick="clearAndHideForm('phone_numberChangeForm')">Cancel</button></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    <?php unset($_SESSION['userInputData']); ?>
    <script>
        // Function to toggle the visibility of elements (both rows and divs)
        function toggleRow(rowId) {
            // Hide all forms first
            var forms = ['passwordChangeForm', 'full_nameChangeForm', 'phone_numberChangeForm'];
            forms.forEach(function(formId) {
                document.getElementById(formId).style.display = 'none';
            });

            // Show the selected form
            var row = document.getElementById(rowId);
            if (row.style.display === "none" || row.style.display === "") {
                row.style.display = "block"; // Use "block" for divs
            }
        }

        // Function to clear inputs and hide the form
        function clearAndHideForm(formId) {
            var form = document.getElementById(formId);
            var inputs = form.getElementsByTagName('input');
            
            // Clear all input fields
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].value = '';
            }

            // Hide the form
            form.style.display = 'none';
        }
    </script>
    
    <?php
} else {
    echo "No user data available.";
}
?>
