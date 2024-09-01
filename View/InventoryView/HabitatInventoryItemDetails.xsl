<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" xmlns:php="http://php.net/xsl">
    <xsl:output method="html" encoding="UTF-8" />

    <!-- Match the root element where all XML files are aggregated -->
    <xsl:template match="/root">
        <div class="main-content">
            <div class="navigation">
                <a href="#">Habitat Decoration Items</a>
                <span>> </span>
                <a href="#">
                    <xsl:value-of select="inventory[inventoryId = $inventoryID]/itemName" />
                </a>
                <span>> </span>
                <a href="#">
                    <xsl:value-of select="habitatinventory[id = $itemID]/habitatItemName" />
                </a>
            </div>
            <div class="container">
                <xsl:for-each select="habitatinventory[id = $itemID]">
                    <div class="left">
                        <div class="image">
                           <img src="{$imageDirectory}{/root/itemimage[habitatId=$itemID]/image_path}"/>
                        </div>
                       
                        <div class="description">
                            <h3>Description</h3>
                            <ul>
                                <li>
                                    <xsl:value-of select="description" />
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="right">
                        <div class="details">
                            <table>
                                <tr>
                                    <th colspan="2" class="productName">
                                        <xsl:value-of select="habitatItemName" />
                                    </th>
                                </tr>
                                <tr>
                                    <th>Last Supply Price</th>
                                    <td >
                                        <xsl:value-of select="/root/purchaseorderlineitem[inventoryId=$inventoryID and habitatId=$itemID][last()]/unitPrice" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Current Stock</th>
                                    <td>
                                        <xsl:value-of select="/root/inventory[inventoryId=/root/habitatinventory[id=$itemID]/inventoryId]/quantity" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td>
                                        <xsl:value-of select="/root/inventory[inventoryId = $inventoryID]/itemName" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Habitat Type</th>
                                    <td>
                                        <xsl:value-of select="habitatType" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Material</th>
                                    <td>
                                        <xsl:value-of select="material" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Expected LifeTime</th>
                                    <td>
                                        <xsl:value-of select="expected_lifetime" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Instalation Instructions</th>
                                    <td>
                                        <xsl:value-of select="installation_instructions" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Disposal Instructions</th>
                                    <td>
                                        <xsl:value-of select="disposal_instructions" />
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
