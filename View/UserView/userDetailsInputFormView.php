<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessBasedOnActioninData($data);

    function getError($data, $key) {
        return isset($data[$key]) && $data[$key] !== '' ? htmlspecialchars($data[$key]) : '';
    }

    function displayErrorMessage($data, $key) {
        return isset($data[$key]) && $data[$key] !== '' ? '<div class="error-message">' . htmlspecialchars($data[$key]) . '</div>' : '';
    }

    function getRoleOptions($roles, $selectedRoleID) {
        $options = '';
        foreach ($roles as $role) {
            $isSelected = ($role["roleID"] == $selectedRoleID) ? "selected" : "";
            $options .= '<option value="' . htmlspecialchars($role["roleID"]) . '" ' . $isSelected . '>' . htmlspecialchars($role["roleName"]) . '</option>';
        }
        return $options;
    }

    function getSubmitButtonValue($data) {
        if (!isset($_SESSION['currentUserModel'])) {
            return 'register';
        }

        // Check if the user is an admin
        if ($_SESSION['currentUserModel']['role']['roleID'] != 1) {
            return 'register';
        }

        // Handle when the action and selected user are set
        if (isset($data['action']) && isset($data['selectedUser'])) {
            return $data['action'] == 'edit' ? 'adminEdit' : 'register';
        }

        return 'register';
    }

    function getSubmitButtonText($data) {
        if (!isset($_SESSION['currentUserModel'])) {
            return 'Register';
        }

        // Check if the user is an admin
        if ($_SESSION['currentUserModel']['role']['roleID'] != 1) {
            return 'Register';
        }

        // Handle when the action and selected user are set
        if (isset($data['action']) && isset($data['selectedUser'])) {
            return $data['action'] == 'edit' ? 'Edit' : 'Register';
        }

        return 'Register';
    }

    // Include required files
    require_once __DIR__ . '/../../Model/User/RolesModel.php';
    require_once __DIR__ . '/../../Control/UserController/InputValidationCtrl.php';

    // Get roles
    $roleModel = new RolesModel;
    $roles = $roleModel->getAllRoles();

?>

<?php 
//display different header based on user role
    if(isset($_SESSION['currentUserModel']) && $_SESSION['currentUserModel']['role']['roleID'] == 2){
        include 'View/clientTopNavHeader.php';
    }else if(!isset ($_SESSION['currentUserModel'])){
        include 'View/clientTopNavHeader.php';
    }
    
    if(isset($data['action'])){
        if ($data['action'] == 'edit'){
            echo '<h1>Editing Form</h1>';
        }
    }else{
        echo '<h1>Registration Form</h1>';
    }
    
?>
   
