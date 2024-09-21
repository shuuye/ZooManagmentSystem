<style>
    .topnav {
        overflow: hidden;
        background-color: #94AF10;
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

    /* Dropdown container */
    .dropdown {
        float: left;
        overflow: hidden;
    }

    /* Dropdown button */
    .dropdown .dropbtn {
        font-size: 17px;
        border: none;
        outline: none;
        color: #f2f2f2;
        padding: 14px 16px;
        background-color: inherit;
        font-family: inherit;
        margin: 0;
    }

    /* Dropdown content (hidden by default) */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    /* Links inside the dropdown */
    .dropdown-content a {
        float: none;
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
    }

    /* Change color of dropdown links on hover */
    .dropdown-content a:hover {
        background-color: #ddd;
    }

    /* Show the dropdown menu on hover */
    .dropdown:hover .dropdown-content {
        display: block;
    }

    /* Change color of the dropdown button when the dropdown content is shown */
    .dropdown:hover .dropbtn {
        background-color: #ddd;
        color: black;
    }
</style>

<header>
    <div class="topnav">
        <a href="index.php"><img src="assests/Logo-Zoo-Negara.png" alt="Zoo Negara Home Page" width="60px" height="50px"/></a>
        <a href="index.php?controller=user&action=showMembership">Membership</a>
        <a href="#contact">Contact</a>
        <!-- ticket -->
        <a href="CustomerTicketPage.php">Ticket</a>
        

        <!-- Dropdown for Events -->
        <div class="dropdown">
            <button class="dropbtn">Events</button>
            <div class="dropdown-content">
                <a href="public_eventBooking.php">Public Event</a>
                <a href="private_eventBooking.php">Private Event</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Reservation List</button>
            <div class="dropdown-content">
                <a href="publicreservationlist.php">Public Event Reservation List</a>
                <a href="privatereservationlist.php">Private Event Reservation List</a>
            </div>
        </div>
        <a href="public/EventWebServices/generateqr.php">QR Code Generator</a>

        <!-- Login/SignUp/Profile/Logout links based on session -->
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
