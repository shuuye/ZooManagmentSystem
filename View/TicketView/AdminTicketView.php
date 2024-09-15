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
                <form method="post" action="index.php" style="display:inline;">
                    <input type="submit" name="logout" value="Logout">
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
