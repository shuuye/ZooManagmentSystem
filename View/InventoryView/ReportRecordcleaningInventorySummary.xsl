<?xml version="1.0" encoding="UTF-8"?>
<!-- Author name: Lim Shuye -->
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="UTF-8" />
    <xsl:template match="/">
        <div class="outsideReport">
            <button class="printbtn" onclick="printReport()">Print Report</button>
        </div>        
        <div class="main-content">
            <header class="report-header">
                <h2>Cleaning Supply Brands Inventory Summary Report</h2>
                <p>Generated on Date: <xsl:value-of select="$reportDate"/></p>
            </header>
                
            <table class="reportdisplayingTable">
                <tr>
                    <th>Inventory ID</th>
                    <th>Item Name</th>
                    <th>ID </th>
                    <th>Brand Name </th>
                    <th>Size </th>
                    <th>Sub-Total:</th>
                    
                </tr>
                <xsl:for-each select="/root/inventory[itemType = 'Cleaning']">
                    <xsl:sort select="itemType" order="descending" />
                    <xsl:variable name="rowCount" select="count(/root/cleaninginventory[inventoryId = current()/inventoryId])" />
    
                    <!-- Display the first row of the inventory and rowspan for cleaningInventory records -->
                    <tr>
                        <!-- Inventory details row with rowspan -->
                        <td rowspan="{$rowCount}">
                            <xsl:value-of select="inventoryId" />
                        </td>
                        <td rowspan="{$rowCount}">
                            <xsl:value-of select="itemName" />
                        </td>

                        <!-- Display the first cleaningInventory record in the same row -->
                        <xsl:for-each select="/root/cleaninginventory[inventoryId = current()/inventoryId]">
                            <!-- Only display the first record in this first row -->
                            <xsl:if test="position() = 1">
                                <td>
                                    <xsl:value-of select="id" />
                                </td>
                                <td>
                                    <xsl:value-of select="cleaningName" />
                                </td>
                                <td>
                                    <xsl:value-of select="size" />
                                </td>
                            </xsl:if>
                        </xsl:for-each>
                        <td rowspan="{$rowCount}">
                            <xsl:value-of select="count(/root/cleaninginventory[inventoryId = current()/inventoryId])" />
                        </td>
                    </tr>

                    <!-- Now display the remaining cleaningInventory records in new rows -->
                    <xsl:for-each select="/root/cleaninginventory[inventoryId = current()/inventoryId]">
                        <!-- Skip the first cleaningInventory record as it has already been displayed -->
                        <xsl:if test="position() &gt; 1">
                            <tr>
                                <td>
                                    <xsl:value-of select="id" />
                                </td>
                                <td>
                                    <xsl:value-of select="cleaningName" />
                                </td>
                                <td>
                                    <xsl:value-of select="size" />
                                </td>
                            </tr>
                        </xsl:if>
                    </xsl:for-each>
                </xsl:for-each>



                <tr>
                    <td colspan="5">
                        Total
                    </td>
                    <td>
                        <xsl:value-of select="count(/root/cleaninginventory)"/>
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
