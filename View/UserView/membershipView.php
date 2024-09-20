<?php
    $membershipArray = $data['membershipArray'];
    $membershipAldBuy = isset($_SESSION['membershipAldBuy']) ? $_SESSION['membershipAldBuy'] : '';
    $membershipPurchaseSuccessFully = isset($_SESSION['membershipPurchaseSuccessFully']) ? $_SESSION['membershipPurchaseSuccessFully'] : '';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            .purchase-button {
                display: inline-block;
                background-color: green;
                color: white;
                text-decoration: none;
                padding: 10px 20px;
                border-radius: 5px;
                text-align: center;
                transition: background-color 0.3s ease;
            }
            .purchase-button:hover {
                background-color: lightgreen;
                color: black;
            }
        </style>
    </head>
    <body>
        <?php if (!empty($membershipAldBuy)): ?>
            <div class="failedSessionMsg" style="margin: 10px auto;">
                <?php echo htmlspecialchars($membershipAldBuy); ?>
            </div>
            <?php unset($_SESSION['membershipAldBuy']); // Clear the session message after displaying ?>
        <?php endif; ?>
        <?php if (!empty($membershipPurchaseSuccessFully)): ?>
            <div class="successSessionMsg" style="margin: 10px auto;">
                <?php echo htmlspecialchars($membershipPurchaseSuccessFully); ?>
            </div>
            <br>
            <div style="text-align: center;">
                <a href="index.php?controller=user&action=showUserProfile">Click Here to Go User Profile To View the current Membership</a>
            </div>
            
            <?php unset($_SESSION['membershipPurchaseSuccessFully']); // Clear the session message after displaying ?>
        <?php endif; ?>
        
        <h1>Membership Type</h1>
        <table class="userManagementTable">
            <thead>
                <tr>
                    <th>Membership Type</th>
                    <th>Fee</th>
                    <th>Discount Offered</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($membershipArray as $membership): ?>
                    <?php if ($membership['membershipID'] != 1): ?>
                        <tr>
                            <td style="text-align: center;"><?php echo htmlspecialchars($membership['membershipType']); ?></td>
                            <td style="text-align: center;"><?php echo htmlspecialchars($membership['fee']); ?></td>
                            <td style="text-align: center;"><?php echo htmlspecialchars($membership['discountOffered']); ?></td>
                            <td style="text-align: center;">
                                <a href="index.php?controller=user&action=purchaseMembership&membershipID=<?php echo htmlspecialchars($membership['membershipID']); ?>" class="purchase-button">Purchase</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>
</html>