
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
            
            if(isset($_SESSION['currentUserModel']) && $_SESSION['currentUserModel']['role']['roleID'] == 2){
                
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
        
        <div style="background-color: lightgreen; height: 100%; width: 950px; margin: 0px auto">
            <img src="/ZooManagementSystem/assests/zooBanner.png" alt="" width="100%">
            <img src="/ZooManagementSystem/assests/tiger.png" alt="" width="100%">
            <h3 style="color: red">
                Zoo Negara
            </h3>
            <p>
                Zoo Negara Malaysia is managed by the Malaysian Zoological Society, a non-governmental organization established to create the first local zoo for Malaysians. Zoo Negara was officially opened on 14th November 1963 and has matured into a well-known zoo all around the world. We have a total of over 3575 specimen from 305 species of mammals, birds, reptiles, amphibians and fish.

                Zoo Negara covers 110 acres of land which is situated only 5km from the city of Kuala Lumpur. Over the years, the zoo has transformed itself to an open concept zoo with over 90% of its animals being kept in spacious exhibits with landscape befitting its nature. We are working in making sure that the old zoo concept is changed entirely.
            </p>
            <?php include 'footer.php' ?>
        </div>
        
    </body>
    
</html>
