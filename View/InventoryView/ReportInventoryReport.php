<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inventory Reports</title>
        <link rel="stylesheet" href="../../Css/Inventory/InventoryReport.css">
    </head>
    <body>
        <header>
            <div class="container">
                <div>
                    <h1>Inventory Reports</h1>
                </div>
                <div class="nav-links">
                    <a href="#" class="nav-link">Inventory catalog</a>
                    <a href="#" class="nav-link">Purchase orders</a>
                    <a href="#" class="nav-link">Sales orders</a>
                    <a href="#" class="nav-link">Package</a>
                    <a href="#" class="nav-link">Shipment</a>
                    <a href="#" class="nav-link">Reports</a>
                    <a href="#" class="nav-link">Contact Us</a>
                </div>
                <div class="user-info">
                    <i class="fas fa-bell"></i>
                    <img src="https://via.placeholder.com/30" alt="Profile Picture">
                    <span>Steve Johnson</span>
                    <span>Inventory Manager</span>
                </div>
            </div>
        </header>

        <main class="main">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search">
            </div>

            <div class="date-picker">
                <label for="start-date">Start Date:</label>
                <input type="date" id="start-date" value="2021-08-01">
                <i class="far fa-calendar-alt"></i>
            </div>

            <div class="date-picker">
                <label for="end-date">End Date:</label>
                <input type="date" id="end-date" value="2021-08-31">
                <i class="far fa-calendar-alt"></i>
            </div>

            <div class="report-section">
                <div class="report-card">
                    <div class="report-title">Inventory Turnover Ratio Report</div>
                    <div class="report-description">Get insights into your inventory turnover rate.</div>
                    <a href="#" class="report-link">View Report</a>
                    <div class="report-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
                <div class="report-card">
                    <div class="report-title">Inventory Holding Cost Report</div>
                    <div class="report-description">Analyze the cost of holding inventory over time.</div>
                    <a href="#" class="report-link">View Report</a>
                    <div class="report-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
                <div class="report-card">
                    <div class="report-title">Inventory on Hand Report</div>
                    <div class="report-description">Track your current inventory levels.</div>
                    <a href="#" class="report-link">View Report</a>
                    <div class="report-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
                <div class="report-card">
                    <div class="report-title">Low Inventory Report</div>
                    <div class="report-description">Identify items with low stock levels.</div>
                    <a href="#" class="report-link">View Report</a>
                    <div class="report-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
                <div class="report-card">
                    <div class="report-title">Inventory Discrepancy Report</div>
                    <div class="report-description">Track any discrepancies between physical and recorded inventory.</div>
                    <a href="#" class="report-link">View Report</a>
                    <div class="report-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
                <div class="report-card">
                    <div class="report-title">Aged Inventory Report</div>
                    <div class="report-description">Analyze the age of your inventory.</div>
                    <a href="#" class="report-link">View Report</a>
                    <div class="report-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
                <div class="report-card">
                    <div class="report-title">Average Days to Sell Report</div>
                    <div class="report-description">Calculate the average time it takes to sell inventory.</div>
                    <a href="#" class="report-link">View Report</a>
                    <div class="report-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
                <div class="report-card">
                    <div class="report-title">Lead Time Report</div>
                    <div class="report-description">Track the time it takes to receive inventory.</div>
                    <a href="#" class="report-link">View Report</a>
                    <div class="report-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
                <div class="report-card">
                    <div class="report-title">Inventory Cycle Count Report</div>
                    <div class="report-description">Track the frequency of your inventory cycle counts.</div>
                    <a href="#" class="report-link">View Report</a>
                    <div class="report-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </main>

        <script src="https://kit.fontawesome.com/your-fontawesome-kit-id.js" crossorigin="anonymous"></script>
    </body>
</html>