<!DOCTYPE html>
<html>
    <head>
       <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Purchase Order</title>
        <link rel="stylesheet" href="../Css/Inventory/poTracking.css">
    </head>
    <body>
        <div class="container">
            <div class="sidebar">
                <h1>Inventory Catalog</h1>
                <ul>
                    <li><a href="#">Purchase Orders</a></li>
                    <li><a href="#">Sales Orders</a></li>
                    <li><a href="#">Package</a></li>
                    <li><a href="#">Shipment</a></li>
                    <li><a href="#">Reports</a></li>
                </ul>
            </div>
            <div class="content">
                <div class="header">
                    <div class="header-title">Purchase Orders</div>
                    <div class="header-options">
                        <div class="header-option">
                            <i class="table-action-icon"></i>Filter
                        </div>
                        <div class="header-option">
                            <i class="table-action-icon"></i>New Purchase Order
                        </div>
                        <div class="header-option">
                            <i class="table-action-icon"></i>Edit
                        </div>
                    </div>
                </div>
                <div class="profile">
                    <img src="https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?s=200&d=mm&r=g" class="profile-image" alt="Profile Image">
                    <div>
                        <div class="profile-name">Chester Perry</div>
                        <div class="profile-role">Inventory Specialist</div>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Supplier</th>
                            <th>Supplier Rating</th>
                            <th>Created by</th>
                            <th>Created at</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2660</td>
                            <td>Wells & Wade</td>
                            <td>4.75</td>
                            <td>Michael Ripley</td>
                            <td>08/18/2021, 1:08 PM</td>
                            <td>$175,975.00</td>
                            <td><div class="table-status table-status-received">Received</div></td>
                        </tr>
                        <tr>
                            <td>2659</td>
                            <td>Gibson Wood</td>
                            <td>4.75</td>
                            <td>Allen Jones</td>
                            <td>08/17/2021, 12:03 PM</td>
                            <td>$22,500.25</td>
                            <td><div class="table-status table-status-processing">Order Processing</div></td>
                        </tr>
                        <tr>
                            <td>2658</td>
                            <td>Timber Smart</td>
                            <td>4.75</td>
                            <td>Jose Mendez</td>
                            <td>08/13/2021, 9:15 AM</td>
                            <td>$40,228.00</td>
                            <td><div class="table-status table-status-processing">Order Processing</div></td>
                        </tr>
                        <tr>
                            <td>2657</td>
                            <td>Pillar Engineered</td>
                            <td>4.75</td>
                            <td>Tito Cubero</td>
                            <td>08/12/2021, 2:42 PM</td>
                            <td>$10,450.68</td>
                            <td><div class="table-status table-status-awaiting">Awaiting Shipment</div></td>
                        </tr>
                        <tr>
                            <td>2656</td>
                            <td>Bastion Wood</td>
                            <td>4.75</td>
                            <td>Trisha Boone</td>
                            <td>08/12/2021, 10:22 AM</td>
                            <td>$12,240.55</td>
                            <td><div class="table-status table-status-awaiting">Awaiting Shipment</div></td>
                        </tr>
                        <tr>
                            <td>2655</td>
                            <td>Wells & Wade</td>
                            <td>4.75</td>
                            <td>Michael Ripley</td>
                            <td>08/10/2021, 10:54 AM</td>
                            <td>$8,500.25</td>
                            <td><div class="table-status table-status-received">Received</div></td>
                        </tr>
                        <tr>
                            <td>2654</td>
                            <td>Woodscape</td>
                            <td>4.63</td>
                            <td>Allen Jones</td>
                            <td>08/09/2021, 1:16 PM</td>
                            <td>$120,840.00</td>
                            <td><div class="table-status table-status-received">Received</div></td>
                        </tr>
                        <tr>
                            <td>2653</td>
                            <td>Wells & Wade</td>
                            <td>4.75</td>
                            <td>Michael Ripley</td>
                            <td>08/06/2021, 4:01 PM</td>
                            <td>$9,760.45</td>
                            <td><div class="table-status table-status-received">Received</div></td>
                        </tr>
                        <tr>
                            <td>2652</td>
                            <td>Woodenlax</td>
                            <td>4.63</td>
                            <td>Trisha Boone</td>
                            <td>08/06/2021, 10:18 AM</td>
                            <td>$10,400.25</td>
                            <td><div class="table-status table-status-received">Received</div></td>
                        </tr>
                        <tr>
                            <td>2651</td>
                            <td>Woodenlax</td>
                            <td>4.63</td>
                            <td>Trisha Boone</td>
                            <td>07/27/2021, 11:54 AM</td>
                            <td>$8,302.18</td>
                            <td><div class="table-status table-status-received">Received</div></td>
                        </tr>
                    </tbody>
                </table>
                <div class="pagination">
                    <div class="pagination-button">Prev</div>
                    <div class="pagination-button">Page 1 of 265</div>
                    <div class="pagination-button">Next</div>
                </div>
            </div>
        </div>
    </body>
</html>