<?php

class CustomerTicketView {

    public static function displayTickets($tickets, $errorMessage = '', $csrfToken = '') {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['currentUserModel'])) {
            $userModel = $_SESSION['currentUserModel'];

            // Display user details
            echo "Welcome, " . htmlspecialchars($userModel['fullName']) . "!";
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
