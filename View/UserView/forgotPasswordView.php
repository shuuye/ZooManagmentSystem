<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    function getError($data, $key) {
        return isset($data[$key]) && $data[$key] !== '' ? htmlspecialchars($data[$key]) : '';
    }
    
    include 'View/clientTopNavHeader.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Forgot Password</title>
        <style>
            .form-container {
                margin: 50px auto;
                background: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 400px;
            }
            .form-container h2 {
                margin-bottom: 20px;
                text-align: center;
            }
            .form-container input[type="email"] {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            .form-container input[type="submit"] {
                width: 100%;
                padding: 10px;
                background-color: #28a745;
                border: none;
                border-radius: 4px;
                color: white;
                font-size: 16px;
                cursor: pointer;
            }
            .form-container input[type="submit"]:hover {
                background-color: #218838;
            }
        </style>
    </head>
    <body>
        <div class="form-container">
            <h2>Forgot Password</h2>
            <form action="index.php?controller=user&action=submitForgotPasswordEmail" method="POST">
                <label for="email">Enter your email address:</label>
                <input type="email" id="email" name="email" required placeholder="example@example.com">
                <!--display the msg in session and unset the session after the msg displayed-->
                <div class="error"><?php if (isset($_SESSION['userInputData'])): ?>
                    <?php echo getError($_SESSION['userInputData'], 'emailErr'); ?>
                    <?php unset($_SESSION['userInputData']); ?>
                <?php endif; ?>
                </div>
                <input type="submit" value="Reset Password">
            </form>
        </div>
    </body>
</html>

