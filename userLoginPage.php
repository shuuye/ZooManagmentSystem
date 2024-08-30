<!DOCTYPE html>

<html>
    <head>
        <title>Zoo Negara</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="Css/userManagementCss.css">
    </head>
    <body>
        
        <?php 
        /*
            include('Model/User.php');
            $user = new User();
            $isLoggedIn =  $user->login();
            if($isLoggedIn){
                require_once 'Control/userManagementControl.php';
                userManagementControl::setUserByRoles();
                $user->navigateToBasedOnRoles();
            }else {
                $errorMessage = $_SESSION['error_message'];
                // Handle the login failure (e.g., display the error message)
            }*/
        ?>
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
        ----------------------------------------------------------------------
            require_once 'View/userManagementUI.php';

            if (isset($_SESSION['error_message'])) {
                echo '<div style="color: red;">' . $_SESSION['error_message'] . '</div>';
                unset($_SESSION['error_message']); // Clear the error message after displaying it
            }
            
            userManagementUI::welcomeLoginBanner();
            userManagementUI::displayLoginForm();*/
            require_once 'Xml/createXMLFromDatabase.php';
            $xmlGenerator = new createXMLFromDatabase();
            $xmlGenerator->createXMLFileByTableName("users", "Xml/users.xml", "users", "user", "id");
            //$xmlGenerator->displayXMLData("users.xml")

        ?>
    </body>
</html>
