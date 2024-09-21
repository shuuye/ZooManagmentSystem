<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Config/webConfig.php';
$webConfig = new webConfig();
$webConfig->restrictAccessForNonLoggedInAdmin();//only allow the logged in admin to access

$cssFiles = ['homeCss.css'];
$pageTitle = 'Admin Main Panel';
include 'htmlHead.php' ;

?>
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
      
      main {
        margin: 0;
        height: 70vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f4f4f4;
    }

    .container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        width: 850px;
        text-align: center;
    }

    .link-box {
        width: 850px;
        height: 90px;
        margin: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: lightblue;
        text-decoration: none;
        color: black;
        font-size: 18pt;
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }

    .link-box:hover {
        background-color: darkblue;
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
    
    
    <h2>Welcome to the Admin Main Panel</h2> 
    <p>Select an option:</p>
    <br>
    
</header>
<main>
   
    <div class="container">
        <a href="/ZooManagementSystem/createanddeletefunction.php" class="link-box">Event Management</a>
        <a href="index.php?controller=user&action=userManagementMainPanel" class="link-box">User Management</a>
        <a href="index.php?controller=inventory&action=index" class="link-box">Inventory Management Panel</a>
        <?php
        if (isset($_SESSION['currentUserModel']) && $_SESSION['currentUserModel']['role']['roleID'] == 1 && !in_array('edit', $_SESSION['currentUserModel']['permissions'])){
            echo'<a href="/ZooManagementSystem/displayTicketsXslt.php" class="link-box">Ticketing & Payment Management Panel</a>';
        } else {
            echo'<a href="/ZooManagementSystem/adminTicketPage.php" class="link-box">Ticketing & Payment Management Panel</a>';
        }
        ?>
        <a href="View/AnimalView/animal_home.php" class="link-box">Animal Management Panel</a>
    </div>
</main>


