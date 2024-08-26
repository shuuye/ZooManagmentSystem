<!DOCTYPE html>
<html>
    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Purchase Order</title>
        <link rel="stylesheet" href="../Css/Inventory/processPO.css">
    </head>
    <body>
        <header>
            <nav>
                <a href="#">Inventory catalog</a>
                <a href="#">Purchase orders</a>
                <a href="#">Sales orders</a>
                <a href="#">Package</a>
                <a href="#">Shipment</a>
                <a href="#">Reports</a>
                <a href="#">Contact Us</a>
            </nav>
        </header>

        <div class="container">
            <div class="sidebar">

            </div>

            <div class="main">
                <div class="purchase-order">
                    <h2>New Purchase Order #2384</h2>

                    <div class="status">
                        <span class="label">Status</span>
                        <span class="draft">Draft</span>
                    </div>

                    <div class="progress">
                        <div class="step">
                            <div class="circle active"></div>
                            <span>Draft purchase order creation</span>
                        </div>
                        <div class="step">
                            <div class="circle"></div>
                            <span>Awaiting purchasing team approval</span>
                        </div>
                        <div class="step">
                            <div class="circle"></div>
                            <span>Awaiting supplier approval</span>
                        </div>
                        <div class="step">
                            <div class="circle"></div>
                            <span>Order processing</span>
                        </div>
                        <div class="step">
                            <div class="circle"></div>
                            <span>Awaiting shipment</span>
                        </div>
                        <div class="step">
                            <div class="circle"></div>
                            <span>Order dispatched</span>
                        </div>
                        <div class="line"></div>
                    </div>

                    <div class="details">
                        <div class="column">
                            <div class="label">To</div>
                            <div class="value">Company: Wells & Wade</div>
                            <div class="value">Address: Ohio, Delaware, 435 Park Ave</div>
                            <div class="value">Phone: 614-435-2489</div>
                        </div>

                        <div class="column">
                            <div class="label">Ship To</div>
                            <div class="value">Company: Engineered Wood Distribution</div>
                            <div class="value">Address: Ohio, Columbus, 5303 Fisher Rd</div>
                            <div class="value">Phone: 614-205-9702</div>
                        </div>
                    </div>

                    <h2>PURCHASE ORDER</h2>

                    <div class="details">
                        <div class="column">
                            <div class="label">Created On Date:</div>
                            <div class="value">08/18/2021, 1:08 PM</div>
                        </div>

                        <div class="column">
                            <div class="label">Preferred Shipping Date:</div>
                            <div class="value">08/25/2021, 2:00 PM</div>
                        </div>
                    </div>

                    <h3>Order Details</h3>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>SKU</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Units</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>Glulam Beam, 5-1/4" x 13-3/4" x 24'</td>
                                <td>184893</td>
                                <td>$31.99</td>
                                <td>5,500</td>
                                <td>FBM</td>
                                <td>$175,975.00</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="total">
                        <span class="label">Subtotal</span>
                        <span>$175,975.00</span>
                    </div>

                    <div class="total">
                        <span class="label">Shipping</span>
                        <span>$0.00</span>
                    </div>

                    <div class="total">
                        <span class="label">Total</span>
                        <span>$175,975.00</span>
                    </div>

                    <div class="actions">
                        <button>Edit order</button>
                        <button>Cancel order</button>
                        <button>Send for approval</button>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            &copy; 2023 Engineered Wood Distribution
        </footer>
    </body>
</html>