<form action="index.php?controller=user&action=submitUserDetailsInputForm" method="POST">
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
        }
        ?>
        <?php if ((isset($data['action']) && isset($data['selectedUser'])) || (isset($data['action']) && $data['action'] == 'edit')): ?>
        <tr>
            <td><label for="id">User ID:</label></td>
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
            <td class="error"><?php if (isset($data['userInputData'])): ?>
                <?php echo getError($data['userInputData'], 'usernameErr'); ?>
            <?php endif; ?>
            </td>
        </tr>
        
        <tr>
            <td><label for="password">Password:</label></td>
            <td>
                <input type="<?php 
                            if (isset($data['selectedUser']) && isset($data['action']) && $data['action'] == 'edit' && isset($data['selectedUser']['password'])) {
                                echo 'text'; // Change input type to 'text'
                            } else {
                                echo 'password'; // Default to 'password'
                            }
                        ?>" 
                        id="password" 
                        name="password" 
                        autocomplete="off" 
                        required
                        value="<?php 
                           if (isset($data['selectedUser']) && isset($data['action'])) {
                               if ($data['action'] == 'edit' && isset($data['selectedUser']['password'])) {
                                   echo $data['selectedUser']['password']; 
                               }
                           }
                       ?>"
                       <?php 
                           if (isset($data['selectedUser']) && isset($data['action'])) {
                               if ($data['action'] == 'edit' && isset($data['selectedUser']['password'])) {
                                   echo 'disabled'; 
                               }
                           }
                       ?>>
            </td>
            <td class="error"><?php if (isset($data['userInputData'])): ?>
                <?php echo getError($data['userInputData'], 'passwordErr'); ?>
            <?php endif; ?>
            </td>
        </tr>
                
        <?php if ((!isset($data['action']) && !isset($data['selectedUser'])) || (isset($data['action']) && $data['action'] != 'edit')): ?>
        <tr>
            <td><label for="confirmPassword">Confirm Password:</label></td>
            <td><input type="password" id="confirmPassword" name="confirmPassword" autocomplete="off" required></td>
            <td class="error"><?php if (isset($data['userInputData'])): ?>
                <?php echo getError($data['userInputData'], 'confirmPasswordErr'); ?>
            <?php endif; ?>
            </td>
        </tr>
        <?php endif; ?>
        
        <tr>
            <td><label for="full_name">Full Name:</label></td>
            <td>
                <input type="text" 
                        id="full_name" 
                        name="full_name" 
                        autocomplete="off" 
                        required
                        value="<?php 
                           if (isset($data['selectedUser']) && isset($data['action'])) {
                               if ($data['action'] == 'edit' && isset($data['selectedUser']['full_name'])) {
                                   echo $data['selectedUser']['full_name']; 
                               }
                           }
                       ?>"
                >
            </td>
            <td class="error"><?php if (isset($data['userInputData'])): ?>
                <?php echo getError($data['userInputData'], 'full_nameErr'); ?>
            <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td><label for="phone_number">Contact Number:</label></td>
            <td>
                <input type="text" 
                        id="phone_number" 
                        name="phone_number" 
                        autocomplete="off" 
                        required
                        value="<?php 
                           if (isset($data['selectedUser']) && isset($data['action'])) {
                               if ($data['action'] == 'edit' && isset($data['selectedUser']['phone_number'])) {
                                   echo $data['selectedUser']['phone_number']; 
                               }
                           }
                       ?>"
                >
            </td>
            <td class="error"><?php if (isset($data['userInputData'])): ?>
                <?php echo getError($data['userInputData'], 'phone_numberErr'); ?>
            <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td><label for="email">Email:</label></td>
            <td>
                <input type="text" 
                        id="email" 
                        name="email" 
                        autocomplete="off" 
                        required
                        value="<?php 
                           if (isset($data['selectedUser']) && isset($data['action'])) {
                               if ($data['action'] == 'edit' && isset($data['selectedUser']['email'])) {
                                   echo $data['selectedUser']['email']; 
                               }
                           }
                       ?>"
                >
            </td>
            <td class="error"><?php if (isset($data['userInputData'])): ?>
                <?php echo getError($data['userInputData'], 'emailErr'); ?>
            <?php endif; ?>
            </td>
        </tr>
       
        <?php
        // Handle role selection based on user permissions
        if (isset($_SESSION['currentUserModel']) && isset($_SESSION['currentUserModel']['role']['roleID']) && isset($_SESSION['currentUserModel']['adminType']) && isset($_SESSION['currentUserModel']['permissions'])) {
            if ($_SESSION['currentUserModel']['role']['roleID'] == 1 && $_SESSION['currentUserModel']['adminType'] == 'SuperAdmin' && in_array('manage admin', $_SESSION['currentUserModel']['permissions'])) {
                $selectedRoleID = isset($data['selectedUser']['role']['roleID']) ? $data['selectedUser']['role']['roleID'] : 2;
                echo '
                <tr>
                    <td>Role:</td>
                    <td>
                        <select name="roleID">
                            ' . getRoleOptions($roles, $selectedRoleID) . '
                        </select>
                    </td>
                </tr>';
            } elseif (isset($_SESSION['currentUserModel']['role']['roleID']) && isset($data['action'])) {
                if ($_SESSION['currentUserModel']['role']['roleID'] == 1 && $data['action'] == 'edit') {
                    echo '<input type="hidden" name="roleID" value="' . $data['selectedUser']['role']['roleID'] . '">';
                }
            } else {
                // Default role for non-admins or other conditions
                echo '<input type="hidden" name="roleID" value="2">';
            }
        } else {
            // Default role for guests or non-authenticated users
            echo '<input type="hidden" name="roleID" value="2">';
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
                <button type="submit" name="submitAs" value="<?php echo htmlspecialchars(getSubmitButtonValue($data)); ?>">
                    <?php echo htmlspecialchars(getSubmitButtonText($data)); ?>
                </button>
            </td>
            <td>
                
                <a href="<?php if (isset($_SESSION['currentUserModel']['role']['roleID']) && isset($data['action'])) {
                                    if ($_SESSION['currentUserModel']['role']['roleID'] == 1 && $data['action'] == 'edit') {
                                        echo 'index.php?controller=user&action=userManagementMainPanel';
                                    }else{
                                        echo 'index.php';
                                    }
                                }else if(isset($_SESSION['currentUserModel']['role']['roleID'])){
                                    if($_SESSION['currentUserModel']['role']['roleID'] == 1){
                                        echo 'index.php?controller=user&action=userManagement';
                                    }
                                } 
                                else{
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
