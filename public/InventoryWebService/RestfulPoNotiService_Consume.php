<!-- Author name: Lim Shuye -->

<?php

require_once '../../Config/databaseConfig.php';

class RestfulPoNotiService_Consume extends databaseConfig {

    private $apiUrl = 'http://localhost:8080/api/reorder'; // API URL
    private $encryptionKey = '773f59689ecda8f66544e104f605099a0caf84a23e0e6dc9c2011aead9819202'; // AES key
    private $hmacKey = 'd379b6527dfcfa7e312cfa904b94ab9d8cf44eaa9169f4eca37c5195e9485e7a'; // Key for HMAC

    public function __construct() {
        
    }

    // Method to encrypt data using AES-256 encryption
    private function encryptData($data) {
        $iv = openssl_random_pseudo_bytes(16); // Generate a random IV
        $binaryKey = hex2bin($this->encryptionKey);
        $encryptedData = openssl_encrypt(json_encode($data), 'aes-256-cbc', $binaryKey, OPENSSL_RAW_DATA, $iv);
        $encodedData = base64_encode($encryptedData);
        

        return base64_encode($iv . $encryptedData); // IV needs to be sent along with the encrypted data
    }

    // Method to generate HMAC for data integrity
    // Method to generate HMAC for data integrity
    private function generateHMAC($data) {
        $hmac = hash_hmac('sha256', $data, $this->hmacKey, true); // Return raw binary data
        $base64Hmac = base64_encode($hmac); // Encode in Base64

        return $base64Hmac;
    }

    // Method to send PO to the supplier via web service with encryption and HMAC
    public function sendPOtoSupplier($poId, $itemName, $quantity, $orderDate, $shippingDate, $shippingAddress, $total) {
        $url = $this->apiUrl;

        // Data to send in the POST request
        $data = array(
            'poId' => $poId,
            'itemName' => $itemName,
            'quantity' => $quantity,
            'orderDate' => $orderDate,
            'shippingDate' => $shippingDate,
            'shippingAddress' => $shippingAddress,
            'total' => $total
        );
        
        // Encrypt the data before sending
        $encryptedData = $this->encryptData($data);

        // Generate HMAC for the encrypted data
        $hmac = $this->generateHMAC($encryptedData);
        // Data to send to the web service including HMAC
        $postData = array(
            'data' => $encryptedData,
            'hmac' => $hmac
        );

        // Use curl to send the POST request to the web service
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        // Execute the request
        $response = curl_exec($ch);

        // Check for errors and handle the response
        if ($response === false) {
            echo 'Curl error: ' . curl_error($ch);
        } else {
            echo 'Response from web service: ' . $response;
        }

        curl_close($ch);
    }
}

////// Example usage
//$poId = 2;
//$itemName = 'Client item123';
//$quantityToReorder = 10;
//
//$service = new RestfulPoNotiService_Consume();
//$service->sendPOtoSupplier($poId, $itemName, $quantityToReorder, "", "", "", 34);
//$key = openssl_random_pseudo_bytes(32);
//echo bin2hex($key); // Convert to hex for readability
?>
