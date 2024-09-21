<style>
    .topnav {
        overflow: hidden;
        background-color: darkblue;
        height: 30px;
      }

      .topnav a {
        float: left;
        color: #f2f2f2;
        text-align: center;
        padding: 5px 16px;
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
        <a href="index.php?controller=admin&action=displayAdminMainPanel">Admin Main Panel</a>
        <?php 
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if( isset($_SESSION['currentUserModel']) ){
                echo '<a href="index.php?controller=user&action=logOut" style="float: right">Log Out</a>';
                echo '<a href="index.php?controller=user&action=showUserProfile" style="float: right">Profile</a>';
                echo '<p style="float: right; color: white; margin-top:7px;">Welcome, ' . $_SESSION['currentUserModel']['username'] . '</p>';
            }
        ?>
    </div>
    
    <!--place link-->
    <div class="topnav" style="background-color: blue;">
        <a href="/ZooManagementSystem/createanddeletefunction.php" class="link-box">Event Management</a>
        <a href="index.php?controller=user&action=userManagementMainPanel" class="link-box">User Management</a>
        <a href="index.php?controller=inventory&action=index" class="link-box">Inventory Management Panel</a>
        <?php
        if ($_SESSION['currentUserModel']['id'] == '3') {
            echo'<a href="/ZooManagementSystem/displayTicketsXslt.php" class="link-box">Ticketing Management Panel</a>';
        } else {
            echo'<a href="/ZooManagementSystem/adminTicketPage.php" class="link-box">Ticketing & Payment Management Panel</a>';
        }
        ?>
        <a href="/ZooManagementSystem/View/AnimalView/animal_home.php" class="link-box">Animal Management Panel</a>
    </div>
</header>
