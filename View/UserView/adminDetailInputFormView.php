<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    //only allow the admin that have permission to edit
    $webConfig->restrictAccessForLoggedInEditPermissionAdmin();


    function getError($data, $key) {
        return isset($data[$key]) && $data[$key] !== '' ? htmlspecialchars($data[$key]) : '';
    }

    function displayErrorMessage($data, $key) {
        return isset($data[$key]) && $data[$key] !== '' ? '<div class="error-message">' . htmlspecialchars($data[$key]) . '</div>' : '';
    }
?>

<h1>Admin Details Editing Form</h1>
<form action="index.php?controller=user&action=submitAdminDetailsForm" method="POST">
    <style>
        .error{
            color:red;
        }
    </style>
    <table>
        <?php 
                //set the hidden data
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
        <!--set the selected user id and not allow user to input-->
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
                           //if it purpose is to edit, it should set the value of selected user
                       ?>"
                       <?php 
                           if (isset($data['selectedUser']) && isset($data['action'])) {
                               if ($data['action'] == 'edit' && isset($data['selectedUser']['username'])) {
                                   echo 'disabled'; 
                               }
                           }
                           //if it purpose is to edit, it should set the input to disabled
                       ?>>
            </td>
        </tr>
        
        
        <?php
            // Determine the default adminType
            $defaultAdminType = isset($data['selectedUser']['adminType']) ? $data['selectedUser']['adminType'] : 'ReadOnlyAdmin';
            // Handle the display of the adminType dropdown or hidden input
            if (isset($_SESSION['currentUserModel']) && isset($_SESSION['currentUserModel']['role']['roleID']) && isset($_SESSION['currentUserModel']['adminType']) && isset($_SESSION['currentUserModel']['permissions'])) {
                if ($_SESSION['currentUserModel']['role']['roleID'] == 1 && $_SESSION['currentUserModel']['adminType'] == 'SuperAdmin' && in_array('manage admin', $_SESSION['currentUserModel']['permissions'])) {
                    echo '
                    <tr>
                        <td>Admin Type:</td>
                        <td>
                            <select name="adminType" id="adminTypeSelect" onchange="updatePermissions()">
                                <option value="ReadOnlyAdmin"' . ($defaultAdminType == 'ReadOnlyAdmin' ? ' selected' : '') . '>Read Only Admin</option>
                                <option value="EditorAdmin"' . ($defaultAdminType == 'EditorAdmin' ? ' selected' : '') . '>Editor Admin</option>
                                <option value="SuperAdmin"' . ($defaultAdminType == 'SuperAdmin' ? ' selected' : '') . '>Super Admin</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Permissions:</td>
                        <td>
                            <input type="text" id="permissionsInput" disabled value="" />
                        </td>
                    </tr>';
                } else {
                    echo '<input type="hidden" name="adminType" value="' . $defaultAdminType . '">';
                }
            } else {
                // Default role for guests or non-authenticated users
                echo '<input type="hidden" name="adminType" value="ReadOnlyAdmin">';
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
                <button type="submit" name="submitAs" value="editAdminDetails">
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

function updatePermissions() {
    const adminType = document.getElementById('adminTypeSelect').value;
    let permissionsText = '';

    switch (adminType) {
        case 'ReadOnlyAdmin':
            permissionsText = 'read';
            break;
        case 'EditorAdmin':
            permissionsText = 'read, edit';
            break;
        case 'SuperAdmin':
            permissionsText = 'read, edit, manage admin';
            break;
        default:
            permissionsText = 'read';
    }

    // Update the input field with the corresponding permissions
    document.getElementById('permissionsInput').value = permissionsText;
}

// Set initial permissions on page load based on the default selected adminType
window.onload = updatePermissions;
</script>
<?php
echo displayErrorMessage($data, 'errorMessage');
?>
