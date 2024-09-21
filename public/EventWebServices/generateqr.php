<!DOCTYPE html>
<html lang="en">
<head>
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
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
</head>
<header>
    <div class="topnav">
        <a href="../../index.php"><img src="../../assests/Logo-Zoo-Negara.png" alt="Zoo Negara Home Page" width="60px" height="50px"/></a>
        <a href="../../index.php?controller=user&action=showMembership">Membership</a><!--set thing you gonna navigate for customer here-->
        <a href="#contact">Contact</a>
        
        <!-- ticket -->
        <a href="/ZooManagementSystem/CustomerTicketPage.php">Ticket</a>
        

        <!-- Dropdown for Events -->
        <div class="dropdown">
            <button class="dropbtn">Events</button>
            <div class="dropdown-content">
                <a href="/ZooManagementSystem/public_eventBooking.php">Public Event</a>
                <a href="/ZooManagementSystem/private_eventBooking.php">Private Event</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Reservation List</button>
            <div class="dropdown-content">
                <a href="/ZooManagementSystem/publicreservationlist.php">Public Event Reservation List</a>
                <a href="/ZooManagementSystem/privatereservationlist.php">Private Event Reservation List</a>
            </div>
        </div>
        <a href="public/EventWebServices/generateqr.php">QR Code Generator</a>

        
        <?php 
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if( isset($_SESSION['currentUserModel']) ){
                echo '<a href="../../index.php?controller=user&action=showUserProfile" style="float: right">Profile</a>';
                echo '<a href="../../index.php?controller=user&action=logOut" style="float: right">Log Out</a>';
                echo '<p style="float: right; color: white; margin-top:15px;">Welcome, ' . $_SESSION['currentUserModel']['username'] . '</p>';
            }else{
                echo '
                <a href="index.php?controller=user&action=signUp" style="float: right">Sign Up</a>
                <a href="index.php?controller=user&action=login" style="float: right">Login</a>';
            }
        ?>
        
    </div>
</header>
<br></br><br></br><br></br>


<body style="font-family: Arial, sans-serif; ">
    <div style="max-width: 400px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="text-align: center;">QR Code Generator</h2>
        <form action="" method="POST">
            <div style="margin-bottom: 15px;">
                <label for="bookingid" style="display: block; margin-bottom: 5px;">Booking ID:</label>
                <input type="text" id="bookingid" name="bookingid" required style="width: 90%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label for="eventType" style="display: block; margin-bottom: 5px;">Event Type:</label>
                <select id="eventType" name="eventType" required style="width: 95%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">Select Event Type</option>
                    <option value="Public">Public</option>
                    <option value="Private">Private</option>
                </select>
            </div>
            <button type="submit" style="width: 100%; padding: 10px; background-color: #007bff; border: none; border-radius: 4px; color: #fff; font-size: 16px; cursor: pointer;">Generate QR Code</button>
        </form>
    </div>

    <?php

    if (isset($_SESSION['currentUserModel'])) {
        $currentUserModel = $_SESSION['currentUserModel'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookingid = $_POST['bookingid'] ?? '';
            $type = $_POST['eventType'] ?? '';
            $customerid = htmlspecialchars($currentUserModel['id']); // Fetch customer ID from session

            // Validate the inputs
            if (empty($bookingid) || empty($type)) {
                echo "<p style='color: red;'>Booking ID and Event Type are required.</p>";
            } else {
                // Construct the API URL
                $apiUrl = "http://127.0.0.1:5000/generate_qr";

                // Prepare data for POST request
                $data = [
                    'bookingid' => $bookingid,
                    'customerid' => $customerid, // Use session customer ID
                    'type' => $type
                ];

                // Initialize cURL
                $ch = curl_init($apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

                // Execute the request
                $response = curl_exec($ch);

                // Check for errors
                if ($response === FALSE) {
                    echo "<p style='color: red;'>Failed to retrieve QR code. Please try again later.</p>";
                } else {
                    // Decode JSON response
                    $decodedResponse = json_decode($response, true);
                    // Check if response contains an error message
                    if (isset($decodedResponse['error'])) {
                        echo "<p style='color: red; text-align: center;' >Error: " . htmlspecialchars($decodedResponse['error']) . "</p>";
                    } else {
                        // If no error, assume the response is a QR code image
                        echo '<h2 style="text-align: center;">Generated QR Code:</h2>';
                        echo '<div style="text-align: center;">';
                        echo '<img src="data:image/png;base64,' . base64_encode($response) . '" alt="QR Code" style="max-width: 100%; height: auto;">';
                        echo '</div>';
                    }
                }

                // Close cURL resource
                curl_close($ch);
            }
        }
    } else {
        echo '
<script type="text/javascript">
    alert("No user is logged in.");
        window.location.href = "../../index.php?controller=user&action=login";
</script>';
exit;
    }
    ?>
</body>
</html>
