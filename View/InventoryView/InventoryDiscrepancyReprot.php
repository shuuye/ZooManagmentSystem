<!DOCTYPE html>
<html>
    <head>
         <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inventory Item</title>
        <link rel="stylesheet" href="../Css/Inventory/InventoryDiscrepancyReport.css">
    </head>
    <body>
        <div class="container">
            <div class="header">
                <div class="logo">
                    <img src="https://via.placeholder.com/40" alt="Logo">
                    <h1>Rosewood Hospital</h1>
                </div>
                <ul class="nav">
                    <li><a href="#">Reports</a></li>
                    <li><a href="#">Inventory Discrepancy</a></li>
                    <li><a href="#">Medications</a></li>
                    <li><a href="#">Medical Supplies</a></li>
                    <li><a href="#">Procurement</a></li>
                    <li><a href="#">Suppliers</a></li>
                    <li><a href="#">Reports</a></li>
                </ul>
                <div class="user">
                    <img src="https://via.placeholder.com/30" alt="User">
                    <span>Sarah Nixon</span>
                </div>
            </div>

            <div class="title">
                <h2>Inventory Discrepancy Report</h2>
            </div>

            <div class="search">
                <input type="text" placeholder="Search for an item">
                <button>Search</button>
            </div>

            <div class="filter">
                <button>Filter</button>
            </div>

            <div class="date-range">
                <label for="from">From:</label>
                <input type="date" id="from" value="2021-11-01">
                <label for="to">To:</label>
                <input type="date" id="to" value="2021-11-10">
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>IMAGE</th>
                        <th>NAME</th>
                        <th>ITEM ID</th>
                        <th>PRESUMABLY IN STOCK</th>
                        <th>ACTUALLY IN STOCK</th>
                        <th>DIFFERENCE</th>
                        <th>COUNT DATE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><img class="image" src="https://via.placeholder.com/50" alt="Image"></td>
                        <td>Basic Medical Blue Nitrile Exam Gloves</td>
                        <td>000124-532</td>
                        <td>180</td>
                        <td>177</td>
                        <td>-3</td>
                        <td>11/10/2021 at 11:54 AM</td>
                    </tr>
                    <tr>
                        <td><img class="image" src="https://via.placeholder.com/50" alt="Image"></td>
                        <td>Lidocane Plus 4% Pain Relieving Cream</td>
                        <td>000643-537</td>
                        <td>118</td>
                        <td>123</td>
                        <td>+5</td>
                        <td>11/09/2021 at 10:51 AM</td>
                    </tr>
                    <tr>
                        <td><img class="image" src="https://via.placeholder.com/50" alt="Image"></td>
                        <td>Sterile 0.9% Saline Wound Cleanser</td>
                        <td>000142-843</td>
                        <td>95</td>
                        <td>91</td>
                        <td>-4</td>
                        <td>11/09/2021 at 10:22 AM</td>
                    </tr>
                    <tr>
                        <td><img class="image" src="https://via.placeholder.com/50" alt="Image"></td>
                        <td>Pre-Moistened First Aid Antiseptic Wipes</td>
                        <td>000643-155</td>
                        <td>119</td>
                        <td>122</td>
                        <td>+3</td>
                        <td>11/08/2021 at 9:56 AM</td>
                    </tr>
                    <tr>
                        <td><img class="image" src="https://via.placeholder.com/50" alt="Image"></td>
                        <td>Basic Medical Blue Nitrile Exam Gloves</td>
                        <td>000483-736</td>
                        <td>110</td>
                        <td>110</td>
                        <td>0</td>
                        <td>11/08/2021</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>