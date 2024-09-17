<?php
define('INVENTORY_PAGE_PATH', '../InventoryView/');
define('INVENTORY_CSS_PATH', '../../Css/Inventory/');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $activePage; ?></title>
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
                        <a href="?action=index" class="dropdown-toggle" data-toggle="dropdown">Dashboard <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="?action=index">Dashboard</a></li>
                        </ul>
                    </li>
                    <li class="dropdown <?php echo ($activePage == 'Inventory Management') ? 'active' : ''; ?>">
                        <a href="?action=inventoryTracking" class="dropdown-toggle" data-toggle="dropdown">Inventory Management <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown"><a href="?action=habitatItem">Habitat Items</a>
                                <ul class="dropdown-submenu">
                                    <li><a href="?action=habitatItem">View All</a></li>
                                    <li><a href="?action=generateReport&report=habitatinventoryRecordReport">Generate Report</a></li>
                                </ul>
                            </li>
                            <li><a href="?action=foodItem">Food Inventory</a>
                                <ul class="dropdown-submenu">
                                    <li><a href="?action=foodItem">View All</a></li>
                                    <li><a href="?action=generateReport&report=foodinventoryRecordReport">Generate Report</a></li>
                                </ul>
                            </li>
                            <li><a href="?action=cleaningItem">Cleaning Inventory</a>
                                <ul class="dropdown-submenu">
                                    <li><a href="?action=cleaningItem">View All</a></li>
                                    <li><a href="?action=generateReport&report=cleaninginventoryRecordReport">Generate Report</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Animal Inventory</a>
                                <ul class="dropdown-submenu">
                                    <li><a href="view-all-animals.php">View All</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown <?php echo ($activePage == 'Log Usage') ? 'active' : ''; ?>">
                        <a href="?action=logusage" class="dropdown-toggle" data-toggle="dropdown">Log Usage <b class="caret"></b></a>
                    </li>
                    <li class="dropdown <?php echo ($activePage == 'Purchase Order Management') ? 'active' : ''; ?>">
                        <a href="?action=showPO" class="dropdown-toggle" data-toggle="dropdown">Purchase Order Management <b class="caret"></b></a>
<!--                        <ul class="dropdown-menu">
                            <li><a href="create-new-order.php">Create New Order</a></li>
                            <li><a href="view-all-orders.php">View All Orders</a></li>
                            <li><a href="order-history.php">Order History</a></li>
                            <li><a href="supplier-management.php">Supplier Management</a></li>
                        </ul>-->
                    </li>
                    <li class="dropdown <?php echo ($activePage == 'Reports') ? 'active' : ''; ?>">
                        <a href="?action=generateReport&report=inventorySummaryReport" class="dropdown-toggle" data-toggle="dropdown">Reports <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="?action=generateReport&report=inventorySummaryReport">Inventory Summary Report</a></li>
                            <li><a href="?action=generateReport&report=cleaninginventoryRecordReport">Cleaning Inventory Summary Report</a></li>
                            <li><a href="?action=generateReport&report=habitatinventoryRecordReport">Habitat Inventory Summary Report</a></li>
                            <li><a href="?action=generateReport&report=foodinventoryRecordReport">Food Inventory Summary Report</a></li>
                            <li><a href="?action=generateReport&report=poSummaryReport">Purchase Order Summary Report</a></li>
                        </ul>
                    </li>

                </ul>
            </nav>
            <h2> <?php echo $activePage; ?></h2>
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
