<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <title>Zoo Negara</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="Css/userManagementCss.css">
    </head>
    <body>
        <?php
            session_start();
            require_once 'View/userManagementUI.php';
            require_once 'Control/userManagementControl.php';

            if (isset($_SESSION['error_message'])) {
                echo '<div style="color: red;">' . $_SESSION['error_message'] . '</div>';
                unset($_SESSION['error_message']); // Clear the error message after displaying it
            }

            // Initialize data if not already done
            userManagementUI::welcomeRegisterBanner();
            userManagementControl::initializeRegisteringData();
            userManagementControl::validateRegistringInput();
            userManagementUI::displayRegistrationForm();
        ?>
        
        
    </body>
</html>
