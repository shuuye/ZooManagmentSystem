<?php
    /*Author Name: Chew Wei Seng*/

class RestfulLocationService_Consume {
    private $apiKey;
    private $apiUrl = 'https://maps.googleapis.com/maps/api/geocode/json'; // Replace with actual API URL

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function getFullAddress($latitude, $longitude) {
        $url = "{$this->apiUrl}?latlng={$latitude},{$longitude}&key={$this->apiKey}";
        $response = file_get_contents($url);

        if ($response === FALSE) {
            throw new Exception('Error fetching location data');
        }

        $data = json_decode($response, true);

        if (isset($data['results'][0]['formatted_address'])) {
            return $data['results'][0]['formatted_address'];
        } else {
            throw new Exception('Error processing location data');
        }
    }
}
?>
