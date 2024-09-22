<?php
    /*Author Name: Chew Wei Seng*/
    if (session_status() === PHP_SESSION_NONE) {
            session_start();
    }
    
    //set the session data
    $registrationUserSuccess = isset($_SESSION['registrationUserSuccess']) ? $_SESSION['registrationUserSuccess'] : '';
    $resetPasswordSuccessfully = isset($_SESSION['resetPasswordSuccessfully']) ? $_SESSION['resetPasswordSuccessfully'] : '';
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <!--display the set data and unset the session after displayed the msg-->
        <?php if (!empty($registrationUserSuccess)): ?>
            <div class="successSessionMsg" style="margin: 10% auto;" >
                <?php echo htmlspecialchars($registrationUserSuccess); ?>
            </div>
            <?php unset($_SESSION['registrationUserSuccess']);  ?>
        <?php endif; ?>

        <?php if (!empty($resetPasswordSuccessfully)): ?>
            <div class="successSessionMsg" style="margin: 10% auto;">
                <?php echo htmlspecialchars($resetPasswordSuccessfully); ?>
            </div>
            <?php unset($_SESSION['resetPasswordSuccessfully']); ?>
        <?php endif; ?>
        
        <div style="margin: 10% auto; text-align: center;">
            <a href="index.php?controller=user&action=login" style="font-size: 20pt; color: green; ">Login Now</a>
        </div>
        
    </body>
</html>

    
