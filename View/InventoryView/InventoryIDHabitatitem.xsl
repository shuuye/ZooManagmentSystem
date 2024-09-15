<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" xmlns:php="http://php.net/xsl">
    <xsl:output method="html" encoding="UTF-8" />

    <!-- Match the root element where all XML files are aggregated -->
    <xsl:template match="/root">
        <div class="main-content">
            <div class="navigation">
                <a class="hrefText" href="#">
                    Habitat Items 
                </a>
                <span>> </span>
                <a class="hrefText" href="#">
                    <xsl:value-of select="inventory[inventoryId = $inventoryID]/itemName" />
                </a>
                <span>> </span>
            </div>
            <div class="recordingFunctions">
                <!-- New Item Button -->
                <div class="new-item" id="newbtn">
                    <a href="?action=addHabitatItem">
                        <img src="../../assests/InventoryImages/btn-plus.svg" class="plus-icon" />
                        New Habitat Item
                    </a>
                </div>
            </div>
            <table class="displayingTable">
                <tr>
                    <th>Inventory ID</th>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Habitat Type</th>
                    <th>Material</th>
                    <th>Expected Life Time</th>
                    <th>Installation Instruction</th>
                    <th>Disposal Instruction</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                <xsl:for-each select="habitatinventory[inventoryId = $inventoryID]">
                    <tr>
                        <td>
                            <xsl:value-of select="inventoryId" />
                        </td>
                        <td>
                            <xsl:value-of select="id" />
                        </td>
                        <td>
                            <a class="hrefText" href="#">
                                <xsl:attribute name="href">
                                    <xsl:value-of select="concat('?action=viewSpecificDetails&amp;inventoryId=', inventoryId, '&amp;itemType=', /root/inventory[inventoryId = $inventoryID]/itemType, '&amp;itemID=', id)" />
                                </xsl:attribute>
                                <xsl:value-of select="habitatItemName" />
                            </a>                           
                        </td>
                        <td>
                            <xsl:value-of select="description" />
                        </td>
                        <td>
                            <xsl:value-of select="habitatType" />
                        </td>
                        <td>
                            <xsl:value-of select="material" />
                        </td>
                        <td>
                            <xsl:value-of select="expected_lifetime" />
                        </td>
                        <td>
                            <xsl:value-of select="installation_instructions" />
                        </td>
                        <td>
                            <xsl:value-of select="disposal_instructions" />
                        </td>
                        <td>
                            <!-- Create PO Button -->
                            <a class="hrefText reorder" href="#">
                                <xsl:attribute name="href">
                                    <xsl:value-of select="concat('?action=createPO&amp;inventoryId=', inventoryId, '&amp;itemType=', /root/inventory[inventoryId = $inventoryID]/itemType, '&amp;itemID=', id)" />
                                </xsl:attribute>
                                <div class="stockstatus createPO">Create PO</div>
                            </a>
                        </td>
                        <td>
                            <!-- Edit Button -->
                            <form action="../../Control/InventoryController/editRecord.php" method="POST" style="display:inline;">
                                <input type="hidden" name="inventoryId" value="{inventoryId}" />
                                <input type="hidden" name="itemID" value="{id}" />
                                <input type="hidden" name="itemType" value="Habitat" />
                                <button type="submit" class="edit-btn">
                                    <img src="../../assests/InventoryImages/btn-edit.svg" class="edit-icon" />
                                </button>
                            </form>
                        </td>
                        <td>
                            <!-- Delete Button -->
                            <form action="../../Control/InventoryController/deleteRecord.php" method="POST" style="display:inline;">
                                <input type="hidden" name="inventoryId" value="{inventoryId}" />
                                <input type="hidden" name="itemID" value="{id}" />
                                <input type="hidden" name="itemType" value="Habitat" />
                                <button type="submit" class="delete-btn">
                                    <img src="../../assests/InventoryImages/btn-close.svg" class="delete-icon" />
                                </button>
                            </form>
                        </td>
                        
                    </tr>
                </xsl:for-each>
            </table>
        </div>
    </xsl:template>
</xsl:stylesheet>
