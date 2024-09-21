<?php
    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInAdmin();//only allow the logged in admin to access

    // Check for registration success and edit success
    $registrationSuccess = isset($_SESSION['registrationSuccess']) ? $_SESSION['registrationSuccess'] : '';
    $editSuccess = isset($_SESSION['editSuccess']) ? $_SESSION['editSuccess'] : '';
    $deleteSuccess = isset($_SESSION['deleteSuccess']) ? $_SESSION['deleteSuccess'] : '';
    $deleteFailed = isset($_SESSION['deleteFailed']) ? $_SESSION['deleteFailed'] : '';
    $editingFailed = isset($_SESSION['editingFailed']) ? $_SESSION['editingFailed'] : '';
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <style>
            .successSessionMsg{
                color: white; 
                border: 1px solid green; 
                width: 30%; 
                height: 40px;
                background-color: green;
                align-items: center;
                display: flex;
                justify-content: space-around;
            }
            .failedSessionMsg{
                color: white; 
                border: 1px solid red; 
                width: 30%; 
                height: 40px;
                background-color: red;
                align-items: center;
                display: flex;
                justify-content: space-around;
            }
        </style>
        <!--display the msg in session and unset the session after the msg displayed-->
        <?php if (!empty($registrationSuccess)): ?>
            <div class="successSessionMsg" >
                <?php echo htmlspecialchars($registrationSuccess); ?>
            </div>
            <?php unset($_SESSION['registrationSuccess']); // Clear the session message after displaying ?>
        <?php endif; ?>

        <?php if (!empty($editSuccess)): ?>
            <div class="successSessionMsg">
                <?php echo htmlspecialchars($editSuccess); ?>
            </div>
            <?php unset($_SESSION['editSuccess']); ?>
        <?php endif; ?>
        
        <?php if (!empty($editingFailed)): ?>
            <div class="failedSessionMsg">
                <?php echo htmlspecialchars($editingFailed); ?>
            </div>
            <?php unset($_SESSION['editingFailed']); ?>
        <?php endif; ?>

        <?php if (!empty($deleteSuccess)): ?>
            <div class="successSessionMsg">
                <?php echo htmlspecialchars($deleteSuccess); ?>
            </div>
            <?php unset($_SESSION['deleteSuccess']); ?>
        <?php endif; ?>

        <?php if (!empty($deleteFailed)): ?>
            <div class="failedSessionMsg">
                <?php echo htmlspecialchars($deleteFailed); ?>
            </div>
            <?php unset($_SESSION['deleteFailed']); ?>
        <?php endif; ?>
        
    </body>
</html>
