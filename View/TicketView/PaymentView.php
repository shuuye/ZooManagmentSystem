<<<<<<< HEAD
<?php

require_once __DIR__ . '/../../Model/Tickets/PaymentModel.php';

class PaymentView {

    public static function displayPaymentDetails($ticketPurchases, $totalPrice) {
        echo '<h2>Payment Details</h2>';
        echo '<table border="1">';
        echo '<tr><th>Ticket ID</th><th>Ticket Type</th><th>Quantity</th><th>Price (RM)</th><th>Total (RM)</th><th>Visit Date</th></tr>';

        foreach ($ticketPurchases->Ticket as $ticket) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($ticket['id']) . '</td>';
            echo '<td>' . htmlspecialchars($ticket->type) . '</td>';
            echo '<td>' . htmlspecialchars($ticket->quantity) . '</td>';
            echo '<td>' . htmlspecialchars($ticket->price) . '</td>';
            echo '<td>' . number_format((float) htmlspecialchars($ticket->total), 2, '.', ',') . '</td>';
            echo '<td>' . htmlspecialchars($ticket->visit_date) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        // Display the total price
        if (isset($_SESSION['userModel'])) {
            $userModel = $_SESSION['userModel'];
            $discount = isset($userModel['membership']['discountOffered']) ? $userModel['membership']['discountOffered'] : 0;
        }
        echo "<br><br> Membership Discount Offered: " . number_format($discount *100) . "% applied!";
        echo '<br><strong>Total Amount: RM ' . number_format($totalPrice, 2) . '</strong>';

        
        
        echo '
        <div id="paypal-button-container"></div>
        
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
                        window.location.href = "successPage.php?transaction_id=" + data.orderID;
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
}

?>


=======
<?php

require_once __DIR__ . '/../../Model/Tickets/PaymentModel.php';

class PaymentView {

    public static function displayPaymentDetails($ticketPurchases, $totalPrice) {
        echo '<h2>Payment Details</h2>';
        echo '<table border="1">';
        echo '<tr><th>Ticket ID</th><th>Ticket Type</th><th>Quantity</th><th>Price (RM)</th><th>Total (RM)</th><th>Visit Date</th></tr>';

        foreach ($ticketPurchases->Ticket as $ticket) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($ticket['id']) . '</td>';
            echo '<td>' . htmlspecialchars($ticket->type) . '</td>';
            echo '<td>' . htmlspecialchars($ticket->quantity) . '</td>';
            echo '<td>' . htmlspecialchars($ticket->price) . '</td>';
            echo '<td>' . number_format((float) htmlspecialchars($ticket->total), 2, '.', ',') . '</td>';
            echo '<td>' . htmlspecialchars($ticket->visit_date) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        // Display the total price
        if (isset($_SESSION['userModel'])) {
            $userModel = $_SESSION['userModel'];
            $discount = isset($userModel['membership']['discountOffered']) ? $userModel['membership']['discountOffered'] : 0;
        }
        echo "<br><br> Membership Discount Offered: " . number_format($discount *100) . "% applied!";
        echo '<br><strong>Total Amount: RM ' . number_format($totalPrice, 2) . '</strong>';

        
        
        echo '
        <div id="paypal-button-container"></div>
        
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
                        window.location.href = "successPage.php?transaction_id=" + data.orderID;
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
}

?>
>>>>>>> 86861c2b6c629367b744ebff4712aa9e4671daa7
