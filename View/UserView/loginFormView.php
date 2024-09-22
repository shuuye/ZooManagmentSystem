<?php
    /*Author Name: Chew Wei Seng*/
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['currentUserModel'])) {
        //clear the user session when ever reach this page
        unset($_SESSION['currentUserModel']);
    }
    
    unset($_SESSION['registrationSuccess']);
    unset($_SESSION['editSuccess']);
    unset($_SESSION['deleteSuccess']);
    unset($_SESSION['deleteFailed']);

    function getError($data, $key) {
        return isset($data[$key]) && $data[$key] !== '' ? htmlspecialchars($data[$key]) : '';
    }

    function displayErrorMessage($data, $key) {
        return isset($data[$key]) && $data[$key] !== '' ? '<div class="error-message">' . htmlspecialchars($data[$key]) . '</div>' : '';
    }
?>

<?php include 'View/clientTopNavHeader.php' ?>

<form action="index.php?controller=user&action=submitLoginForm" method="POST">
    <table>
        <tr>
            <td><label for="username">Username:</label></td>
            <td><input type="text" id="username" name="username" required></td>
            <td class="error"><?php echo getError($data['userLoginInputData'], 'usernameErr'); ?></td>
        </tr>
        <tr>
            <td><label for="password">Password:</label></td>
            <td><input type="password" id="password" name="password" required></td>
            <td class="error"><?php echo getError($data['userLoginInputData'], 'passwordErr'); ?></td>
        </tr>
        <tr>
            <td class="error"><?php echo getError($data['userLoginInputData'], 'loginErr'); ?></td>
        </tr>
        <tr>
            <td><button type="submit">Login</button></td>
        </tr>
    </table>
</form>

<p><a href="index.php?controller=user&action=forgotPassword">Forgot Password</a></p>
<p>Don't have an account? &nbsp; <a href="index.php?controller=user&action=signUp">Create New Account</a></p>
