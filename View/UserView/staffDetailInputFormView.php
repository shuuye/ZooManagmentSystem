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
?>

<h1>Staff Details Editing Form</h1>
<form action="index.php?controller=user&action=submitStaffDetailsForm" method="POST">
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
            // Determine the default position
            $defaultStaffPostition = isset($data['selectedUser']['position']) ? $data['selectedUser']['position'] : 'General Staff';
            // Handle the display of the position dropdown or hidden input
            if (isset($_SESSION['currentUserModel']) && isset($_SESSION['currentUserModel']['role']['roleID']) && isset($_SESSION['currentUserModel']['adminType']) && isset($_SESSION['currentUserModel']['permissions'])) {
                if ($_SESSION['currentUserModel']['role']['roleID'] == 1 && in_array('edit', $_SESSION['currentUserModel']['permissions'])) {
                    echo '
                    <tr>
                        <td>Position:</td>
                        <td>
                            <input type="text" name="position" value="' . $defaultStaffPostition . '" />
                        </td>
                    </tr>';
                } else {
                    echo '<input type="hidden" name="position" value="' . $defaultStaffPostition . '" />';
                }
            } else {
                // Default position for guests or non-authenticated users
                echo '<input type="hidden" name="position" value="' . $defaultStaffPostition . '" />';
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

<?php
echo displayErrorMessage($data, 'errorMessage');
?>
