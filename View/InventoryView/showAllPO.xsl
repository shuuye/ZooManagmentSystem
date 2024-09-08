<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="UTF-8" />
    <xsl:template match="/">
        <div class="main-content">
            <h2>Purchase Order</h2>
                
            <table class="displayingTable">
                <tr>
                    <th></th>
                    <th>Purchase Order ID</th>
                    <th>Supplier</th>
                    <th>Order Date</th>
                    <th>Delivery Date</th>
                    <th>Billing Address</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                </tr>
                <xsl:for-each select="purchaseorders/purchaseorder">
                    <xsl:sort select="orderDate" order="descending"/>
                    <tr>
                        <td>
                            <input type="checkbox" name="record[]" value="{poId}" />
                        </td>
                        <td>
                            <xsl:value-of select="poId" />
                        </td>
                        <td>
                            <a class="hrefText">
                                <xsl:value-of select="supplierId" />
                            </a>
                        </td>
                        <td>
                            <xsl:value-of select="orderDate" />
                        </td>
                        <td>
                            <xsl:value-of select="deliveryDate" />
                        </td>
                        <td>
                            <xsl:value-of select="billingAddress" />
                        </td>
                        <td>
                            <xsl:value-of select="totalAmount" />
                        </td>
                        <td> 
                            <a class="hrefText poStatus">
                                <xsl:attribute name="href">
                                    <xsl:value-of select="concat('?action=sendPO&amp;POid=', poId)" />
                                </xsl:attribute>
                                <xsl:choose>
                                    <xsl:when test="status = 'Draft'">
                                        <div class="poStatus draft">Draft</div>
                                    </xsl:when>
                                    <xsl:when test="status = 'Pending'">
                                        <div class="poStatus processing">Processing</div>
                                    </xsl:when>
                                    <xsl:when test="status = 'Completed'">
                                        <div class="poStatus received">Completed</div>
                                    </xsl:when>
                                </xsl:choose>
                            </a>
                        </td>
                    </tr>
                </xsl:for-each>
            </table>
        </div>
    </xsl:template>
</xsl:stylesheet>
