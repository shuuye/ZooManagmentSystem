<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="UTF-8" />
    <xsl:template match="/">
        <div class="filter-options">
            <form id="filterForm" action="../../Control/InventoryController/filterReport.php" method="POST">
                <label for="itemType">Filter by Item Type:</label>
                <select id="itemType" name="filters">
                    <option value="">All</option>
                    <option value="Food">Food</option>
                    <option value="Habitat">Habitat</option>
                    <option value="Cleaning">Cleaning</option>
                    <option value="lowStock">Low Stock</option>
                    <option value="inStock">In Stock</option>
                    <option value="outStock">Out of Stock</option>
                </select>
                <button type="submit">Apply Filter</button>
            </form>
        </div>
        <div class="outsideReport">
            <button class="printbtn" onclick="printReport()">Print Report</button>
        </div>        
        <div class="main-content">
            <header class="report-header">
                <h2>Inventory Summary Report</h2>
                <p>Generated on Date: <xsl:value-of select="$reportDate"/></p>
            </header>
                
            <table class="reportdisplayingTable">
                <tr>
                    <th>Inventory ID</th>
                    <th>Item Name</th>
                    <th>Item Type</th>
                    <th>Storage Location</th>
                    <th>Reorder Threshold</th>
                    <th>Quantity</th>
                    <th>Status</th>
                </tr>
                <xsl:for-each select="inventories/inventory[quantity &gt;= reorderThreshold]">
                    <xsl:sort select="itemType" order="descending" />
                    <tr>
                        <td>
                            <xsl:value-of select="inventoryId" />
                        </td>
                        <td>
                           
                            <xsl:value-of select="itemName" />
                           
                        </td>
                        <td>
                            
                            <xsl:value-of select="itemType" />
                            
                        </td>
                        <td>
                            <xsl:value-of select="storageLocation" />
                        </td>
                        <td>
                            <xsl:value-of select="reorderThreshold" />
                        </td>
                        <td>
                            <xsl:value-of select="quantity" />
                        </td>
                        <td> 
                            <a class="reorder">
                                <xsl:choose>
                                    <xsl:when test="quantity &lt;= 0">
                                        <div class="rstockstatus routstock">Out of Stock</div>
                                    </xsl:when>
                                    <xsl:when test="quantity &lt;= reorderThreshold">
                                        <div class="rstockstatus rlowstock">Low Stock</div>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <div class="rstockstatus rinstock">In Stock</div>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </a>
                            
                        </td>
                    </tr>
                </xsl:for-each>
                <tr>
                    <td colspan="5">
                        Total
                    </td>
                    <td>
                        <xsl:value-of select="sum(inventories/inventory[quantity &gt;= reorderThreshold]/quantity)"/>
                    </td>
                    <td>
                        <xsl:value-of select="count(inventories/inventory[quantity &gt;= reorderThreshold])"/>
                    </td>
                </tr>
            </table>
            

        </div>
        
        <script>
           

            function printReport() {
            window.print();
            }

          
        </script>

    </xsl:template>
</xsl:stylesheet>
