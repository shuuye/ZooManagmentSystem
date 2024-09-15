<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" xmlns:php="http://php.net/xsl">
    <xsl:output method="html" encoding="UTF-8" />

    <!-- Match the root element where all XML files are aggregated -->
    <xsl:template match="/root">
        <div class="main-content">
            <div class="navigation">
                <a class="hrefText" href="#">
                    Food
                </a>
                <span>> </span>
                <a class="hrefText" href="#">
                    <xsl:value-of select="inventory[inventoryId = $inventoryID]/itemName" />
                </a>
                <span>> </span>
            </div>
            <div class="recordingFunctions">
                <!-- New Brand Button -->
                <div class="new-item" id="newbtn">
                    <a href="?action=addInventoryItem">
                        <img src="../../assests/InventoryImages/btn-plus.svg" class="plus-icon" />
                        New Brand
                    </a>
                </div>
            </div>
            
            <table class="displayingTable">
                <tr>
                    <th></th>
                    <th>Inventory ID</th>
                    <th>Item ID</th>
                    <th>Food Name</th>
                    <th>Nutrition Info</th>
                    <th>Daily Quantity Required</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>
                    
                </tr>
                <xsl:for-each select="foodinventory[inventoryId = $inventoryID]">
                    <tr>
                        <td>
                            <input type="checkbox" name="record[]" value="{inventoryId}" />
                        </td>
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
                                <xsl:value-of select="foodName" />
                            </a>                           
                        </td>
                        <td>
                            <xsl:value-of select="nutritionInfo" />
                        </td>
                        <td>
                            <xsl:value-of select="daily_quantity_required" />
                        </td>
                        <td> 
                            <a class="hrefText reorder">
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
                                <input type="hidden" name="itemType" value="Cleaning" />
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
                                <input type="hidden" name="itemType" value="Cleaning" />
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
