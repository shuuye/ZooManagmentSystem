<?php
class eventBookingView {
    public function displayPublicEventsForm($events, $errors = []) {
        ?>
        <script>
            function restrictCheckboxSelection() {
                const checkboxes = document.querySelectorAll('.event-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', () => {
                        // Uncheck all checkboxes except the one that was just clicked
                        checkboxes.forEach(box => {
                            if (box !== checkbox) box.checked = false;
                        });
                    });
                });
            }
            document.addEventListener('DOMContentLoaded', restrictCheckboxSelection);
            </script>
            
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
            <title>Select Tickets for Public Events</title>
            <link rel="stylesheet" href="Css/eventView.css"> <!-- Your CSS file -->
        </head>
        <header>
    <div class="topnav">
        <a href="index.php"><img src="assests/Logo-Zoo-Negara.png" alt="Zoo Negara Home Page" width="60px" height="50px"/></a>
        <a href="index.php?controller=user&action=showMembership">Membership</a>
        <a href="#contact">Contact</a>
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

        <body>
            <h1>Select Public Event</h1>
            <?php if (!empty($errors)) : ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error) : ?>
                        <p class="error"><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
                  

            <form method="GET" action="public_eventBooking.php">
                <table>
                    <tr>
                        <th>Select</th>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Title</th>
                        <th>Description</th>
                    </tr>
                    <?php foreach ($events as $event) : ?>
                        <?php if ($event['type'] === 'Public') : ?>
                            <tr>
                                <td>
                                   <input type="checkbox" name="event_ids[]" value="<?php echo htmlspecialchars($event['id'] , ENT_QUOTES, 'UTF-8'); ?>" class="event-checkbox">
                                </td>
                                <td><?php echo htmlspecialchars($event['id'] , ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($event['type'] , ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($event['title'] , ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($event['description'] , ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </table>
                <br>
                <input type="submit" value="View Details">
            </form>
        </body>
        </html>
        <?php
    }

    public function displayEventDetailsForm($event, $errors = []) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Event Details</title>
        <link rel="stylesheet" href="Css/eventView.css"> <!-- Your CSS file -->
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
        <script>
            function calculateTotalPrice() {
                var price = parseFloat(document.getElementById('price').innerText.replace('$', '').replace(',', ''));
                var numberOfTickets = parseInt(document.getElementById('ticket_number').value, 10);
                var totalPriceElement = document.getElementById('total_price');
                var totalPriceInput = document.getElementById('totalprice');

                if (isNaN(price) || isNaN(numberOfTickets) || numberOfTickets <= 0) {
                    totalPriceElement.innerText = 'Total Price: $0.00';
                    totalPriceInput.value = '0.00';
                    return;
                }

                var totalPrice = price * numberOfTickets;
                totalPriceElement.innerText = 'Total Price: $' + totalPrice.toFixed(2);
                totalPriceInput.value = totalPrice.toFixed(2);
            }
        </script>
    </head>
  <header>
    <div class="topnav">
        <a href="index.php"><img src="assests/Logo-Zoo-Negara.png" alt="Zoo Negara Home Page" width="60px" height="50px"/></a>
        <a href="index.php?controller=user&action=showMembership">Membership</a>
        <a href="#contact">Contact</a>
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

    <body>
        <h1>Event Details</h1>
         <?php if (!empty($errors)) : ?>
    <div class="error-messages" style="
        background-color: #f8d7da; /* Light red background for errors */
        border: 1px solid #f5c6cb; /* Light red border */
        border-radius: 5px; /* Rounded corners */
        padding: 10px; /* Add some space inside the div */
        margin-bottom: 15px; /* Space below the error message */
        color: #721c24; /* Dark red text color */
        font-size: 16px; /* Adjust font size */
        font-family: Arial, sans-serif; /* Font family */
    ">
        <?php if (isset($errors['duplicate'])) : ?>
            <p class="error" style="
                margin: 0; /* Remove default margin */
                padding: 0; /* Remove default padding */
                font-weight: bold; /* Make the text bold */
            "><?php echo htmlspecialchars($errors['duplicate']); ?></p>
        <?php endif; ?>
    </div>
<?php endif; ?>
               <div style='margin-left: 30px;text-align: left; margin-top: 20px;'>
    <button onclick="history.back()" style='padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; border: none; border-radius: 5px; cursor: pointer;'>Back</button>
</div>

        <table>
            <tr>
                <th>ID</th>
                <td><?php echo htmlspecialchars($event['event_id'] , ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
                <th>Type</th>
                <td><?php echo htmlspecialchars($event['type'] , ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
                <th>Title</th>
                <td><?php echo htmlspecialchars($event['title'] , ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
                <th>Location</th>
                <td><?php echo htmlspecialchars($event['location'] , ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
                <th>Price</th>
                <td id="price"><?php echo htmlspecialchars($event['price'] , ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
                <th>Date</th>
                <td><?php echo htmlspecialchars($event['date'] , ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
                <th>Start Time</th>
                <td><?php echo htmlspecialchars($event['starttime'] , ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
                <th>End Time</th>
                <td><?php echo htmlspecialchars($event['endtime'] , ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
        </table>

        <form method="POST" action="public_eventBooking.php">
            <label for="number_of_tickets">Number of Tickets:</label>
            <input type="number" id="ticket_number" name="ticket_number" min="1" required oninput="calculateTotalPrice()">
            <br><br>
            <div id="total_price">Total Price: $0.00</div>
            <br>
            <input type="submit" value="Reserve Tickets">
            <input type="hidden" id="event_id" name="event_id" value="<?php echo htmlspecialchars($event['event_id']); ?>">    
            <input type="hidden" id="type" name="type" value="<?php echo htmlspecialchars($event['type']); ?>"> 
            <input type="hidden" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>"> 
            <input type="hidden" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>"> 
            <input type="hidden" id="price" name="price" value="<?php echo htmlspecialchars($event['price']); ?>">              
            <input type="hidden" id="date" name="date" value="<?php echo htmlspecialchars($event['date']); ?>"> 
            <input type="hidden" id="starttime" name="starttime" value="<?php echo htmlspecialchars($event['starttime']); ?>">
            <input type="hidden" id="endtime" name="endtime" value="<?php echo htmlspecialchars($event['endtime']); ?>">            
            <input type="hidden" id="totalprice" name="totalprice" value="0.00">
        </form>
    </body>
    </html>
    <?php
}


    public function displayError($message) {
        echo "<p class='error'>" . htmlspecialchars($message , ENT_QUOTES, 'UTF-8') . "</p>";
    }

    public function displaySuccess($message) {
        echo "<p class='success'>" . htmlspecialchars($message , ENT_QUOTES, 'UTF-8') . "</p>";
    }
    
    public function displayPrivateEventsForm($events, $errors = []) {
        ?>
     <script>
            function restrictCheckboxSelection() {
                const checkboxes = document.querySelectorAll('.event-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', () => {
                        // Uncheck all checkboxes except the one that was just clicked
                        checkboxes.forEach(box => {
                            if (box !== checkbox) box.checked = false;
                        });
                    });
                });
            } 
            document.addEventListener('DOMContentLoaded', restrictCheckboxSelection);
            </script>
        <!DOCTYPE html>
        <html lang="en">
        <head>
             <style>
            /* Basic Reset */
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
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
            }
            .navigation-container {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 20px;
                padding: 20px;
            }
            .nav-box {
                width: 200px;
                height: 100px;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #4CAF50;
                color: white;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                text-align: center;
                transition: background-color 0.3s;
                text-decoration: none;
            }
            .nav-box a {
                color: white;
                text-decoration: none;
                font-size: 18px;
            }
            .nav-box:hover {
                background-color: #45a049;
            }
        </style>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Select Tickets for Public Events</title>
            <link rel="stylesheet" href="Css/eventView.css"> 
        </head>
         <header>
    <div class="topnav">
        <a href="index.php"><img src="assests/Logo-Zoo-Negara.png" alt="Zoo Negara Home Page" width="60px" height="50px"/></a>
        <a href="index.php?controller=user&action=showMembership">Membership</a>
        <a href="#contact">Contact</a>
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

        <body>
            <h1>Select Private Event</h1>
            <?php if (!empty($errors)) : ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error) : ?>
                        <p class="error"><?php echo htmlspecialchars($error , ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>           

            <form method="GET" action="private_eventBooking.php">
                <table>
                    <tr>
                        <th>Select</th>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Title</th>
                        <th>Description</th>
                    </tr>
                    <?php foreach ($events as $event) : ?>
                        <?php if ($event['type'] === 'Private') : ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="event_ids[]" value="<?php echo htmlspecialchars($event['id']); ?>" class="event-checkbox">
                                </td>
                                <td><?php echo htmlspecialchars($event['id'] , ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($event['type'] , ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($event['title'] , ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($event['description'] , ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </table>
                <br>
                <input type="submit" value="View Details">
            </form>
        </body>
        </html>
        <?php
    }
    
    public function displayPrivateEventDetailsForm($event, $errors = []) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
            <style>
            /* Basic Reset */
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
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
            }
            .navigation-container {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 20px;
                padding: 20px;
            }
            .nav-box {
                width: 200px;
                height: 100px;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #4CAF50;
                color: white;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                text-align: center;
                transition: background-color 0.3s;
                text-decoration: none;
            }
            .nav-box a {
                color: white;
                text-decoration: none;
                font-size: 18px;
            }
            .nav-box:hover {
                background-color: #45a049;
            }
        </style>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Event Details</title>
        <link rel="stylesheet" href="Css/eventView.css"> <!-- Your CSS file -->
    </head>
<header>
    <div class="topnav">
        <a href="index.php"><img src="assests/Logo-Zoo-Negara.png" alt="Zoo Negara Home Page" width="60px" height="50px"/></a>
        <a href="index.php?controller=user&action=showMembership">Membership</a>
        <a href="#contact">Contact</a>
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

    <body>
        <h1>Private Event Booking</h1>       
         <?php if (!empty($errors)) : ?>
    <div class="error-messages" style="
        background-color: #f8d7da; /* Light red background for errors */
        border: 1px solid #f5c6cb; /* Light red border */
        border-radius: 5px; /* Rounded corners */
        padding: 10px; /* Add some space inside the div */
        margin-bottom: 15px; /* Space below the error message */
        color: #721c24; /* Dark red text color */
        font-size: 16px; /* Adjust font size */
        font-family: Arial, sans-serif; /* Font family */
    ">
        <?php if (isset($errors['duplicate'])) : ?>
            <p class="error" style="
                margin: 0; /* Remove default margin */
                padding: 0; /* Remove default padding */
                font-weight: bold; /* Make the text bold */
            "><?php echo htmlspecialchars($errors['duplicate']); ?></p>
        <?php endif; ?>
    </div>
<?php endif; ?>
        <div style='margin-left: 30px;text-align: left; margin-top: 20px;'>
    <button onclick="history.back()" style='padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; border: none; border-radius: 5px; cursor: pointer;'>Back</button>
</div>

        <form method="POST" action="private_eventBooking.php">
            <table>
                <tr>
                    <th>ID</th>
                    <td><?php echo htmlspecialchars($event['event_id'] , ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td><?php echo htmlspecialchars($event['type'] , ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                    <th>Title</th>
                    <td><?php echo htmlspecialchars($event['title'] , ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                    <th>Location</th>
                    <td><?php echo htmlspecialchars($event['location'] , ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                    <th>Deposit</th>
                    <td><?php echo htmlspecialchars($event['deposit'] , ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <th>Maximum number of Attendees</th>
                    <td><?php echo htmlspecialchars($event['number_of_attendees'] , ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>
                        <input type="date" id="date" name="date">                       
                    <?php if (isset($errors['date'])) 
                    echo '<p class="error">' . $errors['date'] . '</p>'; ?>
                    </td>
                </tr>
                <tr>
                    <th>Start Time</th>
                    <td>
                        <input type="text" id="starttime" name="starttime" placeholder="HH:MM">
                        <?php if (isset($errors['starttime'] )) 
                    echo '<p class="error">' . $errors['starttime'] . '</p>'; ?>
                    </td>
                </tr>
                <tr>
                    <th>End Time</th>
                    <td>
                       <input type="text" id="endtime" name="endtime" placeholder="HH:MM">
                        <?php if (isset($errors['endtime'])) 
                    echo '<p class="error">' . $errors['endtime'] . '</p>'; ?>
                    </td>
                </tr>
                <tr>
                    <th>Number of Attendees:</th>
                    <td>
                        <input type="text" id="numberofattendees" name="numberofattendees">
                    <?php if (isset($errors['numberofattendees'])) 
                        echo '<p class="error">' . $errors['numberofattendees'] . '</p>'; ?>
                    </td>
                </tr>
            </table>
            <br><br>
            <input type="submit" value="Book Private Event">

            <!-- Hidden fields to keep other data (non-editable) -->
            <input type="hidden" id="event_id" name="event_id" value="<?php echo htmlspecialchars($event['event_id'] , ENT_QUOTES, 'UTF-8'); ?>">    
            <input type="hidden" id="type" name="type" value="<?php echo htmlspecialchars($event['type'] , ENT_QUOTES, 'UTF-8'); ?>"> 
            <input type="hidden" id="title" name="title" value="<?php echo htmlspecialchars($event['title'] , ENT_QUOTES, 'UTF-8'); ?>"> 
            <input type="hidden" id="location" name="location" value="<?php echo htmlspecialchars($event['location'] , ENT_QUOTES, 'UTF-8'); ?>"> 
            <input type="hidden" id="deposit" name="deposit" value="<?php echo htmlspecialchars($event['deposit'] , ENT_QUOTES, 'UTF-8'); ?>"> 
            <input type="hidden" id="number_of_attendees" name="number_of_attendees" value="<?php echo htmlspecialchars($event['number_of_attendees'] , ENT_QUOTES, 'UTF-8'); ?>"> 
        </form>
    </body>
    </html>
    <?php
}
public function displayPublicBookingSummary($customerid, $fullname, $eventId, $ticket_number, $type, $title, $location, $price, $date, $starttime, $endtime) {
     ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
            <style>
            /* Basic Reset */
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
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
            }
            .navigation-container {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 20px;
                padding: 20px;
            }
            .nav-box {
                width: 200px;
                height: 100px;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #4CAF50;
                color: white;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                text-align: center;
                transition: background-color 0.3s;
                text-decoration: none;
            }
            .nav-box a {
                color: white;
                text-decoration: none;
                font-size: 18px;
            }
            .nav-box:hover {
                background-color: #45a049;
            }
        </style>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Event Details</title>
        <link rel="stylesheet" href="Css/eventView.css"> <!-- Your CSS file -->
    </head>
    <header>
    <div class="topnav">
        <a href="index.php"><img src="assests/Logo-Zoo-Negara.png" alt="Zoo Negara Home Page" width="60px" height="50px"/></a>
        <a href="index.php?controller=user&action=showMembership">Membership</a>
        <a href="#contact">Contact</a>
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
    </html>
    <?php
    echo "
    <div style='margin-left: 30px; text-align: left; margin-top: 20px;'>
        <button onclick='history.back()' style='padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; border: none; border-radius: 5px; cursor: pointer;'>Back</button>
    </div>
    ";

    // Use htmlspecialchars on actual variable values
    $price = htmlspecialchars($price, ENT_QUOTES, 'UTF-8');
    $ticket_number = htmlspecialchars($ticket_number, ENT_QUOTES, 'UTF-8');
    $totalPrice = $price * $ticket_number;

    // Display booking summary
    echo "
    <div style='width: 80%; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
        <h2 style='text-align: center; color: #333;'>Booking Summary</h2>
        <p style='font-size: 16px; color: #555;'>Customer ID: <strong>" . htmlspecialchars($customerid, ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>Customer Name: <strong>" . htmlspecialchars($fullname, ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>Event Title: <strong>" . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>Event Type: <strong>" . htmlspecialchars($type, ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>Event ID: <strong>" . htmlspecialchars($eventId, ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>Date: <strong>" . htmlspecialchars($date, ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>Start Time: <strong>" . htmlspecialchars($starttime, ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>End Time: <strong>" . htmlspecialchars($endtime, ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>Location: <strong>" . htmlspecialchars($location, ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>Price: <strong>" . htmlspecialchars($price, ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>Ticket Number: <strong>" . htmlspecialchars($ticket_number, ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>Total Price: <strong>" . htmlspecialchars(number_format((float)$totalPrice, 2, '.', ''), ENT_QUOTES, 'UTF-8') . "</strong></p>       
    </div>
    ";

    // PayPal integration
    echo '
    <div id="paypal-button-container" style="display: flex; justify-content: center; align-items: center; margin-top: 20px;padding-left: 250px"></div> 
    
    <script src="https://www.paypal.com/sdk/js?client-id=AczgOTTbFBN6U6nDNgzw1lpnYtku9tybmaxJxKsjX-p3Z0xLb90D4WGknaLf6Q6Es-ZfunPpjV3pk-8G&currency=MYR"></script>
    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: "' . number_format((float)$totalPrice, 2, '.', '') . '"
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert("Transaction completed by " + details.payer.name.given_name);
                    // Redirect to success page or handle the success case
                    window.location.href = "index.php?status=success";
                });
            },
            onError: function(err) {
                console.error(err);
                // Handle error case
                alert("Payment could not be processed. Please try again.");
            }
        }).render("#paypal-button-container");
    </script>';
}

public function displayPrivateBookingSummary($booking) {  
     ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
            <style>
            /* Basic Reset */
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
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
            }
            .navigation-container {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 20px;
                padding: 20px;
            }
            .nav-box {
                width: 200px;
                height: 100px;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #4CAF50;
                color: white;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                text-align: center;
                transition: background-color 0.3s;
                text-decoration: none;
            }
            .nav-box a {
                color: white;
                text-decoration: none;
                font-size: 18px;
            }
            .nav-box:hover {
                background-color: #45a049;
            }
        </style>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Event Details</title>
        <link rel="stylesheet" href="Css/eventView.css"> <!-- Your CSS file -->
    </head>
       <header>
    <div class="topnav">
        <a href="index.php"><img src="assests/Logo-Zoo-Negara.png" alt="Zoo Negara Home Page" width="60px" height="50px"/></a>
        <a href="index.php?controller=user&action=showMembership">Membership</a>
        <a href="#contact">Contact</a>
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
    </html>
    <?php
    echo "
    <div style='margin-left: 30px; text-align: left; margin-top: 20px;'>
        <button onclick='history.back()' style='padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; border: none; border-radius: 5px; cursor: pointer;'>Back</button>
    </div>
    ";
    $totalPrice = htmlspecialchars($booking['deposit'], ENT_QUOTES, 'UTF-8');
    echo "
    <div style='width: 60%; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
        <h2 style='text-align: center; color: #333;'>Booking Summary</h2>
        <p style='font-size: 16px; color: #555;'>Customer ID: <strong>" . htmlspecialchars($booking['customerid'] , ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>Customer Name: <strong>" . htmlspecialchars($booking['fullname'] , ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>Event Title: <strong>" . htmlspecialchars($booking['title'] , ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>Date: <strong>" . htmlspecialchars($booking['date'] , ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>Start Time: <strong>" . htmlspecialchars($booking['starttime'] , ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>End Time: <strong>" . htmlspecialchars($booking['endtime'] , ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>Location: <strong>" . htmlspecialchars($booking['location'] , ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>Deposit: <strong>" . htmlspecialchars($booking['deposit'] , ENT_QUOTES, 'UTF-8') . "</strong></p>
        <p style='font-size: 16px; color: #555;'>Number of Attendees: <strong>" . htmlspecialchars($booking['numberofattendees'] , ENT_QUOTES, 'UTF-8') . "</strong></p>       
    <br></br>";   
 
     echo '
        <div id="paypal-button-container" style="display: flex; justify-content: center; align-items: center; margin-top: 20px;"></div>        
        <script src="https://www.paypal.com/sdk/js?client-id=AczgOTTbFBN6U6nDNgzw1lpnYtku9tybmaxJxKsjX-p3Z0xLb90D4WGknaLf6Q6Es-ZfunPpjV3pk-8G&currency=MYR"></script>
        <script>
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: "' . number_format((float)$totalPrice, 2, '.', '') . '"
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        alert("Transaction completed by " + details.payer.name.given_name);
                        // Redirect to success page or handle the success case
                        window.location.href = "index.php? + successful payment"
                    });
                },
                onError: function(err) {
                    console.error(err);
                    // Handle error case
                    alert("Payment could not be processed. Please try again.");
                }
            }).render("#paypal-button-container");
        </script>';    
}

public function displayAllPrivateEventReservation($bookings) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="Css/eventView.css">
        <style>
            /* Adding some basic styles to ensure the table displays correctly */
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: lightblue;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            tr:hover {
                background-color: #f1f1f1;
            }
            button {
                padding: 10px 20px;
                font-size: 16px;
                color: #fff;
                background-color: #007bff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                margin: 20px 0;
            }
                .topnav {
        overflow: hidden;
        background-color: #94AF10;;
      }

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
    </head>
   <header>
    <div class="topnav">
        <a href="index.php"><img src="assests/Logo-Zoo-Negara.png" alt="Zoo Negara Home Page" width="60px" height="50px"/></a>
        <a href="index.php?controller=user&action=showMembership">Membership</a>
        <a href="#contact">Contact</a>
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
    <body>
        <h1>Reservation List for Private Event</h1>
        <div style='margin-left: 30px;text-align: left; margin-top: 20px;'>
            <button onclick="history.back()">Back</button>
        </div>

        <form method="GET" action="privatereservationlist.php">
            <table>
                <tr>
                    <th>Booking ID</th>
                    <th>Event ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Deposit Paid</th>
                    <th>Number Of Attendees</th>
                </tr>
                <?php if (is_array($bookings) && !empty($bookings)) : ?>
                    <?php foreach ($bookings as $booking) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['bookingid'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($booking['event_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($booking['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($booking['type'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($booking['starttime'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($booking['endtime'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($booking['date'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($booking['location'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($booking['deposit'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($booking['numberofattendees'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="10">No bookings found.</td>
                    </tr>
                <?php endif; ?>
            </table>
            <br>
        </form>
    </body>
    </html>
    <?php
}


public function displayAllPublicEventReservation($bookings) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="Css/eventView.css"> 
        <style>
            /* Adding some basic styles to ensure the table displays correctly */
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: lightblue;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            tr:hover {
                background-color: #f1f1f1;
            }
            button {
                padding: 10px 20px;
                font-size: 16px;
                color: #fff;
                background-color: #007bff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
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
    </head>
<header>
    <div class="topnav">
        <a href="index.php"><img src="assests/Logo-Zoo-Negara.png" alt="Zoo Negara Home Page" width="60px" height="50px"/></a>
        <a href="index.php?controller=user&action=showMembership">Membership</a>
        <a href="#contact">Contact</a>
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
    <body>
        <h1>Reservation List for Public Event</h1>
        <div style='margin-left: 30px; text-align: left; margin-top: 20px;'>
            <button onclick="history.back()">Back</button>
        </div>
        <form method="GET" action="publicreservationlist.php">
            <table>
                <tr>
                    <th>Booking ID</th>
                    <th>Event ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Total Price</th>
                    <th>Ticket Number</th>
                </tr>
                <?php if (is_array($bookings) && !empty($bookings)) : ?>
                    <?php foreach ($bookings as $booking) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['bookingid'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($booking['event_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($booking['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($booking['type'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($booking['starttime'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($booking['endtime'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($booking['date'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($booking['location'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($booking['totalprice'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($booking['ticket_number'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="10">No bookings found.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </form>
    </body>
    </html>
    <?php
}  
}

?>
