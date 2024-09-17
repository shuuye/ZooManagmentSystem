<style>
    .topnav {
        overflow: hidden;
        background-color: #393838;
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
        <a href="index.php?controller=user&action=staffMainPanel">Staff Main Panel</a>
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
    <div class="topnav" style="background-color: #797777;">
        <a href="index.php?controller=user&action=staffWorkingScheduleWithAttendance" class="link-box">Working Schedule With Attendance</a>
        <a href="index.php?controller=user&action=staffTakeWorkingAttendance" class="link-box">Take Attendance</a>
        <a href="index.php?controller=user&action=staffLeaveApplication" class="link-box">Leave Application</a>
    </div>
</header>
