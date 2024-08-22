<!-- 
for inventory tracking
- have animal, habitat, food, and cleaning
- to track which stock level is too low to reorder again

-->

<?php
$activePage = 'Purchase Order';
$pageCss = 'mainInventoryTrackinig.css';
ob_start();
?>


<div class="main-content">

    <h2>Purchase Order</h2>


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
            <th>Order #</th>
            <th>Supplier</th>
            <th>Supplier Rating</th>
            <th>Created by</th>
            <th>Created at</th>
            <th>Amount</th>
            <th>Status</th>
        </tr>
        <tr>
            <td><input type="checkbox"></td>
            <td>2660</td>
            <td>Wells & Wade</td>
            <td>4.75</td>
            <td>Michael Ripley</td>
            <td>08/18/2021, 1:08 PM</td>
            <td>$175,975.00</td>
            <td >
                <div class="poStatus approval"> 
                    Sent for Approval
                </div>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox"></td>
            <td>2659</td>
            <td>Gibson Wood</td>
            <td>4.75</td>
            <td>Allen Jones</td>
            <td>08/17/2021, 12:03 PM</td>
            <td>$22,500.25</td>
            <td >
                <div class="poStatus processing"> 
                    Order Processing
                </div>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox"></td>
            <td>2658</td>
            <td>Timber Smart</td>
            <td>4.75</td>
            <td>Jose Mendez</td>
            <td>08/13/2021, 9:15 AM</td>
            <td>$40,228.00</td>
            <td >
                <div class="poStatus processing"> 
                    Order Processing
                </div>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox"></td>
            <td>2657</td>
            <td>Pillar Engineered</td>
            <td>4.75</td>
            <td>Tito Cubero</td>
            <td>08/12/2021, 2:42 PM</td>
            <td>$10,450.68</td>
            <td >
                <div class="poStatus awaiting"> 
                    Awaiting Shipment
                </div>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox"></td>
            <td>2656</td>
            <td>Bastion Wood</td>
            <td>4.75</td>
            <td>Trisha Boone</td>
            <td>08/12/2021, 10:22 AM</td>
            <td>$12,240.55</td>
            <td >
                <div class="poStatus awaiting"> 
                    In Stock
                </div>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox"></td>
            <td>2655</td>
            <td>Wells & Wade</td>
            <td>4.75</td>
            <td>Michael Ripley</td>
            <td>08/10/2021, 10:54 AM</td>
            <td>$8,500.25</td>
            <td >
                <div class="poStatus received"> 
                    Received
                </div>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox"></td>
            <td>2654</td>
            <td>Woodscape</td>
            <td>4.63</td>
            <td>Allen Jones</td>
            <td>08/09/2021, 1:16 PM</td>
            <td>$120,840.00</td>            
            <td >
                <div class="poStatus received"> 
                    Received
                </div>
            </td>
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



