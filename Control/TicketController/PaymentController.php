<?php
/*Author name: Vanness Chaw Jun Kit*/

require_once __DIR__ . '/../../Model/Tickets/PaymentModel.php';
require_once __DIR__ . '/../../View/TicketView/PaymentView.php';

class PaymentController {

    public function handleRequest() {
        
        if (!isset($_SESSION['currentUserModel']) || !isset($_SESSION['currentUserModel']['id'])) {
            return 'User is not logged in. Please log in and try again.';
        }

        $userId = $_SESSION['currentUserModel']['id'];

        $model = new PaymentModel();
        try {
            // Get ticket purchases and calculate total price
            $ticketPurchases = $model->getTicketPurchases();
            $totalPrice = $model->calculateTotalPrice();

            // Display payment details on the payment page
            PaymentView::displayPaymentDetails($ticketPurchases, $totalPrice);

            // If payment is successful, save to database
            if (isset($_POST['transaction_id'])) {
                $transactionId = $_POST['transaction_id'];

                // Save the payment details (transaction ID, user ID, and amount)
                $model->savePaymentDetails($userId, $transactionId, $totalPrice);

                // Redirect to the success page or handle post-payment
                header("Location: successPage.php");
                exit;
            }
        } catch (Exception $e) {
            echo '<p style="color: red;">' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
}

?>
