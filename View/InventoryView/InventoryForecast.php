<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inventory Item</title>
        <link rel="stylesheet" href="../Css/Inventory/InventoryForecast.css">
    </head>
    <body>

        <div class="container">
            <div class="header">
                <img src="rosewood_hospital_logo.png" alt="Rosewood Hospital">
                <ul>
                    <li><a href="#">Medications</a></li>
                    <li><a href="#">Medical Supply</a></li>
                    <li><a href="#">Procurement</a></li>
                    <li><a href="#">Suppliers</a></li>
                    <li><a href="#">Reports</a></li>
                </ul>
            </div>

            <div class="nav">
                <div class="breadcrumb">
                    <a href="#">Medical Supply</a>
                    <i class="fas fa-chevron-right"></i>
                    <a href="#">Basic Medical Blue Nitrile Exam Gloves</a>
                    <i class="fas fa-chevron-right"></i>
                    <a href="#">Cycle Count</a>
                </div>
                <div class="user">
                    <img src="profile_picture.jpg" alt="Sarah Nixon">
                    <span>Sarah Nixon</span>
                </div>
            </div>

            <div class="content">
                <h2>Basic Medical Blue Nitrile Exam Gloves - Latex-Free & Powder-Free</h2>

                <table class="table">
                    <thead>
                        <tr>
                            <th>ITEM ID</th>
                            <th>COUNT UNIT</th>
                            <th>OLD COUNT</th>
                            <th>NEW COUNT</th>
                            <th>ITEMS USED</th>
                            <th>COUNTING PERIOD</th>
                            <th>LAST COUNT DATE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>000124-532</td>
                            <td>Box</td>
                            <td>356</td>
                            <td>180</td>
                            <td>176</td>
                            <td>1 Month</td>
                            <td>11/30/2021</td>
                        </tr>
                    </tbody>
                </table>

                <h3>Demand Forecast</h3>

                <div class="chart">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('myChart').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr'],
                    datasets: [{
                            label: 'Historical data',
                            data: [150, 150, 130, 160, 150, 170, 180, 180, 190, 190, 200],
                            borderColor: 'cyan',
                            backgroundColor: 'cyan',
                            fill: true,
                            tension: 0.4,
                        }, {
                            label: 'Forecast data',
                            data: [160, 160, 170, 170, 180, 190, 190, 190, 200, 200, 200],
                            borderColor: 'purple',
                            backgroundColor: 'purple',
                            fill: true,
                            tension: 0.4,
                        }, {
                            label: 'Last period usage',
                            data: [176, 176, 176, 176, 176, 176, 176, 176, 176, 176, 176],
                            borderColor: 'gold',
                            backgroundColor: 'gold',
                            fill: false,
                            tension: 0.4,
                        }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>

    </body>
</html>