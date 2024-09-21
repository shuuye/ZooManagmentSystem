<style>
    .topnav {
        overflow: hidden;
        background-color: #94AF10;
        ;
    }

    .topnav a {
        float: left;
        color: #f2f2f2;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
    }

    .topnav a:hover {
        background-color: #ddd;
        color: black;
    }

    .topnav a.active {
        background-color: #04AA6D;
        color: white;
    }
</style>

<header>
    <div class="topnav">
        <a href="index.php"><img src="assests/Logo-Zoo-Negara.png" alt="Zoo Negara Home Page" width="60px" height="50px"/></a>
        <a href="index.php?controller=user&action=showMembership">Membership</a><!--set thing you gonna navigate for customer here-->

        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['currentUserModel'])) {
            echo '<a href="index.php?controller=user&action=showUserProfile" style="float: right">Profile</a>';
            echo '<a href="index.php?controller=user&action=logOut" style="float: right">Log Out</a>';
            echo '<p style="float: right; color: white; margin-top:15px;">Welcome, ' . $_SESSION['currentUserModel']['username'] . '</p>';
        } else {
            echo '
                <a href="index.php?controller=user&action=signUp" style="float: right">Sign Up</a>
                <a href="index.php?controller=user&action=login" style="float: right">Login</a>';
        }
        ?>

    </div>
</header>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/Control/TicketController/CustomerTicketControl.php';

// Ensure user is logged in
if (!isset($_SESSION['currentUserModel']['id'])) {
    die('User is not logged in. Please log in and try again.');
}

// Initialize the controller and handle the request
try {
    $controller = new CustomerTicketControl();
    $controller->route();
} catch (Exception $e) {
    // Display an error message if something goes wrong
    echo '<p style="color: red;">' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>
