<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="UTF-8" />
    <xsl:template match="/">
        <div class="main-content">
            <h2>Inventory</h2>
            <div class="recordingFunctions">
                <div class="filter">
                    <img src="../../assests/InventoryImages/btn-filter.svg" class="filter-icon" />
                    Filter
                </div>
                <div class="new-item">
                    <a href="?action=addInventoryItem">
                        <img src="../../assests/InventoryImages/btn-plus.svg" class="plus-icon" />
                        New Item
                    </a>
                </div>
                <div class="edit">
                    <img src="../../assests/InventoryImages/btn-edit.svg" class="edit-icon" />
                    Edit
                </div>
            </div>
            <table class="displayingTable">
                <tr>
                    <th></th>
                    <th>Inventory ID</th>
                    <th>Item Name</th>
                    <th>Item Type</th>
                    <th>Supplier ID</th>
                    <th>Storage Location</th>
                    <th>Reorder Threshold</th>
                    <th>Quantity</th>
                </tr>
                <xsl:for-each select="inventories/inventory[itemType = 'Cleaning']">
                    <tr>
                        <td><input type="checkbox" name="record[]" value="{inventoryId}" /></td>
                        <td><xsl:value-of select="inventoryId" /></td>
                        <td><xsl:value-of select="itemName" /></td>
                        <td><xsl:value-of select="itemType" /></td>
                        <td><xsl:value-of select="supplierId" /></td>
                        <td><xsl:value-of select="storageLocation" /></td>
                        <td><xsl:value-of select="reorderThreshold" /></td>
                        <td><xsl:value-of select="quantity" /></td>
                    </tr>
                </xsl:for-each>
            </table>
            <div class="nextpage">
                <div class="prev">
                    <xsl:choose>
                        <xsl:when test="pageNum > 1">
                            <a href="?page={pageNum - 1}">Prev</a>
                        </xsl:when>
                        <xsl:otherwise>&lt; Prev</xsl:otherwise>
                    </xsl:choose>
                </div>
                Page <xsl:value-of select="pageNum" /> of <xsl:value-of select="totalPages" />
                <div class="next">
                    <xsl:choose>
                        <xsl:when test="pageNum &lt; totalPages">
                            <a href="?page={pageNum + 1}">Next &gt;</a>
                        </xsl:when>
                        <xsl:otherwise>Next &gt;</xsl:otherwise>
                    </xsl:choose>
                </div>
            </div>
        </div>
    </xsl:template>
</xsl:stylesheet>
