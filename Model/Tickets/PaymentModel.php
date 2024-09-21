<?php

require_once __DIR__ . '/../../Config/databaseConfig.php';

class PaymentModel extends databaseConfig {
    
    private $filePath;
    private $xmlData;

    // Constructor with optional filePath initialization
    public function __construct($filePath = null) {
        $this->filePath = $filePath ?? __DIR__ . '/../Xml/ticket_purchases.xml';
    }

    // Getter and Setter for filePath
    public function getFilePath() {
        return $this->filePath;
    }

    public function setFilePath($filePath) {
        $this->filePath = $filePath;
    }

    // Getter and Setter for XML data
    public function getXmlData() {
        return $this->xmlData;
    }

    public function setXmlData($xmlData) {
        $this->xmlData = $xmlData;
    }

    // Method to retrieve ticket purchases from XML file
    public function getTicketPurchases() {
        if (!file_exists($this->filePath)) {
            throw new Exception("XML file not found.");
        }

        $xml = simplexml_load_file($this->filePath);
        if ($xml === false) {
            throw new Exception("Failed to load XML file.");
        }

        $this->setXmlData($xml);  // Store XML data if needed later
        return $xml;
    }

    // Method to calculate the total price of tickets
    public function calculateTotalPrice() {
        $ticketPurchases = $this->getTicketPurchases();
        $totalPrice = 0;

        // Calculate the total price of tickets
        foreach ($ticketPurchases->Ticket as $ticket) {
            $totalPrice += floatval($ticket->total);
        }

        // Check if the user has a discount based on membership
        if (isset($_SESSION['currentUserModel'])) {
            $userModel = $_SESSION['currentUserModel'];
            $discount = isset($userModel['membership']['discountOffered']) ? $userModel['membership']['discountOffered'] : 0;

            // Apply discount if available
            if ($discount > 0) {
                $totalPrice = $totalPrice - ($totalPrice * $discount);
            }
        }

        return $totalPrice;
    }

    // Method to save payment details to the database
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
