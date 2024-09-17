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
        <a href="">Reservation Management Panel</a>
        <a href="index.php?controller=user&action=userManagementMainPanel">User Management Panel</a>
        <a href="">Inventory Management Panel</a>
        <a href="">Ticketing & Payment Management Panel</a>
        <a href="">Animal Management Panel</a>
    </div>
</header>
