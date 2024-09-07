<?php

class poServiceClient {
    public function fetchPO($email) {
        $client = curl_init();

        // Set the correct URL
        $url = "https://localhost/ZooManagementSystem/api.php?email=" . urlencode($email);

        curl_setopt($client, CURLOPT_URL, $url);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($client, CURLOPT_FOLLOWLOCATION, true);  // Follow redirects
        curl_setopt($client, CURLOPT_SSL_VERIFYPEER, false);  // Disable SSL certificate verification
        curl_setopt($client, CURLOPT_SSL_VERIFYHOST, false);  // Disable SSL host verification

        $response = curl_exec($client);

        if (curl_errno($client)) {
            return 'cURL Error: ' . curl_error($client);
        } else {
            // Trim any extra whitespace
            $response = trim($response);

            // Try to decode the JSON response
            $result = json_decode($response);

            // Check for JSON decoding errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                return 'JSON Error: ' . json_last_error_msg();
            } else {
                return $result;
            }
        }

        curl_close($client);
    }
}
