<!--Author name: Vanness Chaw Jun Kit -->
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

    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background-color: #f9f9f9;
        color: #333;
    }

    h2 {
        text-align: center;
        color: #000;
    }

    table {
        width: 100%;
        margin: 20px auto;
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid #000;
    }

    th, td {
        padding: 12px;
        text-align: center;
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

    .form-container {
        margin: 20px auto;
        width: 50%;
        padding: 20px;
        border: 1px solid #000;
        background-color: #fff;
    }

    label, input, select {
        display: block;
        width: 100%;
        margin-bottom: 10px;
        padding: 10px;
        font-size: 16px;
    }

    input[type="submit"] {
        background-color: #000;
        color: white;
        border: none;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #fff;
        color: #000;
        border: 1px solid #000;
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

<?php

class AdminTicketView {

    public static function render($tickets, $errorMessage = '') {
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="UTF-8">
                <title>Admin Ticket Management</title>
                <link rel="stylesheet" type="text/css" href="../Css/TicketManagement.css">
            </head>
            <body>
                <div class="header">
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
                        <a href="View/AnimalView/animal_home.php" class="link-box">Animal Management Panel</a>
                    </div>
                    <form method="post" action="index.php" style="display:inline;">
                        <input type="submit" name="Back" value="Back">
                    </form>

                    <h2>Manage Tickets</h2>
                </div>

                <form method="post">
                    <label for="action">Choose action:</label>
                    <select name="action" id="action">
                        <option value="Add">Add Ticket</option>
                        <option value="Edit">Edit Ticket</option>
                        <option value="Delete">Delete Ticket</option>
                    </select>
                    <input type="submit" value="Submit">
                </form>

                <?php if (!empty($tickets)) : ?>
                    <h2>Tickets List</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tickets as $ticket) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($ticket['id']); ?></td>
                                    <td><?php echo htmlspecialchars($ticket['type']); ?></td>
                                    <td><?php echo htmlspecialchars($ticket['description']); ?></td>
                                    <td><?php echo htmlspecialchars($ticket['price']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No tickets found. The table is empty.</p>
                <?php endif; ?>

                <div id="form-container">
                    <?php if (isset($_POST['action'])) : ?>
                        <?php if ($_POST['action'] == 'Add') : ?>
                            <h2>Add Ticket</h2>
                            <form method="post">
                                <input type="hidden" name="action" value="Add">
                                <label for="type">Type:</label>
                                <input type="text" name="type" id="type" required>
                                <label for="description">Description:</label>
                                <input type="text" name="description" id="description" required>
                                <label for="price">Price:</label>
                                <input type="number" name="price" id="price" required>
                                <input type="submit" value="Add Ticket">
                            </form>
                        <?php elseif ($_POST['action'] == 'Edit') : ?>
                            <h2>Edit Ticket</h2>
                            <form method="post">
                                <input type="hidden" name="action" value="Edit">
                                <label for="id">ID:</label>
                                <input type="number" name="id" id="id" required>
                                <label for="type">Type:</label>
                                <input type="text" name="type" id="type" required>
                                <label for="description">Description:</label>
                                <input type="text" name="description" id="description" required>
                                <label for="price">Price:</label>
                                <input type="number" name="price" id="price" required>
                                <input type="submit" value="Update Ticket">
                            </form>
                        <?php elseif ($_POST['action'] == 'Delete') : ?>
                            <h2>Delete Ticket</h2>
                            <form method="post">
                                <input type="hidden" name="action" value="Delete">
                                <label for="id">ID:</label>
                                <input type="number" name="id" id="id" required>
                                <input type="submit" value="Delete Ticket">
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($errorMessage) : ?>
                        <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
                    <?php endif; ?>
                </div>
            </body>
        </html>
        <?php
    }
}
?>
