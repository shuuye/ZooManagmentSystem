<?php
//
$content = '
        <div class="main-content">
        <form action="../../Control/InventoryController/addNewItem.php" method="post" class="poform">
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
                    <td class="price" id="price">' . $price . '</td>
                    <td class="quantity">
                        <button class="decrease" id="decrease">-</button>
                        <input type="number" id="quantity" value="1" min="0">
                        <button class="increase" id="increase">+</button>
                    </td>
                    <td><div class="total">RM <span id="totalNumber">0.00</span></div></td>
                </tr>
            </table>
';
foreach ($supplierDetails as $supplierdetail) {
    if ($supplierdetail) {
        $supplierOptions .= "<option value=\"" . htmlspecialchars($supplierdetail['supplierId']) . "\">" . htmlspecialchars($supplierdetail['supplierName']) . "</option>";
        $supplierDetailswithKey[$supplierdetail['supplierId']] = $supplierdetail; // Store details by supplier ID
    }
}
$content .= '
            <h2>Order Details</h2>
            <div class="order-details">
                <div class="field">
                    <label for="supplier">Supplier:</label>
                    <select id="supplier">
                        <option value="">Select a supplier</option>
                        ' . $supplierOptions . '
                    </select>
                </div>
                <div class="field">
                    <label for="billing-address">Billing Address:</label>
                    <select id="billing-address">
                        <option value="ohio-columbus">Ohio, Columbus, 5303 Fisher Rd</option>
                    </select>
                </div>
                <div class="field">
                    <label for="shipping-address">Shipping Address:</label>
                    <select id="shipping-address">
                        <option value="ohio-columbus">Ohio, Columbus, 5303 Fisher Rd</option>
                    </select>
                </div>
                <div class="field">
                    <label for="shipping-method">Shipping Method:</label>
                    <select id="shipping-method">
                        <option value="dedicated-carrier">Dedicated Carrier</option>
                    </select>
                </div>
                <div class="field">
                    <label for="shipping-date">Preferred Shipping Date:</label>
                    <select id="shipping-date">
                        <option value="08-25-2021">08/25/2021, 2:00 PM</option>
                    </select>
                </div>
            </div>

            <button class="create-purchase-order">Create purchase order</button>
            </form>
        </div>
        
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const decreaseButton = document.getElementById("decrease");
        const increaseButton = document.getElementById("increase");
        const quantityInput = document.getElementById("quantity");
        const price = parseFloat(document.getElementById("price").textContent.replace(/[^0-9.-]+/g,"")); // Replace with your actual price value
        const totalNumber = document.getElementById("totalNumber");

        function updateTotal() {
            // Get the current quantity
            let currentQuantity = parseFloat(quantityInput.value.replace(/,/g, ""));
            if (isNaN(currentQuantity)) currentQuantity = 0;
            
            // Calculate the total price
            const totalPrice = currentQuantity * price;
            
            // Update the total number in the DOM
            totalNumber.textContent = totalPrice.toFixed(2);
        }

        // Update the total when the page loads
        updateTotal();

        // Add event listeners for the buttons to update the total
        decreaseButton.addEventListener("click", function() {
         event.preventDefault(); // Prevent page refresh
            let currentValue = parseFloat(quantityInput.value.replace(/,/g, ""));
            if (currentValue > 0) {
                currentValue -= 1; // Decrease by 1
                quantityInput.value = currentValue.toLocaleString(); // Format with commas
                updateTotal(); // Update the total price
            }
        });

        increaseButton.addEventListener("click", function() {
         event.preventDefault(); // Prevent page refresh
            let currentValue = parseFloat(quantityInput.value.replace(/,/g, ""));
            currentValue += 1; // Increase by 1
            quantityInput.value = currentValue.toLocaleString(); // Format with commas
            updateTotal(); // Update the total price
        });

        // Also update the total if the user manually changes the quantity
        quantityInput.addEventListener("change", updateTotal);
    });
    
    
</script>

        
 
';
?>
