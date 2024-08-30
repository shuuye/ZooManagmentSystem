<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                media="screen, print, handheld">
    <xsl:output method="html" indent="yes"/>
    
    <!-- Transform the inventory data -->
    <xsl:template match="/">
        <div class="main-content">
            <h2>Inventory</h2>
            <div class="recordingFunctions">
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
            </div>
            <table class="displayingTable">
                <tr>
                    <th>Inventory ID</th>
                    <th>Item Name</th>
                    <th>Item Type</th>
                    <th>Supplier ID</th>
                    <th>Storage Location</th>
                    <th>Reorder Threshold</th>
                </tr>
                <xsl:for-each select="inventories/inventory">
                    <tr>
                        <td>
                            <xsl:value-of select="inventoryId"/>
                        </td>
                        <td>
                            <xsl:value-of select="itemName"/>
                        </td>
                        <td>
                            <xsl:value-of select="itemType"/>
                        </td>
                        <td>
                            <xsl:value-of select="supplierId"/>
                        </td>
                        <td>
                            <xsl:value-of select="storageLocation"/>
                        </td>
                        <td>
                            <xsl:value-of select="reorderThreshold"/>
                        </td>
                        <td>
                            <xsl:value-of select="quantity"/>
                        </td>
                    </tr>
                </xsl:for-each>
            </table>
        </div>
    </xsl:template>
</xsl:stylesheet>