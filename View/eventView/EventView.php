<?php

class EventView {

    public function displayAllEvents($events, $selectedType, $errors = []) {
        // Ensure $selectedType defaults to "Public" if no filter is selected

        if ($selectedType === null) {
            $selectedType = "Public";
        }
        ?>
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <style>
                    .topnav {
                        overflow: hidden;
                        background-color: darkblue;
                        height: 30px;
                    }

                    .topnav a {
                        float: left;
                        color: #f2f2f2;
                        text-align: center;
                        padding: 5px 16px;
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

                    main {
                        margin: 0;
                        height: 70vh;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        background-color: #f4f4f4;
                    }

                    .container {
                        display: flex;
                        flex-wrap: wrap;
                        justify-content: space-between;
                        width: 850px;
                        text-align: center;
                    }

                    .link-box {
                        width: 850px;
                        height: 90px;
                        margin: 10px;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        background-color: lightblue;
                        text-decoration: none;
                        color: black;
                        font-size: 18pt;
                        border-radius: 8px;
                        transition: background-color 0.3s ease;
                    }

                    .link-box:hover {
                        background-color: darkblue;
                        color: white;
                    }
                </style>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="Css/eventDelete.css"> 
                <title>Events</title>
                <script>
                    function restrictCheckboxSelection() {
                        const checkboxes = document.querySelectorAll('.event-checkbox');
                        checkboxes.forEach(checkbox => {
                            checkbox.addEventListener('change', () => {
                                checkboxes.forEach(box => {
                                    if (box !== checkbox)
                                        box.checked = false;
                                });
                            });
                        });
                    }

                    function showSuccessMessage() {
                        alert('Events deleted successfully!');
                    }

                    function showNoEventMessage() {
                        alert('No events to be deleted!');
                    }

                    function clearURLParams() {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('success');
                        url.searchParams.delete('noEvent');
                        window.history.replaceState({}, '', url);
                    }

                    window.onload = function () {
                        restrictCheckboxSelection();
                        const urlParams = new URLSearchParams(window.location.search);
                        if (urlParams.has('success')) {
                            showSuccessMessage();
                            clearURLParams();
                        } else if (urlParams.has('noEvent')) {
                            showNoEventMessage();
                            clearURLParams();
                        }
                    };
                </script>
            </head>
            <header>
                <div class="topnav">
                    <a href="index.php?controller=admin&action=displayAdminMainPanel">Admin Main Panel</a>
                    <?php
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    if (isset($_SESSION['currentUserModel'])) {
                        echo '<a href="index.php?controller=user&action=logOut" style="float: right">Log Out</a>';
                        echo '<a href="index.php?controller=user&action=showUserProfile" style="float: right">Profile</a>';
                        echo '<p style="float: right; color: white; margin-top:7px;">Welcome, ' . $_SESSION['currentUserModel']['username'] . '</p>';
                    }
                    ?>
                </div>
            </header>
            <body>
                <h1>Events</h1>
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

                <button onclick="window.location.href = 'createanddeletefunction.php'" style="margin-left: 210px;">Back</button>   
                <br></br>
                <form method="POST" action="">
                    <button type="submit" name="filter" value="Public" <?php echo $selectedType === 'Public' ? 'class="selected"' : ''; ?>>Show Public Events</button>
                    <button type="submit" name="filter" value="Private" <?php echo $selectedType === 'Private' ? 'class="selected"' : ''; ?>>Show Private Events</button>
                </form>
                <form method="POST" action="deleteEvent.php">
                    <table>
                        <tr>
                            <th>Select</th>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Title</th>                       
                            <th>Location</th>
                            <?php if ($selectedType === 'Public') { ?>
                                <th>Price</th>
                                <th>Capacity</th>
                                <th>Date</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                            <?php } elseif ($selectedType === 'Private') { ?>
                                <th>Deposit</th>
                                <th>Number of Attendees</th>
                            <?php } ?>
                        </tr>
                        <?php foreach ($events as $event) { ?>
                            <?php if ($event['type'] === $selectedType) { ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="event_ids[]" value="<?php echo htmlspecialchars($event['id']); ?>" class="event-checkbox">
                                    </td>
                                    <?php if ($selectedType === 'Public') { ?>
                                        <td><?php echo htmlspecialchars($event['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($event['type'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($event['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($event['location'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($event['price'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($event['capacity'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($event['date'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($event['starttime'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($event['endtime'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <?php } elseif ($selectedType === 'Private') { ?>
                                        <td><?php echo htmlspecialchars($event['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($event['type'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($event['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($event['location'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($event['deposit'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($event['number_of_attendees'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <?php } ?>
                                </tr>                      
                            <?php } ?>
                        <?php } ?>
                    </table>
                    <br>
                    <input type="submit" name="delete_selected" value="Delete Event">   
                    <input type="hidden" id="type" name="type" value="<?php echo htmlspecialchars($selectedType, ENT_QUOTES, 'UTF-8'); ?>">
                </form>
            </body>
        </html>
        <?php
    }

    public function displayForm($errors = [], $type = 'Public', $title = '', $price = '', $date = '', $starttime = '', $endtime = '', $location = '', $description = '', $capacity = '', $deposit = '', $numberOfAttendees = '') {
        ?>
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <style>
                    .topnav {
                        overflow: hidden;
                        background-color: darkblue;
                        height: 30px;
                    }

                    .topnav a {
                        float: left;
                        color: #f2f2f2;
                        text-align: center;
                        padding: 5px 16px;
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

                    main {
                        margin: 0;
                        height: 70vh;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        background-color: #f4f4f4;
                    }

                    .container {
                        display: flex;
                        flex-wrap: wrap;
                        justify-content: space-between;
                        width: 850px;
                        text-align: center;
                    }

                    .link-box {
                        width: 850px;
                        height: 90px;
                        margin: 10px;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        background-color: lightblue;
                        text-decoration: none;
                        color: black;
                        font-size: 18pt;
                        border-radius: 8px;
                        transition: background-color 0.3s ease;
                    }

                    .link-box:hover {
                        background-color: darkblue;
                        color: white;
                    }
                </style>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Event Form</title>
                <link rel="stylesheet" href="Css/EventView.css"> 
                <script>
                    function updateFormFields() {
                        var eventType = document.getElementById('type').value;
                        var publicFields = document.getElementById('public-fields');
                        var privateFields = document.getElementById('private-fields');

                        publicFields.style.display = eventType === 'Public' ? 'block' : 'none';
                        privateFields.style.display = eventType === 'Private' ? 'block' : 'none';
                    }

                    // Initialize form fields based on default type
                    window.onload = function () {
                        updateFormFields(); // Ensure the function is called on page load
                    };
                </script>
            </head>
            <header>
                <div class="topnav">
                    <a href="index.php?controller=admin&action=displayAdminMainPanel">Admin Main Panel</a>
        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['currentUserModel'])) {
            echo '<a href="index.php?controller=user&action=logOut" style="float: right">Log Out</a>';
            echo '<a href="index.php?controller=user&action=showUserProfile" style="float: right">Profile</a>';
            echo '<p style="float: right; color: white; margin-top:7px;">Welcome, ' . $_SESSION['currentUserModel']['username'] . '</p>';
        }
        ?>
                </div>
            </header>
            <body>
                <h1>Create Event</h1>
                <button onclick="window.location.href = 'createanddeletefunction.php'"
                        style="
                        background-color: #4CAF50; /* Green background */
                        border: none; /* Remove default border */
                        color: white; /* White text */
                        padding: 10px 20px; /* Top and bottom padding, left and right padding */
                        text-align: center; /* Center text */
                        text-decoration: none; /* Remove underline */
                        display: inline-block; /* Align button in line with other elements */
                        font-size: 16px; /* Text size */
                        margin-left: 30px; /* Adjust left margin */
                        border-radius: 5px; /* Rounded corners */
                        cursor: pointer; /* Pointer cursor on hover */
                        transition: background-color 0.3s ease; /* Smooth transition for background color */
                        "           
                        >Back
                </button>

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

                <form method="POST" action="create_event.php">
                    <label for="type">Event Type:</label>
                    <select id="type" name="type" onchange="updateFormFields()">
                        <option value="Public" <?php echo $type === 'Public' ? 'selected' : ''; ?>>Public</option>
                        <option value="Private" <?php echo $type === 'Private' ? 'selected' : ''; ?>>Private</option>
                    </select>
                    <br><br>

                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>">
        <?php if (isset($errors['title']))
            echo '<p class="error">' . $errors['title'] . '</p>';
        ?>

                    <br><br>

                    <div id="public-fields" style="display: <?php echo $type === 'Public' ? 'block' : 'none'; ?>;">
                        <label for="price">Price:</label>
                        <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php if (isset($errors['price']))
                            echo '<p class="error">' . $errors['price'] . '</p>';
                        ?>
                        <br><br>

                        <label for="capacity">Capacity:</label>
                        <input type="text" id="capacity" name="capacity" value="<?php echo htmlspecialchars($capacity, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php if (isset($errors['capacity']))
                            echo '<p class="error">' . $errors['capacity'] . '</p>';
                        ?>
                        <br><br>

                        <label for="date">Date:</label>
                        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php if (isset($errors['date']))
                            echo '<p class="error">' . $errors['date'] . '</p>';
                        ?>
                        <br><br>

                        <label for="starttime">Start Time (24-hour format):</label>
                        <input type="text" id="starttime" name="starttime" value="<?php echo htmlspecialchars($starttime, ENT_QUOTES, 'UTF-8'); ?>" placeholder="HH:MM">
        <?php if (isset($errors['starttime']))
            echo '<p class="error">' . $errors['starttime'] . '</p>';
        ?>
                        <br><br>

                        <label for="endtime">End Time (24-hour format):</label>
                        <input type="text" id="endtime" name="endtime" value="<?php echo htmlspecialchars($endtime, ENT_QUOTES, 'UTF-8'); ?>" placeholder="HH:MM">
        <?php if (isset($errors['endtime']))
            echo '<p class="error">' . $errors['endtime'] . '</p>';
        ?>
                        <br><br>
                    </div>

                    <div id="private-fields" style="display: <?php echo $type === 'Private' ? 'block' : 'none'; ?>;">
                        <label for="deposit">Deposit:</label>
                        <input type="text" id="deposit" name="deposit" value="<?php echo htmlspecialchars($deposit, ENT_QUOTES, 'UTF-8'); ?>">
        <?php if (isset($errors['deposit']))
            echo '<p class="error">' . $errors['deposit'] . '</p>';
        ?>
                        <br><br>

                        <label for="number_of_attendees">Number of Attendees:</label>
                        <input type="text" id="number_of_attendees" name="number_of_attendees" value="<?php echo htmlspecialchars($numberOfAttendees, ENT_QUOTES, 'UTF-8'); ?>">
        <?php if (isset($errors['numberOfAttendees']))
            echo '<p class="error">' . $errors['numberOfAttendees'] . '</p>';
        ?>
                        <br><br>
                    </div>

                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($location, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php if (isset($errors['location']))
                        echo '<p class="error">' . $errors['location'] . '</p>';
                    ?>
                    <br><br>

                    <label for="description">Description:</label>
                    <textarea id="description" name="description"><?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?></textarea>
        <?php if (isset($errors['description']))
            echo '<p class="error">' . $errors['description'] . '</p>';
        ?>
                    <br><br>

                    <input type="submit" name="Create Event" value="Create Event">
                </form>
            </body>
        </html>
        <?php
    }

    public function displayEvent($event) {
        $type = htmlspecialchars($event->getType()); // Get the event type

        echo '<script type="text/javascript">
            alert("' . $type . ' Event Created Successfully");
            window.location.href = "create_event.php";
          </script>';
    }

    public function displayDeleteSuccessfulMessage($successMessage) {
        echo '<div class="success-message" style="
        background-color: #d4edda; /* Light green background for success */
        border: 1px solid #c3e6cb; /* Light green border */
        border-radius: 5px; /* Rounded corners */
        padding: 10px; /* Add some space inside the div */
        margin-bottom: 15px; /* Space below the success message */
        color: #155724; /* Dark green text color */
        font-size: 16px; /* Adjust font size */
        font-family: Arial, sans-serif; /* Font family */
    ">
        <p class="success" style="
            margin: 0; /* Remove default margin */
            padding: 0; /* Remove default padding */
            font-weight: bold; /* Make the text bold */
        ">' . htmlspecialchars($successMessage) . '</p>
    </div>';
    }

    public function displayError($errorMessage) {
        echo '<p class="error">' . htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') . '</p>';
    }

    public function displayNavigation() {
        ?>
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Navigation</title>
                <style>
                    /* Basic Reset */
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
                    .topnav {
                        overflow: hidden;
                        background-color: darkblue;
                        height: 30px;
                    }

                    .topnav a {
                        float: left;
                        color: #f2f2f2;
                        text-align: center;
                        padding: 5px 16px;
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
                </style>
            </head>

            <header>
                <div class="topnav">
                    <a href="index.php?controller=admin&action=displayAdminMainPanel">Admin Main Panel</a>
                    <?php
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    if (isset($_SESSION['currentUserModel'])) {
                        echo '<a href="index.php?controller=user&action=logOut" style="float: right">Log Out</a>';
                        echo '<a href="index.php?controller=user&action=showUserProfile" style="float: right">Profile</a>';
                        echo '<p style="float: right; color: white; margin-top:7px;">Welcome, ' . $_SESSION['currentUserModel']['username'] . '</p>';
                    }
                    ?>
                </div>

                <!--place link-->
                <div class="topnav" style="background-color: blue;">
                    <a href="createanddeletefunction.php" class="link-box">Event Management</a>
                    <a href="index.php?controller=user&action=userManagementMainPanel" class="link-box">User Management</a>
                    <a href="index.php?controller=inventory&action=index" class="link-box">Inventory Management Panel</a>
        <?php
        if (isset($_SESSION['currentUserModel']) && $_SESSION['currentUserModel']['role']['roleID'] == 1 && !in_array('edit', $_SESSION['currentUserModel']['permissions'])) {
            echo'<a href="/ZooManagementSystem/displayTicketsXslt.php" class="link-box">Ticketing & Payment Management Panel</a>';
        } else {
            echo'<a href="/ZooManagementSystem/adminTicketPage.php" class="link-box">Ticketing & Payment Management Panel</a>';
        }
        ?>
                    <a href="/ZooManagementSystem/View/AnimalView/animal_home.php" class="link-box">Animal Management Panel</a>
                </div>
            </header>


            <body>       
                <!-- Navigation Buttons -->
                <div class="navigation-container">
                    <div class="nav-box"><a href="deleteEvent.php">Delete Event</a></div>
                    <div class="nav-box"><a href="create_event.php">Create Event</a></div>
                    <div class="nav-box"><a href="displayPublicEventReport.php">Public Event Reports</a></div>
                    <div class="nav-box"><a href="displayPrivateEventReport.php">Private Event Reports</a></div>
                </div>
            </body>
        </html>
        <?php
    }

    public function displayPrivateEventReport() {
        ?>
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <style>
                    /* Basic Reset */
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
                    .topnav {
                        overflow: hidden;
                        background-color: darkblue;
                        height: 30px;
                    }

                    .topnav a {
                        float: left;
                        color: #f2f2f2;
                        text-align: center;
                        padding: 5px 16px;
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
                </style>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="Css/navigation.css">
                <title>Public Event Report</title>
            </head>
            <header>
                <div class="topnav">
                    <a href="index.php?controller=admin&action=displayAdminMainPanel">Admin Main Panel</a>
                    <?php
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    if (isset($_SESSION['currentUserModel'])) {
                        echo '<a href="index.php?controller=user&action=logOut" style="float: right">Log Out</a>';
                        echo '<a href="index.php?controller=user&action=showUserProfile" style="float: right">Profile</a>';
                        echo '<p style="float: right; color: white; margin-top:7px;">Welcome, ' . $_SESSION['currentUserModel']['username'] . '</p>';
                    }
                    ?>
                </div>  
            </header>

            <body>   
                <div class="navigation-container">
                    <div class="nav-box"><a href="createanddeletefunction.php">Back</a></div>
                    <div class="nav-box"><a href="Model/Xml/displayprivateeventsummaryreport1.php">Display Report by Monthly Bookings</a></div>
                    <div class="nav-box"><a href="Model/Xml/displayprivateeventsummaryreport2.php">Display Report by EventID</a></div>
                    <div class="nav-box"><a href="Model/Xml/displayprivateeventsummaryreport3.php">Display Report by Location </a></div>
                </div>          
            </body>
        </html>
        <?php
    }

    public function displayPublicEventReport() {
        ?>
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <style>
                    /* Basic Reset */
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
                    .topnav {
                        overflow: hidden;
                        background-color: darkblue;
                        height: 30px;
                    }

                    .topnav a {
                        float: left;
                        color: #f2f2f2;
                        text-align: center;
                        padding: 5px 16px;
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
                </style>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="Css/navigation.css">
                <title>Public Event Report</title>
            </head>
            <header>
                <div class="topnav">
                    <a href="index.php?controller=admin&action=displayAdminMainPanel">Admin Main Panel</a>
        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['currentUserModel'])) {
            echo '<a href="index.php?controller=user&action=logOut" style="float: right">Log Out</a>';
            echo '<a href="index.php?controller=user&action=showUserProfile" style="float: right">Profile</a>';
            echo '<p style="float: right; color: white; margin-top:7px;">Welcome, ' . $_SESSION['currentUserModel']['username'] . '</p>';
        }
        ?>
                </div>  
            </header>

            <body>      
                <div class="navigation-container">
                    <div class="nav-box"><a href="createanddeletefunction.php">Back</a></div>
                    <div class="nav-box"><a href="Model/Xml/displaypubliceventsummaryreport1.php">Display Report by Total Earnings </a></div>
                    <div class="nav-box"><a href="Model/Xml/displaypubliceventsummaryreport2.php">Display Report by Ticket Sales</a></div>
                    <div class="nav-box"><a href="Model/Xml/displaypubliceventsummaryreport3.php">Display Report by Monthly Bookings</a></div>
                </div>       
            </body>
        </html>
        <?php
    }
}
?>
