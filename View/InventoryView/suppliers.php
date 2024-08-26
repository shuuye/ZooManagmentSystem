<?php
$activePage = 'Supplier > Wells & Wade'; /* next time use php to call the name of supplier */
$pageCss = 'suppliers.css';
ob_start();
?>

<div class="main-content">

    <section class="supplier-info-section">
        <div class="supplier-details">
            <h3>About Supplier</h3>
            <table class="supplierInfo section-table">
                <tr>
                    <th colspan="2">Wells & Wade</th>
                </tr>
                <tr>
                    <td>Address: </td>
                    <td class="details">Ohio, Delaware, 435 Park Ave</td>
                </tr>
                <tr>
                    <td>Phone: </td>
                    <td class="details">614-205-9702</td>
                </tr>
                <tr>
                    <td>Contact person: </td>
                    <td class="details">Michael Ripley, Inventory Manager</td>
                </tr>
                <tr>
                    <td>Partners since: </td>
                    <td class="details">August 2017</td>
                </tr>

            </table>
        </div>
        <div class="deal-details">
            <div class="recent-deals-header">
                <h3>Recent Deals</h3> 
                <a href="#" class="more-deals">More Deals ></a>
            </div>
            <div class="recent-deals">
                <table class="displayingTable">
                    <tr>
                        <th>Order #</th>
                        <th>Created at</th>
                        <th>Amount</th>
                    </tr>
                    <tr>
                        <td>2384</td>
                        <td>08/18/2021, 1:08 PM</td>
                        <td>$175,975.00</td>
                    </tr>
                    <tr>
                        <td>2341</td>
                        <td>07/12/2021, 10:54 AM</td>
                        <td>$74,340.00</td>
                    </tr>
                    <tr>
                        <td>2304</td>
                        <td>06/04/2021, 4:35 PM</td>
                        <td>$90,200.00</td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
    <section class="supplier-evaluation-section">
        <h3>Supplier Evaluation</h3>

        <table class="displayingTable">
            <tr>
                <th>Competency</th>
                <th>1 - Poor</th>
                <th>2 - Fair</th>
                <th>3 - Satisfactory</th>
                <th>4 - Good</th>
                <th>5 - Excellent</th>
            </tr>

            <tr>
                <td>Capacity</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox" checked></td>
            </tr>
            <tr>
                <td>Commitment</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox" checked></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Control</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox" checked></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Cost</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox" checked></td>
            </tr>
            <tr>
                <td>Consistency</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox" checked></td>
            </tr>
            <tr>
                <td>Culture</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox" checked></td>
            </tr>
            <tr>
                <td>Communication</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox" checked></td>
            </tr>
            <tr>
                <td colspan="6">
                    <div class="risk-assessment">Risk Assessment: <span class="risk-status">Low</span></div>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                   <div class="overall-rating">Overall rating: 4.75</div>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <button class="save-button">Save</button>
                </td>
            </tr>

        </table>    

    </section>
</div>


<?php
$content = ob_get_clean();
include './InventoryMasterPage.php';
?>
