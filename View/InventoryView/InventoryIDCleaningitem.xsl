<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" xmlns:php="http://php.net/xsl">
    <xsl:output method="html" encoding="UTF-8" />

    <!-- Match the root element where all XML files are aggregated -->
    <xsl:template match="/root">
        <div class="main-content">
            <div class="navigation">
                <a class="hrefText" href="#">
                    Cleaning Supply Items
                </a>
                <span>> </span>
                <a class="hrefText" href="#">
                    <xsl:value-of select="inventory[inventoryId = $inventoryID]/itemName" />
                </a>
                <span>> </span>
            </div>
            <table class="displayingTable">
                <tr>
                    <th></th>
                    <th>Inventory ID</th>
                    <th>Item ID</th>
                    <th>Cleaning Supply Name</th>
                    <th>Size</th>
                    <th>Usage Instructions</th>
                </tr>
                <xsl:for-each select="cleaninginventory[inventoryId = $inventoryID]">
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
                                <xsl:value-of select="cleaningName" />
                            </a>                           
                        </td>
                        <td>
                            <xsl:value-of select="size" />
                        </td>
                        <td>
                            <xsl:value-of select="usageInstructions" />
                        </td>
                    </tr>
                </xsl:for-each>
            </table>
        </div>
    </xsl:template>
</xsl:stylesheet>
