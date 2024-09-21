<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForLoggedInEditPermissionAdmin();//only allow the admin that have permission to edit

    function getError($data, $key) {
        return isset($data[$key]) && $data[$key] !== '' ? htmlspecialchars($data[$key]) : '';
    }

    function displayErrorMessage($data, $key) {
        return isset($data[$key]) && $data[$key] !== '' ? '<div class="error-message">' . htmlspecialchars($data[$key]) . '</div>' : '';
    }

    function getMembershipOptions($memberships, $selectedMembershipID) {
        $options = '';
        foreach ($memberships as $membership) {
            $isSelected = ($membership["membershipID"] == $selectedMembershipID) ? "selected" : "";
            $options .= '<option value="' . htmlspecialchars($membership["membershipID"]) . '" ' . $isSelected . '>' . htmlspecialchars($membership["membershipType"]) . '</option>';
        }
        return $options;
    }
    // Include required files
    require_once __DIR__ . '/../../Model/User/MembershipModel.php';

    // Get membership
    $membershipModel = new MembershipModel();
    $memberships = $membershipModel->getAllMembership();

?>
<h1>Customer Details Editing Form</h1>

<form action="index.php?controller=user&action=submitCustomerDetailsForm" method="POST">
    <style>
        .error{
            color:red;
        }
    </style>
    <table>
        <?php 

                if (isset($data['selectedUser']) && isset($data['action'])) {
                    if ($data['action'] == 'edit' && isset($data['selectedUser']['id'])) {
                        // Correctly output the hidden input field
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($data['selectedUser']['id']) . '">';
                    }
                    if ($data['action'] == 'edit' && isset($data['selectedUser']['username'])) {
                        // Correctly output the hidden input field
                        echo '<input type="hidden" name="username" value="' . htmlspecialchars($data['selectedUser']['username']) . '">';
                    }
                }
                ?>
        <?php if ((isset($data['action']) && isset($data['selectedUser'])) || (isset($data['action']) && $data['action'] == 'edit')): ?>
                <tr>
                    <td><label for="id">Username ID:</label></td>
                    <td>
                        <input type="text" id="id" name="id" 
                               autocomplete="off" 
                               required 
                               value="<?php echo htmlspecialchars($data['selectedUser']['id']) ?>"
                               disabled
                               >
                    </td>
                </tr>
        <?php endif; ?>
        
        
        <tr>
            <td><label for="username">Username:</label></td>
            <td>
                <input type="text" id="username" name="username" 
                       autocomplete="off" 
                       required 
                       value="<?php 
                           if (isset($data['selectedUser']) && isset($data['action'])) {
                               if ($data['action'] == 'edit' && isset($data['selectedUser']['username'])) {
                                   echo $data['selectedUser']['username']; 
                               }
                           }
                       ?>"
                       <?php 
                           if (isset($data['selectedUser']) && isset($data['action'])) {
                               if ($data['action'] == 'edit' && isset($data['selectedUser']['username'])) {
                                   echo 'disabled'; 
                               }
                           }
                       ?>>
            </td>
        </tr>
        
        <?php
            // Handle the display of the membership dropdown or hidden input
            if (isset($_SESSION['currentUserModel']) && isset($_SESSION['currentUserModel']['role']['roleID']) && isset($_SESSION['currentUserModel']['adminType']) && isset($_SESSION['currentUserModel']['permissions'])) {
                if ($_SESSION['currentUserModel']['role']['roleID'] == 1 && in_array('edit', $_SESSION['currentUserModel']['permissions'])) {
                    $selectedMembershipID = isset($data['selectedUser']['membership']['membershipID']) ? $data['selectedUser']['membership']['membershipID'] : 2;
                    $defaultFeeValue = '';
                    if (isset($selectedMembershipID) && isset($data['action']) && $data['action'] == 'edit') {
                        $defaultFeeValue = htmlspecialchars($data['selectedUser']['membership']['fee']); // Set the fee value if conditions are met
                    }
                    $defaultDiscountOffered = '';
                    if (isset($selectedMembershipID) && isset($data['action']) && $data['action'] == 'edit') {
                        $defaultDiscountOffered = htmlspecialchars($data['selectedUser']['membership']['discountOffered']); // Set the fee value if conditions are met
                    }
                    echo '
                    <tr>
                        <td>Membership:</td>
                        <td>
                            <select name="membershipID" id="membershipIDSelect" onchange="updateMembershipDetails()">
                                ' . getMembershipOptions($memberships, $selectedMembershipID) . '
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Fee:</td>
                        <td>
                            <input type="text" id="feeInput" disabled value="' . $defaultFeeValue . '" />
                        </td>
                    </tr>
                    <tr>
                        <td>Discount Offered (%):</td>
                        <td>
                            <input type="text" id="discountOfferedInput" disabled value=" ' . $defaultDiscountOffered . '" />
                        </td>
                    </tr>';
                } else {
                    $selectedMembershipID = isset($data['selectedUser']['membership']['membershipID']) ? $data['selectedUser']['membership']['membershipID'] : 2;
                    echo '<input type="hidden" name="membershipID" value="' . $selectedMembershipID . '">';
                }
            } else {
                // Default role for guests or non-authenticated users
                echo '<input type="hidden" name="membershipID" value="1">';
            }
        ?>
        <tr>
            <td class="error"><?php if (isset($data['userInputData'])): ?>
                <?php echo getError($data['userInputData'], 'inputFormErr'); ?>
            <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>
                <button type="submit" name="submitAs" value="editCustomerDetails">
                    Edit
                </button>
                
            </td>
            <td>
                <a href="<?php if (isset($_SESSION['currentUserModel']['role']['roleID']) && isset($data['action'])) {
                                    if ($_SESSION['currentUserModel']['role']['roleID'] == 1 && $data['action'] == 'edit') {
                                        echo 'index.php?controller=user&action=userManagementMainPanel';
                                    }else{
                                        echo 'index.php';
                                    }
                                } else{
                                    echo 'index.php';
                                }
                        ?>" id="cancelLink"><button type="button">
                    Cancel
                </button></a>
            </td>
        </tr>
    </table>
</form>
<script>
// Function to update the permissions input field based on the selected admin type
const memberships = <?php echo json_encode($memberships); ?>;

// Function to update fee and discount based on selected membership
function updateMembershipDetails() {
    const membershipID = document.getElementById('membershipIDSelect').value;
    
    // Find the selected membership in the array
    const selectedMembership = memberships.find(membership => membership.membershipID == membershipID);
    
    // Update the fee and discount fields
    if (selectedMembership) {
        document.getElementById('feeInput').value = selectedMembership.fee;
        document.getElementById('discountOfferedInput').value = selectedMembership.discountOffered;
    }
}

// Set initial permissions on page load based on the default selected adminType
window.onload = updatePermissions;
</script>
<?php
echo displayErrorMessage($data, 'errorMessage');
?>
