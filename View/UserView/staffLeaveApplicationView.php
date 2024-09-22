<?php
    /*Author Name: Chew Wei Seng*/
    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInStaff();//only allow the logged in staff to access
    
    $leaveAppliedSuccessfully = isset($_SESSION['leaveAppliedSuccessfully']) ? $_SESSION['leaveAppliedSuccessfully'] : '';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        
        <?php if (!empty($leaveAppliedSuccessfully)): ?>
            <div class="successSessionMsg" style="margin: 10px auto; width:40%">
                <?php echo '<p style="margin-top: 30px; center; background-color: green; color:white; text-align: center;">' . htmlspecialchars($leaveAppliedSuccessfully) . '</p>'; ?>
            </div>
            <?php unset($_SESSION['leaveAppliedSuccessfully']); // Clear the session message after displaying ?>
        <?php endif; ?>
        
        <div style="text-align: center;font-size: 18pt;border: 1px solid lightblue;background-color: lightblue;width: 40%;margin: 10px auto">
            <a href="index.php?controller=user&action=addLeaveApplication"><p>+ Apply A Leave Application</p></a>
        </div>
        
        <h1 style="margin-top: 50px;">Your Applied Leave</h1>
        
    </body>
</html>
