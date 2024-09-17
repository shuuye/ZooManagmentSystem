<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="UTF-8" />

    <!-- Match the root element where all XML files are aggregated -->
    <xsl:template match="/root">
        <div class="main-content">
            <xsl:for-each select="purchaseorder[poId = $POid]">
                <div class="purchase-order">
                    <div class="navigation">
                        <span>
                            <h2>
                                <a class="hrefText" href="#">
                                    Purchase Order
                                </a> New Purchase Order #<xsl:value-of select="poId" />
                            </h2> 
                        </span> 
                    </div>
                    <div class="statusBar">
                        <div class="status">
                            <span class="label">Status</span>
                            <xsl:choose>
                                <xsl:when test="status = 'Draft'">
                                    <span class="poStatus draft">Draft</span>
                                </xsl:when>
                                <xsl:when test="status = 'Pending'">
                                    <span class="poStatus processing">Processing</span>
                                </xsl:when>
                                <xsl:when test="status = 'Completed'">
                                    <span class="poStatus received">Completed</span>
                                </xsl:when>
                                <xsl:otherwise>
                                    <span class="poStatus unknown">Unknown</span>
                                </xsl:otherwise>
                            </xsl:choose>
                        </div>
                        <div class="actions">
                            <div class="cancelorder" id="cancelbtn">
                                <xsl:choose>
                                    <xsl:when test="status = 'Completed' or status = 'Pending'">
                                        
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <!-- Use xsl:attribute to construct the href attribute -->
                                        <a>
                                            <xsl:attribute name="href">
                                                <xsl:value-of select="concat('C:\xampp\htdocs\ZooManagementSystem\Control\InventoryController\cancelOrder.php?POid=', poId)" />
                                            </xsl:attribute>
                                            <img src="/ZooManagementSystem/assests/InventoryImages/btn-close.svg" class="plus-icon" />
                                            Cancel order
                                        </a>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </div>

                            <!-- Send for Approval Button -->
                            <xsl:choose>
                                <xsl:when test="status = 'Draft'">
                                    <a class="sendBtn received" href="{concat('C:\xampp\htdocs\ZooManagementSystem\Control\InventoryController\approve.php?POid=', poId)}">
                                        Send for approval
                                    </a>
                                </xsl:when>
                                <xsl:when test="status = 'Pending'">
                                    <a class="sendBtn processing" href="{concat('C:\xampp\htdocs\ZooManagementSystem\Control\InventoryController\receive.php?POid=', poId)}">
                                        Received
                                    </a>
                                </xsl:when>
                                <xsl:when test="status = 'Completed'">
                                    
                                </xsl:when>
                            </xsl:choose>
                        </div>
                    </div>
                    <div class="poLayout">
                        <div class="poHeader">
                            <div>
                                <h2>PURCHASE ORDER</h2>
                            </div>
                            <div class="details">
                                <div class="column left">
                                    <div class="value">
                                        <xsl:value-of select="shippingAddress" />
                                    </div>
                                    <div class="value">
                                        <xsl:value-of select="/root/supplier[supplierId = purchaseorder/supplierId]/contactPhone" />
                                    </div>
                                </div>
                                <div class="column right">
                                    <table>
                                        <tr>
                                            <th class="label">Created On Date:</th>
                                            <td class="value">
                                                <xsl:value-of select="orderDate" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="label">Preferred Shipping Date:</th>
                                            <td class="value">
                                                <xsl:value-of select="deliveryDate" />
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="details">
                            <div class="column">
                                <table class="poAddressTable">
                                    <tr>
                                        <th colspan="2">To</th>
                                    </tr>
                                    <tr>
                                        <td class="label">Company:</td>
                                        <td>
                                            <xsl:value-of select="/root/supplier[supplierId = current()/supplierId]/supplierName" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">Address:</td>
                                        <td>
                                            <xsl:value-of select="billingAddress" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">Phone:</td>
                                        <td>
                                            <xsl:value-of select="/root/supplier[supplierId = current()/supplierId]/contactPhone" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="column">
                                <table class="poAddressTable">
                                    <tr>
                                        <th colspan="2">Ship To</th>
                                    </tr>
                                    <tr>
                                        <td class="label">Company:</td>
                                        <td>National Zoo of Malaysia</td>
                                    </tr>
                                    <tr>
                                        <td class="label">Address:</td>
                                        <td>
                                            <xsl:value-of select="shippingAddress" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">Phone:</td>
                                        <td> 03-4108 3422</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <xsl:for-each select="/root/purchaseorderlineitem[poId = $POid]">
                            <h3>Order Details</h3>
                            <table class="displayingTable">
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                                <tr>
                                    <td>
                                        <xsl:choose>
                                            <!-- Check for Cleaning Items -->
                                            <xsl:when test="cleaningId and cleaningId != ''">
                                                <xsl:value-of select="/root/cleaninginventory[id = current()/cleaningId]/cleaningName" />
                                            </xsl:when>

                                            <!-- Check for Food Items -->
                                            <xsl:when test="foodId and foodId != ''">
                                                <xsl:value-of select="/root/foodinventory[id = current()/foodId]/foodName" />
                                            </xsl:when>

                                            <!-- Check for Habitat Items -->
                                            <xsl:when test="habitatId and habitatId != ''">
                                                <xsl:value-of select="/root/habitatinventory[id = current()/habitatId]/habitatItemName" />
                                            </xsl:when>
                                        </xsl:choose>
                                    </td>
                                    <td>
                                        <xsl:value-of select="unitPrice" />
                                    </td>
                                    <td>
                                        <xsl:value-of select="quantity" />
                                    </td>
                                    <td>
                                        <xsl:value-of select="format-number(quantity * unitPrice, '#.00')" />
                                    </td>
                                </tr>
                            </table>
                        </xsl:for-each>

                        <div class="total-section">
                            <table class="total">
                                <tr>
                                    <th class="label">Subtotal</th>
                                    <td>
                                        RM
                                        <xsl:value-of select="totalAmount" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="label">Shipping</th>
                                    <td>RM 0.00</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td class="value">RM 
                                        <xsl:value-of select="totalAmount" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </xsl:for-each>
        </div>
    </xsl:template>
</xsl:stylesheet>
