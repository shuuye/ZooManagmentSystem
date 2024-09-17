<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Rest API Client Side Demo</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">
            <h2>Rest API Client Side Demo</h2>
            <form class="form-inline" action="" method="POST">
                <div class="form-group">
                    <label for="email">email</label>
                    <input type="text" name="email" class="form-control" placeholder="Enter Supplier Email" required/>
                </div>
                <button type="submit" name="submit" class="btn btndefault">Submit</button>
            </form>
            <p>&nbsp;</p>
            <h3>
                <?php
                if (isset($_POST['submit'])) {
                    $email = $_POST['email'];
                    $url = "http://localhost/ZooManagementSystem/apiWebService.php?email=" . $email;
                    $client = curl_init();

                    // Set the correct URL

                    curl_setopt($client, CURLOPT_URL, $url);
                    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($client, CURLOPT_FOLLOWLOCATION, true);  // Follow redirects
                    curl_setopt($client, CURLOPT_SSL_VERIFYPEER, false);  // Disable SSL certificate verification
                    curl_setopt($client, CURLOPT_SSL_VERIFYHOST, false);  // Disable SSL host verification

                    $response = curl_exec($client);

                    if (curl_errno($client)) {
                        echo 'cURL Error: ' . curl_error($client);
                    } else {
                        // Trim any extra whitespace
                        $response = trim($response);
                        //var_dump($response);
                        // Try to decode the JSON response
                        $result = json_decode($response);

                        // Check for JSON decoding errors
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            echo 'JSON Error: ' . json_last_error_msg();
                        } else {

                            if (isset($result->data) && is_array($result->data)) {
                                echo "<h3>Purchase Order Details:</h3>";
                                foreach ($result->data as $po) {
                                    echo "<p>PO ID: " . $po->poId . "</p>";
                                    echo "<p>Order Date: " . $po->orderDate . "</p>";
                                    echo "<p>Delivery Date: " . $po->deliveryDate . "</p>";
                                    echo "<p>Total Amount: " . number_format($po->totalAmount, 2) . "</p>";
                                    echo "<p>Status: " . $po->status . "</p>";
                                    echo "<h4>Line Item Details:</h4>";
                                    echo "<p>Inventory ID: " . $po->inventoryId . "</p>";
                                    echo "<p>Cleaning ID: " . $po->cleaningId . "</p>";
                                    echo "<p>Habitat ID: " . $po->habitatId . "</p>";
                                    echo "<p>Food ID: " . $po->foodId . "</p>";
                                    echo "<p>Quantity: " . $po->quantity . "</p>";
                                    echo "<p>Unit Price: " . number_format($po->unitPrice, 2) . "</p>";
                                    echo "<hr>";
                                }
                            } else {
                                echo "Data not found in the response";
                            }
                        }
                    }

                    curl_close($client);
                }
                ?>
            </h3>
        </div>
    </body>
</html>