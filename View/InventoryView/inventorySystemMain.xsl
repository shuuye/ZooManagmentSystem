<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="UTF-8" />
    <xsl:template match="/">  
        <div class="main-content">          
            <div class="kpi-container">
                <h2>Inventory Management Status</h2>
                <div class="allkpi-container">
                    <div class="kpi">
                        <p>Total Inventory</p>
                        <p>
                            <xsl:value-of select="count(/root/inventory)"/>
                        </p>
                    </div>
                    <div class="kpi">
                        <p>Current In Stock</p>
                        <p>
                            <xsl:value-of select="count(/root/inventory[quantity &gt;= reorderThreshold])"/>
                        </p>
                    </div>
                    <div class="kpi">
                        <p>Current Low Stock</p>
                        <p>
                            <xsl:value-of select="count(/root/inventory[quantity &lt;= reorderThreshold])"/>
                        </p>
                    </div>
                    <div class="kpi">
                        <p>Current Out of Stock</p>
                        <p>
                            <xsl:value-of select="count(/root/inventory[quantity &lt;= 0])"/>
                        </p>
                    </div>
                    <div class="kpi">
                        <p>Processing Purchase Order</p>
                        <p>
                            <xsl:value-of select="count(/root/purchaseorder[status = 'Pending'])"/>
                        </p>
                    </div>
                    <div class="kpi">
                        <p>Processing PO Cost</p>
                        <p>RM <xsl:value-of select="sum(/root/purchaseorder[status = 'Pending']/totalAmount)"/></p>
                    </div>
                </div>
            </div>
            <div class="charts">
                <div class="chart">
                    <h2>Inventory</h2>
                    <canvas id="myPieChart" width="50" height="30"></canvas>
                </div>
                <div class="product-section">
                    <h2>Recent Used</h2>
                    <table class="displayingTable product">
                        <tr>
                            <th>Photos</th>
                            <th>Item</th>
                            <th>Date Time</th>
                            <th>Quantity</th>
                        </tr>
                        <xsl:for-each select="/root/inventoryusagelog[position() &gt;= last() - 4]">
                            <xsl:variable name="currentInventoryId" select="inventoryId" />
                            <xsl:variable name="itemType" select="/root/inventory[inventoryId=$currentInventoryId]/itemType" />
                            <xsl:variable name="imagePath">
                                <xsl:choose>
                                    <xsl:when test="$itemType = 'Cleaning'">
                                        <xsl:value-of select="/root/itemimage[cleaningId = /root/cleaninginventory[inventoryId = $currentInventoryId]/id]/image_path" />
                                    </xsl:when>
                                    <xsl:when test="$itemType = 'Food'">
                                        <xsl:value-of select="/root/itemimage[foodId = /root/foodinventory[inventoryId = $currentInventoryId]/id]/image_path" />
                                    </xsl:when>
                                    <xsl:when test="$itemType = 'Habitat'">
                                        <xsl:value-of select="/root/itemimage[habitatId = /root/habitatinventory[inventoryId = $currentInventoryId]/id]/image_path" />
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <xsl:text>No Image</xsl:text>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </xsl:variable>
                            <tr>
                                <td>
                                    <img src="{concat('../../assests/InventoryImages/', $imagePath)}" alt="Product Image" />
                                </td>
                                <td>
                                    <xsl:choose>
                                        <xsl:when test="/root/cleaninginventory[inventoryId = $currentInventoryId]">
                                            <xsl:value-of select="/root/cleaninginventory[inventoryId = $currentInventoryId]/cleaningName" />
                                        </xsl:when>
                                        <xsl:when test="/root/foodinventory[inventoryId = $currentInventoryId]">
                                            <xsl:value-of select="/root/foodinventory[inventoryId = $currentInventoryId]/foodName" />
                                        </xsl:when>
                                        <xsl:when test="/root/habitatinventory[inventoryId = $currentInventoryId]">
                                            <xsl:value-of select="/root/habitatinventory[inventoryId = $currentInventoryId]/habitatItemName" />
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <xsl:text>Unknown</xsl:text>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </td>
                                <td>
                                    <xsl:value-of select="dateTime" />
                                </td>
                                <td>
                                    <xsl:value-of select="quantityUsed" />
                                </td>
                            </tr>
                        </xsl:for-each>
                    </table>
                </div>
            </div>
        </div>  

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Calculate counts using XSLT
            var inStockItems = <xsl:value-of select="count(/root/inventory[quantity &gt;= reorderThreshold])" />;
            var outOfStockItems = <xsl:value-of select="count(/root/inventory[quantity &lt;= 0])" />;
            var lowStockItems = <xsl:value-of select="count(/root/inventory[quantity &lt;= reorderThreshold and quantity &gt; 0])" />;

            // Initialize the chart with these counts
            var ctx = document.getElementById('myPieChart').getContext('2d');
            var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
            labels: ['In Stock Items', 'Out of Stock Items', 'Low Stock Items'],
            datasets: [{
            data: [inStockItems, outOfStockItems, lowStockItems], // Use XSLT values here
            backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56'],
            }]
            },
            options: {
            responsive: true,
            plugins: {
            legend: {
            display: true,
            position: 'bottom'
            }
            }
            }
            });
        </script>
    </xsl:template>
</xsl:stylesheet>
