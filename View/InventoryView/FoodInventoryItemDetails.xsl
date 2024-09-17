<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" xmlns:php="http://php.net/xsl">
    <xsl:output method="html" encoding="UTF-8" />

    <!-- Match the root element where all XML files are aggregated -->
    <xsl:template match="/root">
        <div class="main-content">
            <div class="navigation">
                <a href="#">Food </a>
                <span>> </span>
                <a href="#">
                    <xsl:value-of select="inventory[inventoryId = $inventoryID]/itemName" />
                </a>
                <span>> </span>
                <a href="#">
                    <xsl:value-of select="foodinventory[id = $itemID]/foodName" />
                </a>
            </div>
            <div class="container">
                <xsl:for-each select="foodinventory[id = $itemID]">
                    <div class="left">
                        <div class="image">
                            <img src="{$imageDirectory}{/root/itemimage[foodId=$itemID]/image_path}"/>
                        </div>
                    </div>
                    <div class="right">
                        <div class="details">
                            <table>
                                <tr>
                                    <th colspan="2" class="productName">
                                        <xsl:value-of select="foodName" />
                                    </th>
                                </tr>
                                <tr>
                                    <th>Last Supply Price</th>
                                    <td >
                                        <xsl:value-of select="/root/purchaseorderlineitem[inventoryId=$inventoryID and foodIdId=$itemID][last()]/unitPrice" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Current Stock</th>
                                    <td>
                                        <xsl:value-of select="/root/inventory[inventoryId=/root/foodinventory[id=$itemID]/inventoryId]/quantity" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td>
                                        <xsl:value-of select="/root/inventory[inventoryId = $inventoryID]/itemName" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nutrition Info</th>
                                    <td>
                                        <xsl:value-of select="nutritionInfo" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Daily Quantity Required</th>
                                    <td>
                                        <xsl:value-of select="daily_quantity_required" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </xsl:for-each>
            </div>
        </div>
    </xsl:template>
</xsl:stylesheet>
