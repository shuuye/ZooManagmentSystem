<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
    padding: 0;
}

form {
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin: 20px auto;
    padding: 20px;
    width: 80%;
    max-width: 800px;
}

h2 {
    color: #000;
    font-size: 24px;
    margin-bottom: 20px;
}

p {
    font-size: 14px;
}

p.error {
    color: red;
    font-weight: bold;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table th, table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
}

table th {
    background-color: #000;
    color: #fff;
}

table td {
    background-color: #f9f9f9;
}

input[type="number"], input[type="date"], input[type="submit"] {
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 8px;
    margin-top: 5px;
}

input[type="submit"] {
    background-color: #000;
    color: #fff;
    cursor: pointer;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #333;
}
</style>

<?php

class CustomerTicketView {

    public static function displayTickets($tickets, $errorMessage = '', $csrfToken = '') {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['currentUserModel'])) {
            $userModel = $_SESSION['currentUserModel'];
        } else {
            echo "No user is logged in.";
        }

        echo '<form method="POST" action="">';

        // Include CSRF token in the form
        echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrfToken) . '">';

        echo '<h2>Select Your Tickets</h2>';

        if (!empty($errorMessage)) {
            echo '<p style="color: red;">' . htmlspecialchars($errorMessage) . '</p>';
        }

        echo '<table>';
        echo '<tr><th>Type</th><th>Description</th><th>Price (RM)</th><th>Quantity</th></tr>';

        foreach ($tickets as $ticket) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($ticket['type']) . '</td>';
            echo '<td>' . htmlspecialchars($ticket['description']) . '</td>';
            echo '<td>' . htmlspecialchars($ticket['price']) . '</td>';
            echo '<td><input type="number" name="quantity[' . htmlspecialchars($ticket['id']) . ']" value="0" min="0" max="10"></td>';
            echo '</tr>';
        }

        echo '</table>';

        // Date input for selecting the zoo visit date
        echo '<label for="visit_date">Select Visit Date:</label>';
        echo '<input type="date" name="visit_date" required>';
        echo '<br><br>';

        echo '<input type="submit" value="Proceed to Payment">';
        echo '</form>';
    }
}

?>
