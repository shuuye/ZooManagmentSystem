<?php
define('INVENTORY_PAGE_PATH', '../InventoryView/');
define('INVENTORY_CSS_PATH', '../../Css/Inventory/');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo ($activePage == 'Dashboard') ? 'active' : ''; ?></title>
        <link rel="stylesheet" href="../../Css/Inventory/InventoryMasterPage.css">
        <link rel="stylesheet" href="../../Css/Inventory/displayingTable.css">
        <?php
        if (isset($pageCss)) { 
            echo '<link rel="stylesheet" href="' . INVENTORY_CSS_PATH . $pageCss . '">';
        }
        ?>
    </head>
    <body>
        <header>

            <nav class="inventoryNav">
                <ul>
                    <li class="dropdown <?php echo ($activePage == 'Dashboard') ? 'active' : ''; ?>">
                        <a href="<?php echo INVENTORY_PAGE_PATH; ?>inventorySystemMain.php" class="dropdown-toggle" data-toggle="dropdown">Dashboard <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="dashboard.php">Dashboard</a></li>
                        </ul>
                    </li>
                    <li class="dropdown <?php echo ($activePage == 'Inventory Management') ? 'active' : ''; ?>">
                        <a href="?action=index" class="dropdown-toggle" data-toggle="dropdown">Inventory Management <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown"><a href="?action=habitatItem">Habitat Items</a>
                                <ul class="dropdown-submenu">
                                    <li><a href="?action=habitatItem">View All</a></li>
                                    <li><a href="add-new-habitat-item.php">Add New Item</a></li>
                                    <li><a href="bulk-update-habitat-items.php">Bulk Update</a></li>
                                    <li><a href="generate-habitat-items-report.php">Generate Report</a></li>
                                </ul>
                            </li>
                            <li><a href="?action=foodItem">Food Inventory</a>
                                <ul class="dropdown-submenu">
                                    <li><a href="?action=foodItem">View All</a></li>
                                    <li><a href="add-new-food-item.php">Add New Item</a></li>
                                    <li><a href="bulk-update-food-items.php">Bulk Update</a></li>
                                    <li><a href="generate-food-items-report.php">Generate Report</a></li>
                                </ul>
                            </li>
                            <li><a href="?action=cleaningItem">Cleaning Inventory</a>
                                <ul class="dropdown-submenu">
                                    <li><a href="?action=cleaningItem">View All</a></li>
                                    <li><a href="add-new-cleaning-item.php">Add New Item</a></li>
                                    <li><a href="bulk-update-cleaning-items.php">Bulk Update</a></li>
                                    <li><a href="generate-cleaning-items-report.php">Generate Report</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Animal Inventory</a>
                                <ul class="dropdown-submenu">
                                    <li><a href="view-all-animals.php">View All</a></li>
                                    <li><a href="add-new-animal.php">Add New Animal</a></li>
                                    <li><a href="bulk-update-animals.php">Bulk Update</a></li>
                                    <li><a href="generate-animals-report.php">Generate Report</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown <?php echo ($activePage == 'Item Details') ? 'active' : ''; ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Item Details <b class="caret"></b></a>
                        <ul class="dropdown-menu"> 
                            <li><a href="habitat-item-details.php">Habitat Item Details</a></li>
                            <li><a href="food-item-details.php">Food Item Details</a></li>
                            <li><a href="?action=viewCleaningDetails">Cleaning Item Details</a></li>
                            <li><a href="animal-item-details.php">Animal Item Details</a></li>
                        </ul>
                    </li>
                    <li class="dropdown <?php echo ($activePage == 'Reports') ? 'active' : ''; ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="summary-report.php">Summary Report</a></li>
                            <li><a href="supplier-and-purchase-history-report.php">Supplier and Purchase History Report</a></li>
                            <li><a href="stock-report.php">Stock Report</a></li>
                            <li><a href="inventory-cost-analysis-report.php">Inventory Cost Analysis Report</a></li>
                            <li><a href="reorder-and-procurement-report.php">Reorder and Procurement Report</a></li>
                            <li><a href="inventory-usage-and-waste-report.php">Inventory Usage and Waste Report</a></li>
                        </ul>
                    </li>
                    <li class="dropdown <?php echo ($activePage == 'Purchase Order Management') ? 'active' : ''; ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Purchase Order Management <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="create-new-order.php">Create New Order</a></li>
                            <li><a href="view-all-orders.php">View All Orders</a></li>
                            <li><a href="order-history.php">Order History</a></li>
                            <li><a href="supplier-management.php">Supplier Management</a></li>
                        </ul>
                    </li>
                    <li class="dropdown <?php echo ($activePage == 'Settings') ? 'active' : ''; ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Settings <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="system-settings.php">System Settings</a></li>
                            <li><a href="email-settings.php">Email Settings</a></li>
                            <li><a href="payment-gateway-settings.php">Payment Gateway Settings</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <h1> <?php echo $activePage; ?></h1>
        </header>
        <main>
            <?php

            if (!$xslt_transform) {
                echo $content;
            }
            ?>
        </main>

    </body>
</html>

<script>
    $(document).ready(function () {
        $('.dropdown-toggle').click(function (e) {
            e.preventDefault();
            $(this).parent().toggleClass('open');
        });
    });
</script>
