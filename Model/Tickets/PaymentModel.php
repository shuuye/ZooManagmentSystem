<?php

require_once __DIR__ . '/../../Config/databaseConfig.php';

class PaymentModel extends databaseConfig {

    public function getTicketPurchases() {
        $filePath = __DIR__ . '/../Xml/ticket_purchases.xml';

        if (!file_exists($filePath)) {
            throw new Exception("XML file not found.");
        }

        $xml = simplexml_load_file($filePath);
        if ($xml === false) {
            throw new Exception("Failed to load XML file.");
        }

        return $xml;
    }

    public function calculateTotalPrice() {
        $ticketPurchases = $this->getTicketPurchases();
        $totalPrice = 0;

        // Calculate the total price of tickets
        foreach ($ticketPurchases->Ticket as $ticket) {
            $totalPrice += floatval($ticket->total);
        }

        // Check if the user has a discount based on membership
        if (isset($_SESSION['userModel'])) {
            $userModel = $_SESSION['userModel'];
            $discount = isset($userModel['membership']['discountOffered']) ? $userModel['membership']['discountOffered'] : 0;

            // Apply discount if available
            if ($discount > 0) {
                $totalPrice = $totalPrice - ($totalPrice * $discount );
            }
        }

        return $totalPrice;
    }

    public function savePaymentDetails($transactionId, $amount, $userId) {
        $db = $this->getConnection();

        $sql = "INSERT INTO payments (transaction_id, user_id, amount) VALUES (:transaction_id, :user_id, :amount)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':transaction_id' => $transactionId,
            ':user_id' => $userId,
            ':amount' => $amount
        ]);
    }
}

?>
