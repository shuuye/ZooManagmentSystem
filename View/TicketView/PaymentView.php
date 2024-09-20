<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        margin: 0;
        padding: 0;
    }

    h2 {
        color: #000;
        font-size: 24px;
        margin: 20px 0;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px auto;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    table th, table td {
        padding: 15px;
        text-align: left;
    }

    table th {
        background-color: #000;
        color: #fff;
        text-transform: uppercase;
        font-weight: bold;
    }

    table td {
        background-color: #f9f9f9;
        color: #333;
    }

    table tr:nth-child(even) td {
        background-color: #efefef;
    }

    table tr:hover td {
        background-color: #d3d3d3;
    }

    /* Center alignment for discount and total price */
    .payment-summary {
        text-align: center;
        margin: 20px 0;
        font-size: 18px;
    }

    .payment-summary strong {
        font-weight: bold;
        color: #000;
    }

    #paypal-button-container {
        margin: 20px auto;
        width: 50%;
        text-align: center;
        padding: 20px;
        background-color: #000;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    #paypal-button-container:hover {
        background-color: #333;
    }

    /* Styling for the alert messages */
    .alert {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-radius: 4px;
        padding: 10px;
        margin: 20px 0;
    }
</style>



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
        if (isset($_SESSION['currentUserModel'])) {
            $userModel = $_SESSION['currentUserModel'];
            $discount = isset($userModel['membership']['discountOffered']) ? $userModel['membership']['discountOffered'] : 0;
        }
        echo '<div class="payment-summary">';
        echo "<br><br> Membership Discount Offered: " . number_format($discount * 100) . "% applied!";
        echo '<br><strong>Total Amount: RM ' . number_format($totalPrice, 2) . '</strong>';
        echo '</div>';
        echo '
        <div id="paypal-button-container"></div>
        
        <script src="https://www.paypal.com/sdk/js?client-id=AczgOTTbFBN6U6nDNgzw1lpnYtku9tybmaxJxKsjX-p3Z0xLb90D4WGknaLf6Q6Es-ZfunPpjV3pk-8G&currency=MYR"></script>
        <script>
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: "' . number_format((float) $totalPrice, 2, '.', '') . '"
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
