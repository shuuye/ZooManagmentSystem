<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inventory Holding Cost</title>
        <link rel="stylesheet" href="../Css/Inventory/InventoryTrend.css">
    </head>
    <body>
        <div class="container">
            <div class="logo">
                <img src="https://dummyimage.com/40x40/007bff/fff" alt="Logo">
                <h2>Rosewood Hospital</h2>
            </div>
            <ul class="navigation">
                <li><a href="#">Medications</a></li>
                <li><a href="#">Medical Supply</a></li>
                <li><a href="#">Procurement</a></li>
                <li><a href="#">Suppliers</a></li>
                <li><a href="#">Reports</a></li>
            </ul>
            <div class="user">
                <i class="fas fa-bell"></i>
                <img src="https://dummyimage.com/30x30/007bff/fff" alt="User">
                <span>Sarah Nixon</span>
            </div>
        </div>

        <ul class="breadcrumb">
            <li><a href="#">Medications</a></li>
            <li><i class="fas fa-chevron-right"></i> <a href="#">Pain Relievers</a></li>
            <li><i class="fas fa-chevron-right"></i> <a href="#">Tylenol (Acetaminophen)</a></li>
            <li><i class="fas fa-chevron-right"></i> <a href="#">Automatic Replenishment</a></li>
        </ul>

        <div class="card">
            <div class="card-header">
                Acetaminophen (Tylenol) - Tylenol (Acetaminophen)
            </div>
            <div class="card-info">
                <i class="fas fa-info-circle"></i>
                <span>Current stock will cover 22 days based on the last 2 weeks trend</span>
            </div>
            <div class="card-content">
                <div class="item">
                    <h3>64</h3>
                    <div class="label">Current Stock</div>
                    <div class="value">bottles</div>
                </div>
                <div class="item">
                    <h3>300</h3>
                    <div class="label">Reorder Qty</div>
                    <div class="value">bottles</div>
                </div>
                <div class="item">
                    <h3>$3,245.56</h3>
                    <div class="label">Reorder Amount</div>
                </div>
                <div class="item">
                    <h3>12/14/2021</h3>
                    <div class="label">Next Automatic Reorder</div>
                    <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                    <div class="value">Reschedule</div>
                </div>
            </div>
            <div class="card-content">
                <div class="item">
                    <div class="label">Supplier</div>
                    <div class="value">OptimumHealth Pro</div>
                </div>
            </div>
        </div>

        <div class="chart">
            <div class="chart-header">
                <div class="title">Tylenol (Acetaminophen) - Usage Trend</div>
                <div class="weekly">
                    <i class="fas fa-chart-line"></i>
                    Weekly
                </div>
            </div>
            <div class="chart-body">
                <div class="line">
                    <div class="point" style="left: 10%; top: 75%;"><div class="label">23</div></div>
                    <div class="point" style="left: 30%; top: 80%;"><div class="label">24</div></div>
                    <div class="point" style="left: 50%; top: 78%;"><div class="label">25</div></div>
                    <div class="point" style="left: 70%; top: 70%;"><div class="label">22</div></div>
                    <div class="point" style="left: 90%; top: 75%;"><div class="label">28</div></div>
                </div>
                <div class="x-axis">
                    <div class="label">09/23/2021- <br> 09/30/2021</div>
                    <div class="label">10/01/2021- <br> 10/07/2021</div>
                    <div class="label">10/08/2021- <br> 10/15/2021</div>
                    <div class="label">10/16/2021- <br> 10/23/2021</div>
                    <div class="label">10/24/2021- <br> 10/31/2021</div>
                    <div class="label">11/01/2021- <br> 11/07/2021</div>
                    <div class="label">This week</div>
                </div>
                <div class="y-axis">
                    <div class="label">Bottles</div>
                    <div class="value">30</div>
                    <div class="value">20</div>
                    <div class="value">10</div>
                    <div class="value last">0</div>
                </div>
            </div>
        </div>
    </body>
</html>