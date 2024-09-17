<?php
require_once __DIR__ . '/../../Config/webConfig.php';
$webConfig = new webConfig();
$webConfig->restrictAccessForNonLoggedInUser();

$selectedMembership = $data['selectedMembership'];
$fee = $selectedMembership['fee'];
?>
<!DOCTYPE html>
<html>
    <head>
        <style>
            .cancel-button {
                display: inline-block;
                background-color: grey;
                color: black;
                text-decoration: none;
                padding: 10px 20px;
                border-radius: 5px;
                text-align: center;
                transition: background-color 0.3s ease;
            }
        </style>
    </head>
    <body>
        <table style="text-align: left; margin: 50px auto;">
            <tr>
                <th>Membership Type:</th>
                <td><?php echo htmlspecialchars($selectedMembership['membershipType']); ?></td>
            </tr>
            <tr>
                <th>Discount Offered:</th>
                <td><?php echo htmlspecialchars($selectedMembership['discountOffered']); ?></td>
            </tr>
            <tr>
                <th>Membership Fee:</th>
                <td><?php echo htmlspecialchars(number_format($fee, 2)); ?></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <a href="index.php?controller=user&action=showMembership" class="cancel-button">Cancel</a>
                </td>
            </tr>
        </table>

        <div id="paypal-button-container"></div>

        <script src="https://www.paypal.com/sdk/js?client-id=AczgOTTbFBN6U6nDNgzw1lpnYtku9tybmaxJxKsjX-p3Z0xLb90D4WGknaLf6Q6Es-ZfunPpjV3pk-8G&currency=MYR"></script>
        <script>
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '<?php echo number_format($fee, 2, '.', ''); ?>' // Ensure this is a string
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        alert("Transaction completed by " + details.payer.name.given_name);
                        // Redirect to success page or handle the success case
                        window.location.href = "index.php?controller=user&action=purchaseSuccess&membershipID=" + <?php echo $selectedMembership['membershipID']; ?>; // Pass orderID or another identifier
                    });
                },
                onError: function(err) {
                    console.error(err);
                    // Handle error case
                    alert("Payment could not be processed. Please try again.");
                }
            }).render("#paypal-button-container");
        </script>
    </body>
</html>
