<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            session_start();
            $currentUser = $_SESSION['user'];
           
            echo '<div style="color: green;"> hello!' . $currentUser['username'] . '</div>';
            if (isset($_SESSION['error_message'])) {
                echo '<div style="color: red;">' . $_SESSION['error_message'] . '</div>';
                unset($_SESSION['error_message']); // Clear the error message after displaying it
            }
            
            if (isset($_SESSION['user'])) {
                echo '<pre>';
                print_r($_SESSION['user']);
                echo '</pre>';
            } else {
                echo 'No user data in session.';
            }
        ?>
    </body>
</html>
