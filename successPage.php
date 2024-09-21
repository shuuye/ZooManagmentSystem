<style>
    .topnav {
        overflow: hidden;
        background-color: #94AF10;
    }

    .topnav a {
        float: left;
        color: #f2f2f2;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
    }

    .topnav a:hover {
        background-color: #ddd;
        color: black;
    }

    .topnav a.active {
        background-color: #04AA6D;
        color: white;
    }

    /* Dropdown container */
    .dropdown {
        float: left;
        overflow: hidden;
    }

    /* Dropdown button */
    .dropdown .dropbtn {
        font-size: 17px;
        border: none;
        outline: none;
        color: #f2f2f2;
        padding: 14px 16px;
        background-color: inherit;
        font-family: inherit;
        margin: 0;
    }

    /* Dropdown content (hidden by default) */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    /* Links inside the dropdown */
    .dropdown-content a {
        float: none;
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
    }

    /* Change color of dropdown links on hover */
    .dropdown-content a:hover {
        background-color: #ddd;
    }

    /* Show the dropdown menu on hover */
    .dropdown:hover .dropdown-content {
        display: block;
    }

    /* Change color of the dropdown button when the dropdown content is shown */
    .dropdown:hover .dropbtn {
        background-color: #ddd;
        color: black;
    }
</style>

<header>
    <div class="topnav">
        <a href="index.php"><img src="assests/Logo-Zoo-Negara.png" alt="Zoo Negara Home Page" width="60px" height="50px"/></a>
        <a href="index.php?controller=user&action=showMembership">Membership</a>
        <!-- ticket -->
        <a href="CustomerTicketPage.php">Ticket</a>


        <!-- Dropdown for Events -->
        <div class="dropdown">
            <button class="dropbtn">Events</button>
            <div class="dropdown-content">
                <a href="public_eventBooking.php">Public Event</a>
                <a href="private_eventBooking.php">Private Event</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Reservation List</button>
            <div class="dropdown-content">
                <a href="publicreservationlist.php">Public Event Reservation List</a>
                <a href="privatereservationlist.php">Private Event Reservation List</a>
            </div>
        </div>
        <a href="public/EventWebServices/generateqr.php">QR Code Generator</a>

        <!-- Login/SignUp/Profile/Logout links based on session -->
        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['currentUserModel'])) {
            echo '<a href="index.php?controller=user&action=showUserProfile" style="float: right">Profile</a>';
            echo '<a href="index.php?controller=user&action=logOut" style="float: right">Log Out</a>';
            echo '<p style="float: right; color: white; margin-top:15px;">Welcome, ' . $_SESSION['currentUserModel']['username'] . '</p>';
        } else {
            echo '
                <a href="index.php?controller=user&action=signUp" style="float: right">Sign Up</a>
                <a href="index.php?controller=user&action=login" style="float: right">Login</a>';
        }
        ?>
    </div>
</header>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Ensure user is logged in
if (!isset($_SESSION['currentUserModel']['id'])) {
    die('User is not logged in. Please log in and try again.');
}

// Load the XML data
$filePath = __DIR__ . '/Model/Xml/ticket_purchases.xml';
require_once __DIR__ . '/Model/Tickets/PaymentModel.php';

$paymentModel = new PaymentModel();
$transactionId = $_GET['transaction_id'] ?? null; // Get transaction ID from PayPal response
if ($transactionId === null) {
    die('Transaction ID is missing.');
}

$totalPrice = $paymentModel->calculateTotalPrice(); // Calculate total price
// Save payment details to the database
try {
    $paymentModel->savePaymentDetails($transactionId, $totalPrice, $_SESSION['currentUserModel']['id']);
} catch (Exception $e) {
    die('Failed to save payment details: ' . htmlspecialchars($e->getMessage()));
}

// Load the XML file
if (file_exists($filePath)) {
    $xml = simplexml_load_file($filePath);
} else {
    die('XML file not found.');
}

// Start outputting the summary report
echo '<html><head><title>Invoice Summary</title>';
echo '<style>


        h1 {
            text-align: center;
            color: #000;
            margin-bottom: 30px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #000;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .total {
            font-weight: bold;
            font-size: 1.5em;
            color: #000;
            text-align: center;
            margin: 20px 0;
        }

        .confirm-button {
            display: block;
            margin: 30px auto;
            padding: 12px 24px;
            background-color: #000;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
            border: 2px solid #000;
            transition: background-color 0.3s, color 0.3s;
        }

        .confirm-button:hover {
            background-color: white;
            color: #000;
        }

        .confirm-button:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
        }
</style>';
echo '</head><body>';
echo '<h1>Invoice Summary</h1>';

// Display the ticket details in a table
echo '<table>';
echo '<tr><th>Type</th><th>Quantity</th><th>Price (RM)</th><th>Visit Date</th></tr>';

$totalAmount = 0;

foreach ($xml->Ticket as $ticket) {
    $type = htmlspecialchars($ticket->type);
    $quantity = htmlspecialchars($ticket->quantity);
    $price = number_format((float) $ticket->price, 2, '.', '');
    $visitDate = htmlspecialchars($ticket->visit_date);

    $totalAmount += (float) $ticket->total;

    echo '<tr>';
    echo '<td>' . $type . '</td>';
    echo '<td>' . $quantity . '</td>';
    echo '<td>' . $price . '</td>';
    echo '<td>' . $visitDate . '</td>';
    echo '</tr>';
}

echo '</table>';

// Display the total amount
echo '<h2 class="total">Total Amount After Discount: RM' . number_format($totalPrice, 2, '.', '') . '</h2>';
echo '<a href="index.php" class="confirm-button">Confirm and Return to Home</a>';

echo '</body></html>';
?>
