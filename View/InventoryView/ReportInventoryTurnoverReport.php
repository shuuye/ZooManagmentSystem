<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inventory Turnover Report</title>
        <link rel="stylesheet" href="../../Css/Inventory/InventoryTurnoverReport.css">
    </head>
    <body>

        <div class="container">
            <div class="left-column">
                <h1>Inventory Turnover Report</h1>
                <div class="search-container">
                    <input type="text" class="search-input" placeholder="Search">
                    <button class="search-button">Search</button>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>SKU</th>
                            <th>Beginning Inventory</th>
                            <th>Purchases</th>
                            <th>Sales</th>
                            <th>Ending Inventory</th>
                            <th>Inventory Turnover</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>OSB Handi-Panel, 1/2 x 6 x 10</td>
                            <td>457436</td>
                            <td>$6,112.25</td>
                            <td>$10,220.02</td>
                            <td>$12,230.00</td>
                            <td>$4,102.27</td>
                            <td>2.4</td>
                        </tr>
                        <tr>
                            <td>Sanded Plywood Board, 1/4 x 4 x6</td>
                            <td>457434</td>
                            <td>$32,330.02</td>
                            <td>$26,220.16</td>
                            <td>$46,436.00</td>
                            <td>$12,114.18</td>
                            <td>2.1</td>
                        </tr>
                        <tr>
                            <td>Glulam Beam, 5-1/4" x 10-3/4" x 22</td>
                            <td>457436</td>
                            <td>$296,281.20</td>
                            <td>$40,500.24</td>
                            <td>$291,218.70</td>
                            <td>$45,562.50</td>
                            <td>1.7</td>
                        </tr>
                        <tr>
                            <td>Primed MDF Board, 1 x4x8</td>
                            <td>504683</td>
                            <td>$12,135.16</td>
                            <td>$11,500.12</td>
                            <td>$15,686.00</td>
                            <td>$7,949.28</td>
                            <td>1.6</td>
                        </tr>
                        <tr>
                            <td>OSB Board Type 2, 7/16 x 4 x 12</td>
                            <td>363954</td>
                            <td>$20,912.25</td>
                            <td>$12,434.25</td>
                            <td>$23,616.25</td>
                            <td>$9,730.25</td>
                            <td>1.5</td>
                        </tr>
                        <tr>
                            <td>ACX Sanded Plywood, 1/4 x 4'x8'</td>
                            <td>457573</td>
                            <td>$8,144.08</td>
                            <td>$9,220.02</td>
                            <td>$10,300.00</td>
                            <td>$7,064.86</td>
                            <td>1.5</td>
                        </tr>
                        <tr>
                            <td>Fire Retardant-Interior, 6' x 8</td>
                            <td>346274</td>
                            <td>$10,220.50</td>
                            <td>$11,430.14</td>
                            <td>$10,979.46</td>
                            <td>$10,647.18</td>
                            <td>1.1</td>
                        </tr>
                        <tr>
                            <td>I-Joist 90 Series, 3-1/2" x 14" x 22′</td>
                            <td>485343</td>
                            <td>$102,858.55</td>
                            <td>$63,450.14</td>
                            <td>$126,400.55</td>
                            <td>$39,908.32</td>
                            <td>0.8</td>
                        </tr>
                        <tr>
                            <td>Laminated Strand Lumber, 2' x 6'</td>
                            <td>329474</td>
                            <td>$15,686.04</td>
                            <td>$13,450.56</td>
                            <td>$12,260.88</td>
                            <td>$16,875.72</td>
                            <td>0.8</td>
                        </tr>
                        <tr>
                            <td>Plywood Sheathing, 4' x 8'</td>
                            <td>648293</td>
                            <td>$10,300.56</td>
                            <td>$13,450.18</td>
                            <td>$11,579.33</td>
                            <td>$12,171.41</td>
                            <td>0.7</td>
                        </tr>
                    </tbody>
                </table>

                <div class="pagination">
                    <button class="pagination-button" disabled>Prev</button>
                    <button class="pagination-button">Page 1 of 14</button>
                    <button class="pagination-button">Next</button>
                </div>
            </div>

            <div class="right-column">
                <div class="filter-container">
                    <button class="filter-button">Filter</button>
                </div>
            </div>
        </div>

    </body>
</html>