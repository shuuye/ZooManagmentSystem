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
                <h2>Purchase Order Details</h2>
                <p>Generated on Date: <xsl:value-of select="$reportDate"/></p>
            </header>
        
                
            <table class="reportdisplayingTable">
                <tr>
                    <th>PO ID</th>
                    <th>Supplier ID</th>
                    <th>Order Date</th>
                    <th>Delivery Date</th>
                    <th>Billing Address</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Line Item ID</th>
                    <th>Inventory ID</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Sub-Total:</th>
                </tr>
                <xsl:for-each select="/root/purchaseorder">
                    <xsl:variable name="poId" select="poId" />
                    <xsl:variable name="rowCount" select="count(/root/purchaseorderlineitem[poId = $poId])" />
                    
                    <!-- Display the first row of the purchaseorder with rowspan -->
                    <tr>
                        <td rowspan="{$rowCount}">
                            <xsl:value-of select="poId" />
                        </td>
                        <td rowspan="{$rowCount}">
                            <xsl:value-of select="/root/supplier[supplierId = current()/supplierId]/supplierName" />
                        </td>
                        <td rowspan="{$rowCount}">
                            <xsl:value-of select="orderDate" />
                        </td>
                        <td rowspan="{$rowCount}">
                            <xsl:value-of select="deliveryDate" />
                        </td>
                        <td rowspan="{$rowCount}">
                            <xsl:value-of select="billingAddress" />
                        </td>
                        <td rowspan="{$rowCount}">
                            <xsl:value-of select="totalAmount" />
                        </td>

                        <td rowspan="{$rowCount}"> 
                            <a class="rpoStatus">
                                <xsl:choose>
                                    <xsl:when test="status = 'Draft'">
                                        <div class="rpoStatus rdraft">Draft</div>
                                    </xsl:when>
                                    <xsl:when test="status = 'Pending'">
                                        <div class="rpoStatus rprocessing">Processing</div>
                                    </xsl:when>
                                    <xsl:when test="status = 'Completed'">
                                        <div class="rpoStatus rreceived">Completed</div>
                                    </xsl:when>
                                </xsl:choose>
                            </a>
                        </td>
                        
                        <!-- Display the first purchaseorderlineitem in the same row -->
                        <xsl:for-each select="/root/purchaseorderlineitem[poId = $poId]">
                            <xsl:if test="position() = 1">
                                <td>
                                    <xsl:value-of select="poLineItemId" />
                                </td>
                                <td>
                                    <xsl:value-of select="inventoryId" />
                                </td>
                                <xsl:choose>
                                    <xsl:when test="cleaningId and cleaningId != ''">
                                        <td>
                                            <xsl:value-of select="/root/cleaninginventory[id = current()/cleaningId]/cleaningName" />
                                        </td>
                                    </xsl:when>
                                    <xsl:when test="foodId and foodId != ''">
                                        <td>
                                            <xsl:value-of select="/root/foodinventory[id = current()/foodId]/foodName" />
                                        </td>
                                    </xsl:when>
                                    <xsl:when test="habitatId and habitatId != ''">
                                        <td>
                                            <xsl:value-of select="/root/habitatinventory[id = current()/habitatId]/habitatItemName" />
                                        </td>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <td>Not Available</td>
                                    </xsl:otherwise>
                                </xsl:choose>
                                <td>
                                    <xsl:value-of select="quantity" />
                                </td>
                                <td>
                                    <xsl:value-of select="unitPrice" />
                                </td>
                                <td>
                                    <xsl:value-of select="quantity * unitPrice" />
                                </td>
                            </xsl:if>
                            
                            
                        </xsl:for-each>

                        
                    </tr>
                    
                    <!-- Display remaining purchaseorderlineitem records in new rows -->
                    <xsl:for-each select="/root/purchaseorderlineitem[poId = $poId]">
                        <xsl:if test="position() &gt; 1">
                            <tr>
                                <td>
                                    <xsl:value-of select="poLineItemId" />
                                </td>
                                <td>
                                    <xsl:value-of select="inventoryId" />
                                </td>
                                <xsl:choose>
                                    <xsl:when test="cleaningId and cleaningId != ''">
                                        <td>
                                            <xsl:value-of select="/root/cleaninginventory[id = current()/cleaningId]/cleaningName" />
                                        </td>
                                    </xsl:when>
                                    <xsl:when test="foodId and foodId != ''">
                                        <td>
                                            <xsl:value-of select="/root/foodinventory[id = current()/foodId]/foodName" />
                                        </td>
                                    </xsl:when>
                                    <xsl:when test="habitatId and habitatId != ''">
                                        <td>
                                            <xsl:value-of select="/root/habitatinventory[id = current()/habitatId]/habitatItemName" />
                                        </td>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <td>Not Available</td>
                                    </xsl:otherwise>
                                </xsl:choose>
                                <td>
                                    <xsl:value-of select="quantity" />
                                </td>
                                <td>
                                    <xsl:value-of select="unitPrice" />
                                </td>
                                <td>
                                    <xsl:value-of select="quantity * unitPrice" />
                                </td>
                            </tr>
                        </xsl:if>
                    </xsl:for-each>
                    
                </xsl:for-each>
                <tr>
                    <td colspan="9" style="text-align: right;">
                        <strong>Total</strong>
                    </td>
                    <td>
                        <xsl:value-of select="count(/root/purchaseorderlineitem)" />
                    </td>
                    <td>
                        <xsl:value-of select="sum(/root/purchaseorderlineitem/quantity)" />
                    </td>
                    <td>
                        RM <xsl:value-of select="sum(/root/purchaseorderlineitem/unitPrice)" />
                    </td>
                    <td>
                        RM <xsl:value-of select="sum(/root/purchaseorder/totalAmount)" />
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

