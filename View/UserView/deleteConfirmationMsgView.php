<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForLoggedInEditPermissionAdmin();


    if (!isset($data['selectedUser']['id'])) {
        header('Location: index.php?controller=user&action=userManagementMainPanel');
        exit;
    }

    // Include required files
    require_once __DIR__ . '/../../Model/User/MembershipModel.php';
    require_once __DIR__ . '/../../Model/User/RolesModel.php';
    require_once __DIR__ . '/../../Model/User/CustomerModel.php';
    require_once __DIR__ . '/../../Model/User/AdminModel.php';
    require_once __DIR__ . '/../../Model/User/StaffModel.php';

    $membershipModel = new MembershipModel();
    $roleModel = new RolesModel();
    $customerModel = new CustomerModel();
    $adminModel = new AdminModel();
    $staffModel = new StaffModel();

    $adminType = '';
    $membership = '';
    $position = '';
    $roleID = '';

    function setPermission($adminType){
        $permissions = '';
        switch ($adminType) {
            case 'ReadOnlyAdmin':
                $permissions = 'read';
                break;
            case 'EditorAdmin':
                $permissions = 'read, edit';
                break;
            case 'SuperAdmin':
                $permissions = 'read, edit, manage admin';
                break;
            default:
                $permissions = 'read';
                break;
        }

        return $permissions;
    }

    if(isset($data['selectedUser'])){
        $user = $data['selectedUser'];
        $id = $data['selectedUser']['id'];
        $roleID = $data['selectedUser']['roleID'];
        $role = $roleModel->getRoleByID($roleID);

        if($roleID == 1){
            $adminType = $adminModel->getAdminTypeByID($id);
            $permissions = setPermission($adminType);
        }elseif($roleID == 2){
            $membershipID = $customerModel->getMembershipIDByID($id);
            $membership = $membershipModel->getMembershipByMembershipID($membershipID);
        }elseif($roleID == 3){
            $position = $staffModel->getPositionByID($id);
        }
    }
    // Form HTML
?>

<html>
    <body>
        <form action="index.php?controller=user&action=confirmDeletion" method="POST">
            <!-- Hidden input to pass user ID -->
            <input type="hidden" name="userID" value="<?php echo htmlspecialchars($user['id']); ?>">
            
            <table>
                <tr>
                    <td colspan="2"><h4>Are you sure to delete the user:</h4></td>
                </tr>
                <tr>
                    <td>User ID:</td>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                </tr>
                <tr>
                    <td>Username:</td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                </tr>
                <tr>
                    <td>Full Name:</td>
                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                </tr>
                <tr>
                    <td>Phone Number:</td>
                    <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                </tr>

            </table>

            <?php if ($roleID == 1 && $adminType != '' && $permissions != ''): ?>
                <br><br>
                <table>
                    <tr>
                        <td colspan="2"><h4 style="color: red;">Deleting this user will also delete the following admin data:</h4></td>
                    </tr>
                    <tr>
                        <td>Admin Type:</td>
                        <td><?php echo htmlspecialchars($adminType); ?></td>
                    </tr>
                    <tr>
                        <td>Permissions:</td>
                        <td><?php echo htmlspecialchars($permissions); ?></td>
                    </tr>
                </table>
            <?php elseif ($roleID == 2 && $membership != ''): ?>
                <br><br>
                <h4 style="color: red;">Deleting this user will also delete the following customer data:</h4>
                <table>
                    <tr>
                        <td>Membership ID:</td>
                        <td><?php echo htmlspecialchars($membership['membershipID']); ?></td>
                    </tr>
                    <tr>
                        <td>Membership Type:</td>
                        <td><?php echo htmlspecialchars($membership['membershipType']); ?></td>
                    </tr>
                    <tr>
                        <td>Fee:</td>
                        <td><?php echo htmlspecialchars($membership['fee']); ?></td>
                    </tr>
                    <tr>
                        <td>Discount Offered:</td>
                        <td><?php echo htmlspecialchars($membership['discountOffered']); ?></td>
                    </tr>
                </table>
            <?php elseif ($roleID == 3 && $position != ''): ?>
                <br><br>
                <h4 style="color: red;">Deleting this user will also delete the following staff data:</h4>
                <table>
                    <tr>
                        <td>Position:</td>
                        <td><?php echo htmlspecialchars($position); ?></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><p style="color: red;">And His/Her Following Work Schedule and Attendance Record.</p></td>
                    </tr>
                    <tr>
                        <td><p style="color: red;">Also His/Her Following Leave Applied.</p></td>
                    </tr>
                </table>
            <?php endif; ?>

            <table>
                <tr>
                    <td>
                        <button type="submit" name="submitDeletionConfirmation">
                            Confirm
                        </button>

                    </td>
                    <td>
                        <a href="<?php if (isset($_SESSION['currentUserModel']['role']['roleID'])) {
                                            if ($_SESSION['currentUserModel']['role']['roleID'] == 1) {
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
        
    </body>
</html>