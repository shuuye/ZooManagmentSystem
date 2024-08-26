<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard</title>
        <link rel="stylesheet" href="../Css/Inventory/inventory.css">
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>Dashboard</h1>
                <div class="header-right">
                    <ul>
                        <li>Inventory catalog</li>
                        <li>Purchase orders</li>
                        <li>Package</li>
                        <li>Shipment</li>
                        <li>Reports</li>

                    </ul>
                </div>
            </div>
            <div class="sidebar">
                <div class="logo">
                    <img src="logo.png" alt="Logo">
                    <p>ENGINEERED WOOD DISTRIBUTION</p>
                </div>
                <ul>
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="inventoryMainTrackingPage.php">Inventory</a></li>
                    <li><a href="#">Purchase Orders</a></li>
                    <li><a href="#">Suppliers</a></li>
                    <li><a href="#">Sales Orders</a></li>
                    <li><a href="#">Customers</a></li>
                    <li><a href="#">Reports</a></li>
                </ul>
                <div class="warehouse">
                    <select>
                        <option value="Warehouse A">Warehouse A</option>
                        <option value="Warehouse B">Warehouse B</option>
                        <option value="Warehouse C">Warehouse C</option>
                    </select>
                    <p>OH, Columbus, 5303 Fisher Rd</p>
                </div>
                <a href="#" class="manage-warehouses">Manage Warehouses</a>
            </div>
            <div class="content">
                <div class="user-header">
                    <div class="search">
                        <input type="text" placeholder="Search">
                    </div>
                    <div class="user-info">
                        <img src="user.png" alt="User">
                        <p>Steve Johnson</p>
                        <p>Inventory Manager</p>
                    </div>
                </div>
                <div class="kpi-container">
                    <h2>Inventory Management KPIs</h2>
                    <div class="period">
                        <p>Period: This week</p>
                        <select>
                            <option value="This week">This week</option>
                            <option value="This month">This month</option>
                            <option value="This year">This year</option>
                        </select>
                    </div>
                    <div class="allkpi-container">
                        <div class="kpi">
                            <p>Inventory Turnover Ratio</p>
                            <p>1.2</p>
                        </div>
                        <div class="kpi">
                            <p>Days to Sell Inventory</p>
                            <p>16.5</p>
                        </div>
                        <div class="kpi">
                            <p>Lead Time</p>
                            <p>14.2</p>
                        </div>
                        <div class="kpi">
                            <p>Average Inventory</p>
                            <p>$341,678</p>
                        </div>
                        <div class="kpi">
                            <p>Cost of Goods Sold</p>
                            <p>$412,343</p>
                        </div>
                        <div class="kpi">
                            <p>Perfect Order Rate</p>
                            <p>94.5%</p>
                        </div>
                        <div class="kpi">
                            <p>Service Level</p>
                            <p>95.5%</p>
                        </div>
                        <div class="kpi">
                            <p>Rate of Return</p>
                            <p>2.3%</p>
                        </div>
                    </div>
                </div>
                <div class="charts">
                    <div class="chart">
                        <h2>Sales Orders</h2>
                        <canvas id="sales-chart"></canvas>
                        <ul>
                            <li>Completed</li>
                            <li>In Progress</li>
                            <li>Returns</li>
                            <li>Overdue Shipping</li>
                        </ul>
                    </div>
                    <div class="chart">
                        <h2>Inventory</h2>
                        <canvas id="inventory-chart"></canvas>
                        <ul>
                            <li>In Stock Items</li>
                            <li>Out of Stock items</li>
                            <li>Low Stock Items</li>
                            <li>Dead Stock Items</li>
                        </ul>
                    </div>
                </div>
                <div class="product-container">
                    <div class="product-section">
                        <h2>Top Selling Items</h2>
                        <a href="#" class="more-items">More Items →</a>
                        <div class="product">
                            <img src="product1.jpg" alt="Product 1">
                            <p>Glulam Beam, 5-1/4" x 13-3/4" x 24</p>
                            <p>2.003</p>
                            <p>11,638</p>
                            <p>FBM</p>
                        </div>
                        <div class="product">
                            <img src="product2.jpg" alt="Product 2">
                            <p>OSB Board Type 27/16 x 4 x 10</p>
                            <p>164</p>
                            <p>305</p>
                            <p>Sheets</p>
                        </div>
                        <div class="product">
                            <img src="product3.jpg" alt="Product 3">
                            <p>Primed MDF Board, 1 x 4 x 7</p>
                            <p>274</p>
                            <p>248</p>
                            <p>Sheets</p>
                        </div>
                        <div class="product">
                            <img src="product4.jpg" alt="Product 4">
                            <p>Sanded Plywood Board, 1/4 x 2 x 4</p>
                            <p>132</p>
                            <p>322</p>
                            <p>Sheets</p>
                        </div>
                    </div>
                    <div class="product-section">
                        <h2>High Demand is Expected</h2>
                        <a href="#" class="more-items">More Items →</a>
                        <div class="product">
                            <img src="product5.jpg" alt="Product 5">
                            <p>Glulam Beam, 5-1/4" x 13-3/4" x 24</p>
                            <p>11,638</p>
                            <p>FBM</p>
                        </div>
                        <div class="product">
                            <img src="product6.jpg" alt="Product 6">
                            <p>Joist 90 Series, 3-1/2" x 14" x 20</p>
                            <p>201</p>
                            <p>Pieces</p>
                        </div>
                        <div class="product">
                            <img src="product7.jpg" alt="Product 7">
                            <p>CLT, 6-3/4" x 4 x 24'</p>
                            <p>4.830</p>
                            <p>FBM</p>
                        </div>
                        <div class="product">
                            <img src="product8.jpg" alt="Product 8">
                            <p>OSB Board Type 2, 7/16 x 4 x 10</p>
                            <p>305</p>
                            <p>Sheets</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <script src="script.js"></script>
    </body>
</html>
