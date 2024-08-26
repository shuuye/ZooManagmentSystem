<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order</title>
    <link rel="stylesheet" href="../Css/Inventory/purchaseorder.css">
</head>
<body>
    <div class="container">
        <h1>Purchase Orders <span>&gt;</span> New Purchase Order #2384</h1>
        <p>The purchase order has been generated based on the previous order #2022 from 07/05/2021. You can edit it if changes are required.</p>

        <h2>Item Details</h2>
        <div class="item-details">
            <div class="item-detail">
                <div class="photo">
                    <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.woodcraft.com%2Fproducts%2Fglulam-beams&psig=AOvVaw14275w6H3l_QfW2W_2g_kE&ust=1691380367769000&source=images&cd=vfe&ved=0CBEQjRxqFwoTCPD_rN_X_4ADFQAAAAAdAAAAABAD&uact=8&opi=89978449&client=internal-xx-ca&q=glulam%20beam%20image&ved=0CBEQjRxqFwoTCPD_rN_X_4ADFQAAAAAdAAAAABAD&opi=89978449&client=internal-xx-ca&q=glulam%20beam%20image&ved=0CBEQjRxqFwoTCPD_rN_X_4ADFQAAAAAdAAAAABAD&opi=89978449&client=internal-xx-ca&q=glulam%20beam%20image" alt="Glulam Beam">
                </div>
                <div class="name">Glulam Beam, 5-1/4" x 13-3/4" x 24"</div>
                <div class="sku">184893</div>
                <div class="price">$31.99</div>
                <div class="units">FBM</div>
                <div class="quantity">
                    <button class="decrease">-</button>
                    <input type="number" value="5,500">
                    <button class="increase">+</button>
                </div>
                <div class="total">$175,975.00</div>
            </div>
        </div>

        <h2>Order Details</h2>
        <div class="order-details">
            <div class="field">
                <label for="supplier">Supplier:</label>
                <select id="supplier">
                    <option value="wells-wade">Wells & Wade</option>
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
    </div>
</body>
</html>