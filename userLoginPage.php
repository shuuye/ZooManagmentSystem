<!DOCTYPE html>

<html>
    <head>
        <title>Customer Details</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="Css/userManagementCss.css">
    </head>
    <body>
        <div>Customer Information:</div>
        <?php
        /*
            require_once 'Entity/User.php';
            require_once 'Entity/Admin.php';
            require_once 'Entity/Customer.php';
            require_once 'Entity/Membership.php';

            // Example usage
            $membership = new Membership(1, 'Premium', 99.99, '20%');
            $customer = new Customer(3, 'customer1', 'password123', 'John', 'Doe', '123-456-7890', 'customer1@example.com', $membership);

            echo "First Name: " . $customer->getFirstName() . "<br>"; // Output: John
            echo "Last Name: " . $customer->getLastName() . "<br>"; // Output: Doe
            echo "Phone Number: " . $customer->getPhoneNumber() . "<br>"; // Output: 123-456-7890
        */
            session_start();
            require_once 'Boundary/userManagementUI.php';

            if (isset($_SESSION['error_message'])) {
                echo '<div style="color: red;">' . $_SESSION['error_message'] . '</div>';
                unset($_SESSION['error_message']); // Clear the error message after displaying it
            }

            
            require_once 'Boundary/userManagementUI.php';
            userManagementUI::welcomeLoginBanner();
            userManagementUI::displayLoginForm();
         
        ?>
    </body>
</html>
