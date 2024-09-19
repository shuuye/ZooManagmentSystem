
<html>
    <?php 
        $cssFiles = ['homeCss.css'];

        include 'htmlHead.php' 
    ?>
    <body>
        
        <?php include 'clientTopNavHeader.php' ?>
         
        <?php
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
                        
            //if (isset($_SESSION['userModel'])) {
                //$userModel = $_SESSION['userModel'];
            if(isset($_SESSION['currentUserModel']) && $_SESSION['currentUserModel']['role']['roleID'] == 2){
                echo "Welcome, " . htmlspecialchars($_SESSION['currentUserModel']['fullName']) . "!";
                //testing
                echo '<a href="CustomerTicketPage.php" class="link-box">Book Ticket</a>';
                
            } elseif(isset($_SESSION['currentUserModel']) && $_SESSION['currentUserModel']['role']['roleID'] == 1) {
                header("Location: index.php?controller=admin&action=displayAdminMainPanel");
                exit();
            } elseif(isset($_SESSION['currentUserModel']) && $_SESSION['currentUserModel']['role']['roleID'] == 3) {
                header("Location: index.php?controller=user&action=staffMainPanel");
                exit();
            }else{
                //echo "No user is logged in.";
            }
        ?>
        <?php include 'footer.php' ?>
    </body>
    
</html>
