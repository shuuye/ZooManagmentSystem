<!-- 
for inventory catalog
- have animal, habitat, food, and cleaning
- to see the items that is inside 
- dont know if want to combine with inventory tracking

-->

<?php
$activePage = 'Inventory Catalog';
$pageCss = 'mainInventoryTrackinig.css';
ob_start();
?>


<div class="main-content">

    <h2>Inventory</h2>


    <div class="recordingFunctions">
        <div class="filter">
            <img src="../assests/InventoryImages/btn-filter.svg" class="filter-icon"  />
            Filter
        </div>
        <div class="new-item">
            <img src="../assests/InventoryImages/btn-plus.svg" class="plus-icon"  />
            New Item
        </div>
        <div class="edit">
            <img src="../assests/InventoryImages/btn-edit.svg" class="edit-icon"  />
            Edit
        </div>
    </div>

    <table class="displayingTable">
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
            <td><img src="../assests/InventoryImages/sample.jpg" alt="Glulam Beam" height="50"></td>
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
            <td><img src="../assests/InventoryImages/sample.jpg" alt="OSB Board Type 2" height="50"></td>
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
            <td><img src="../assests/InventoryImages/sample.jpg" alt="Primed MDF Board" height="50"></td>
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
            <td><img src="../assests/InventoryImages/sample.jpg" alt="Sanded Plywood Board" height="50"></td>
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
            <td><img src="../assests/InventoryImages/sample.jpg" alt="I-Joist 90 Series" height="50"></td>
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
            <td><img src="../assests/InventoryImages/sample.jpg" alt="OSB Handi-Panel" height="50"></td>
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
            <td><img src="../assests/InventoryImages/sample.jpg" alt="Prefinished MDF Panel" height="50"></td>
            <td>Prefinished MDF Panel</td>
            <td>648393</td>
            <td>1/2 x 4 x 8</td>
            <td>$15.99</td>
            <td>$21.45</td>
            <td>300</td>
            <td>Sheets</td>
            <td class="updated">08/17/2021, 1:06 PM</td>
        </tr>
        <tr >
            <td colspan="10"> 
                <div class="nextpage">
                    <div class="prev"> 
                        < Prev
                    </div>
                    Page 1 of 14
                    <div class="next">
                        Next >
                    </div>
                </div>

            </td>

        </tr>
    </table>

</div>



<?php
$content = ob_get_clean();
include './InventoryMasterPage.php';
?>



