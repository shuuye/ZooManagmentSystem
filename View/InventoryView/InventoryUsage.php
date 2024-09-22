<?php
//<!-- Author name: Lim Shuye -->
$inventoryTypes = array_unique(array_column($inventoryData, 'itemType'));
$inventoryTypes = array_filter($inventoryTypes, function ($type) {
    return $type !== "Animal";
});

$content = '
<div class="main-content">
    <form id="inventory-form" action="/ZooManagementSystem/Control/InventoryController/logUsage.php" method="post">
        <label for="date-time">Date and Time:</label>
        <input type="datetime-local" id="date-time" name="date-time" value="' . date('Y-m-d\TH:i') . '">
        <br><br>
        
        <label for="inventory-type">Select Inventory Type:</label>
        <select id="inventory-type" name="inventory-type" onchange="filterItems()">
            <option value="">Select an Inventory Type</option>';
foreach ($inventoryTypes as $type) {
    $content .= '<option value="' . $type . '">' . $type . '</option>';
}
$content .= '
        </select>
        <br><br>
        
        <label for="inventory-item">Select Inventory Item:</label>
        <select id="inventory-item" name="inventory-item" onchange="setMaxQuantity()" required>
            <option value="">Select an Inventory Item</option>
            <!-- Inventory items will be populated dynamically using JavaScript -->
        </select>
        <br><br>
        
        <label for="quantity-used">Quantity Used:</label>
        <input type="number" id="quantity-used" name="quantity-used" min="1" placeholder="Enter quantity" disabled oninput="checkQuantity()" required>
        <br><br>
        
        <label for="reason-for-use">Reason for Use:</label>
        <textarea id="reason-for-use" name="reason-for-use" placeholder="Optional: specify the purpose"></textarea>
        <br><br>
        
        <button type="submit">Submit</button>
    </form>
</div>

<script>
// JavaScript to handle dynamic item filtering and quantity validation
var inventoryArray = ' . json_encode($inventoryData) . ';

function filterItems() {
    var itemType = document.getElementById("inventory-type").value;
    var itemSelect = document.getElementById("inventory-item");
    itemSelect.innerHTML = "<option value=\'\'>Select an Inventory Item</option>"; // Clear existing options
        var quantityInput = document.getElementById("quantity-used");
    var maxQuantity = quantityInput.max;
    quantityInput.value = ""; // Clear previous input
    quantityInput.disabled = false; // Enable quantity field once an item is selected

    
    // Filter items based on selected type
    inventoryArray.forEach(function(item) {
        if (item.itemType === itemType) {
            var option = document.createElement("option");
            option.value = item.inventoryId;
            option.text = item.itemName + " (" + item.quantity + " available)";
            option.dataset.quantity = item.quantity;
            itemSelect.appendChild(option);
        }
    });
}

function setMaxQuantity() {
    var itemSelect = document.getElementById("inventory-item");
    var selectedOption = itemSelect.options[itemSelect.selectedIndex];
    var maxQuantity = selectedOption.dataset.quantity;

    var quantityInput = document.getElementById("quantity-used");
    quantityInput.max = maxQuantity;
}

function checkQuantity() {
    var quantityInput = document.getElementById("quantity-used");
    var maxQuantity = quantityInput.max;

    if (parseInt(quantityInput.value) > parseInt(maxQuantity)) {
        alert("Quantity exceeds available stock!");
        quantityInput.value = maxQuantity; 
    }
}
</script>
';
?>