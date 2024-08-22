
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Animal Inventory Tracking</title>
        <style>
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            td, th {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }

            tr:nth-child(even) {
                background-color: #dddddd;
            }

            .updated {
                text-align: center;
                padding: 8px;
                font-weight: bold;
            }
        </style>
    </head>

    <body>
        <?php
        // put your code here
        ?>
        <h2>Inventory</h2>

        <table>
            <tr>
                <th></th>
                <th>Photo</th>
                <th>Animal ID</th>
                <th>Name</th>
                <th>Species</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Average Life Span</th>
                <th>Updated</th>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td><img src="glulam-beam.jpg" alt="Glulam Beam" height="50"></td>
                <td>Glulam Beam</td>
                <td>184893</td>
                <td>5-1/4" x 13-3/4" x 24'</td>
                <td>$31.99</td>
                <td>$37.99</td>
                <td>11,638</td>
                <td>FBM</td>
                <td class="updated">08/18/2021, 3:48 PM</td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td><img src="osb-board-type-2.jpg" alt="OSB Board Type 2" height="50"></td>
                <td>OSB Board Type 2</td>
                <td>363933</td>
                <td>7/16 x 4 x 10</td>
                <td>$27.45</td>
                <td>$36.55</td>
                <td>305</td>
                <td>Sheets</td>
                <td class="updated">08/18/2021, 3:23 PM</td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td><img src="primed-mdf-board.jpg" alt="Primed MDF Board" height="50"></td>
                <td>Primed MDF Board</td>
                <td>504685</td>
                <td>1x4x7</td>
                <td>$16.99</td>
                <td>$23.95</td>
                <td>248</td>
                <td>Sheets</td>
                <td class="updated">08/18/2021, 2:11 PM</td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td><img src="sanded-plywood-board.jpg" alt="Sanded Plywood Board" height="50"></td>
                <td>Sanded Plywood Board</td>
                <td>238363</td>
                <td>1/4 x 2 x 4</td>
                <td>$29.95</td>
                <td>$38.75</td>
                <td>322</td>
                <td>Sheets</td>
                <td class="updated">08/17/2021, 5:08 PM</td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td><img src="i-joist-90-series.jpg" alt="I-Joist 90 Series" height="50"></td>
                <td>I-Joist 90 Series</td>
                <td>485956</td>
                <td>3-1/2" x 14" x 20'</td>
                <td>$60.99</td>
                <td>$79.55</td>
                <td>201</td>
                <td>Pieces</td>
                <td class="updated">08/17/2021, 3:54 PM</td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td><img src="osb-handi-panel.jpg" alt="OSB Handi-Panel" height="50"></td>
                <td>OSB Handi-Panel</td>
                <td>457446</td>
                <td>1/2 x 4 x 8</td>
                <td>$25.99</td>
                <td>$36.95</td>
                <td>190</td>
                <td>Sheets</td>
                <td class="updated">08/17/2021, 3:13 PM</td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td><img src="prefinished-mdf-panel.jpg" alt="Prefinished MDF Panel" height="50"></td>
                <td>Prefinished MDF Panel</td>
                <td>648393</td>
                <td>1/2 x 4 x 8</td>
                <td>$15.99</td>
                <td>$21.45</td>
                <td>300</td>
                <td>Sheets</td>
                <td class="updated">08/17/2021, 1:06 PM</td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td><img src="fire-retardant-interior.jpg" alt="Fire Retardant Interior" height="50"></td>
                <td>Fire Retardant Interior</td>
                <td>346273</td>
                <td>1-1/2" x 5-1/2" x 8'</td>
                <td>$8.99</td>
                <td>$13.55</td>
                <td>1,214</td>
                <td>FBM</td>
                <td class="updated">08/16/2021, 3:32 PM</td>
            </tr>
        </table>

    </body>
</html>