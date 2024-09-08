<?php

//
$content = '
        <div class="main-content">
        <form action="../../Control/InventoryController/createPO.php?inventoryId=' . $InventoryDetails['inventoryId'] . '&amp;itemType=' . $InventoryDetails['itemType'] . '&amp;itemID=' . $InventoryDetails['itemID'] . '" method="post" class="poform">
            
            <h1>Purchase Orders <span>&gt;</span> New Purchase Order #' . $POid . '</h1>
            <p>The purchase order has been generated based on the previous order #2022 from 07/05/2021. You can edit it if changes are required.</p>

            <h2>Item Details</h2>
            <table class="displayingTable">
                <tr>
                    <th>Photos</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td class="photo"><img src="' . $image . '" alt="' . $itemName . '"></td>
                    <td class="name">' . $itemName . '</td>
                    <td class="price">
                        RM <input type="text" id="priceInput" value="0.00" disabled required>
                        <input type="hidden" name="price" id="hiddenPrice" value="0.00">
                    </td>
                    <td class="quantity">
                        <button class="decrease" id="decrease">-</button>
                        <input type="number" name="quantity" id="quantity" value="1" min="0">
                        <button class="increase" id="increase">+</button>
                    </td>
                    <td>
                        <div class="total">
                            RM <input type="text" id="totalNumber" value="0.00" disabled>
                            <input type="hidden" name="total" id="hiddenTotal" value="0.00">
                        </div>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="itemName" value="' . htmlspecialchars($itemName) . '">
            <input type="hidden" name="itemImage" value="' . htmlspecialchars($image) . '">
            
';
$supplierOptions = '';
foreach ($supplierDetails as $supplierdetail) {
    if ($supplierdetail) {
        $supplierOptions .= "<option value=\"" . htmlspecialchars($supplierdetail['supplierId']) . "\">" . htmlspecialchars($supplierdetail['supplierName']) . "</option>";
    }
}
$content .= '
            <h2>Order Details</h2>
            <div class="order-details">
                <div class="field">
                    <label for="supplier">Supplier:</label>
                    <select id="supplier" name="supplierId" required>
                        <option value="" disabled selected>Select a supplier</option>
                        ' . $supplierOptions . '
                    </select>
                </div>
                <div class="field">
                    <label for="billing-address">Billing Address:</label>
                    <input type="text" id="billingAddress" name="billingAddress" value="">
                </div>
                <div class="field">
                    <label for="shippingAddress">Shipping Address:</label>
                    <input type="text" id="shippingAddress" name="shippingAddress" value="Jalan Taman Zooview, Taman Zooview, 68000 Ampang, Selangor" required>
                </div>
                <div class="field">
                    <label for="shipping-method">Shipping Method:</label>
                    <select id="shipping-method" name="shippingMethod">
                        <option value="dedicated-carrier">Dedicated Carrier</option>
                    </select>
                </div>
                <div class="field">
                    <label for="shipping-date">Preferred Shipping Date:</label>
                    <input type="date" id="shipping-date" name="shippingDate" required>
                </div>

                <div class="field">
                    <label for="shipping-time">Preferred Shipping Time:</label>
                    <input type="time" id="shipping-time" name="shippingTime" min="09:00" max="17:00" required>
                </div>

            </div>

            <button class="create-purchase-order">Create purchase order</button>
            </form>
        </div>
        
<script>
    var today = new Date();
    today.setDate(today.getDate() + 3); // Add 3 days

    // Format the date to YYYY-MM-DD
    var year = today.getFullYear();
    var month = (\'0\' + (today.getMonth() + 1)).slice(-2); // Month is 0-based
    var day = (\'0\' + today.getDate()).slice(-2);

    var minDate = year + \'-\' + month + \'-\' + day;

    // Set the min attribute of the date input to the calculated date
    document.getElementById(\'shipping-date\').setAttribute(\'min\', minDate);
    
    document.addEventListener("DOMContentLoaded", function() {
    const supplierSelect = document.getElementById("supplier");
    const priceField = document.getElementById("priceInput");
    const hiddenPrice = document.getElementById("hiddenPrice");
    const quantityInput = document.getElementById("quantity");
    const totalNumber = document.getElementById("totalNumber");
    const hiddenTotal = document.getElementById("hiddenTotal");
    const supplierAddress = document.getElementById("billingAddress");
    

    function updateHiddenFields() {
        // Update hidden fields with current values
        hiddenPrice.value = parseFloat(priceField.value.replace(/[^0-9.-]+/g, "")).toFixed(2);
        hiddenTotal.value = totalNumber.value;
    }

    function updateTotal() {
        let currentQuantity = parseFloat(quantityInput.value);
        if (isNaN(currentQuantity)) currentQuantity = 0;
        let price = parseFloat(priceField.value);
        let totalPrice = currentQuantity * price;
        totalNumber.value = totalPrice.toFixed(2);
        updateHiddenFields();
    }

    supplierSelect.addEventListener("change", function() {
        var selectedSupplierId = supplierSelect.value;
        var supplierPrices = {
               ';

foreach ($price as $supplierId => $supplierPrice) {
    $content .= '"' . $supplierId . '": ' . $supplierPrice . ',';
}


$content .= ' 
        };       
    
        var supplierAddresses = {
               ';
foreach ($supplierDetails as $supplierId => $details) {
    // Ensure the address is properly formatted with quotes
    $content .= '"' . $details["supplierId"] . '": "' . addslashes($details["address"]) . '",';
}
$content .= ' 
        };
        var price = supplierPrices[selectedSupplierId];
        
       var address = supplierAddresses[selectedSupplierId]; 
       
       if (address) {
            supplierAddress.value = address;
        } else {
            supplierAddress.value = "No address found";
        }
        if (price) {
            priceField.value = parseFloat(price).toFixed(2);
        } else {
            priceField.value = "0.00";
        }
        updateTotal();
    });

    quantityInput.addEventListener("change", function() {
        updateTotal();
    });

    document.getElementById("decrease").addEventListener("click", function(event) {
        event.preventDefault(); // Prevent page refresh
        let currentValue = parseFloat(quantityInput.value);
        if (currentValue > 0) {
            currentValue -= 1; // Decrease by 1
            quantityInput.value = currentValue;
            updateTotal();
        }
    });

    document.getElementById("increase").addEventListener("click", function(event) {
        event.preventDefault(); // Prevent page refresh
        let currentValue = parseFloat(quantityInput.value);
        currentValue += 1; // Increase by 1
        quantityInput.value = currentValue;
        updateTotal();
    });

    // Initialize total on page load
    updateTotal();
});

</script>
';
?>