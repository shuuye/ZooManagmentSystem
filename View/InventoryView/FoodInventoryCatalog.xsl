<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="UTF-8" />
    <xsl:template match="/">
        <div class="main-content">
            <h2>Inventory</h2>
            <div class="recordingFunctions">
                <div class="filter" id="filterbtn">
                    <img src="../../assests/InventoryImages/btn-filter.svg" class="filter-icon" />
                    Filter
                </div>
                <div class="new-item" id="newbtn">
                    <a href="?action=addInventoryItem">
                        <img src="../../assests/InventoryImages/btn-plus.svg" class="plus-icon" />
                        New Item
                    </a>
                </div>
            </div>
            
            <!-- Pop-up Modal -->
            <div id="filterModal" class="modal">
                <div class="modal-content">
                    <span class="close">x</span>
                    <h2>Filter Options</h2>
                    <form action="../../Control/InventoryController/filter.php" method="POST">
                        <label for="filterType">Filter by:</label>
                        <br/>
                        <table class="displayingTable">
                            <tr>
                                <th>Item Type</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="radio" id="itemType1" name="filters[]" value="Animal"></input>
                                    <label for="itemType1"> Animal</label>
                                    <br/>
                                    <input type="radio" id="itemType2" name="filters[]" value="Habitat"></input>
                                    <label for="itemType2"> Habitat</label>
                                    <br/>
                                    <input type="radio" id="itemType3" name="filters[]" value="Food"></input>
                                    <label for="itemType3"> Food</label>
                                    <br/>
                                    <input type="radio" id="itemType4" name="filters[]" value="Cleaning"></input>
                                    <label for="itemType4"> Cleaning Supply</label>
                                </td>
                            </tr>
                        </table>
                        <br/>
                        <br/>
                        <button type="submit">Apply Filter</button>
                    </form>
                </div>
            </div>
            <table class="displayingTable">
                <tr>
                    <th>Inventory ID</th>
                    <th>Item Name</th>
                    <th>Item Type</th>
                    <th>Storage Location</th>
                    <th>Reorder Threshold</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                <xsl:for-each select="inventories/inventory[itemType = 'Food']">
                    <tr>
                        <td>
                            <xsl:value-of select="inventoryId" />
                        </td>
                        <td>
                            <a class="hrefText" href="#">
                                <xsl:attribute name="href">
                                    <xsl:value-of select="concat('?action=viewItembasedOnInventoryID&amp;inventoryId=', inventoryId, '&amp;itemType=', itemType)" />
                                </xsl:attribute>
                                <xsl:value-of select="itemName" />
                            </a>   
                        </td>
                        <td>
                            <xsl:value-of select="itemType" />
                        </td>
                        <td>
                            <xsl:value-of select="storageLocation" />
                        </td>
                        <td>
                            <xsl:value-of select="reorderThreshold" />
                        </td>
                        <td>
                            <xsl:value-of select="quantity" />
                        </td>
                        <td> 
                            <a class="hrefText reorder">
                                <xsl:attribute name="href">
                                    <xsl:value-of select="concat('?action=viewItembasedOnInventoryID&amp;inventoryId=', inventoryId, '&amp;itemType=', itemType)" />
                                </xsl:attribute>
                                <xsl:choose>
                                    <xsl:when test="quantity &lt;= 0">
                                        <div class="stockstatus outstock">Out of Stock</div>
                                    </xsl:when>
                                    <xsl:when test="quantity &lt;= reorderThreshold">
                                        <div class="stockstatus lowstock">Low Stock</div>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <div class="stockstatus instock">In Stock</div>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </a>
                            
                        </td>
                        <td>
                            <!-- Edit Button -->
                            <form action="../../Control/InventoryController/editRecord.php" method="POST" style="display:inline;">
                                <input type="hidden" name="inventoryId" value="{inventoryId}" />
                                <button type="submit" class="edit-btn">
                                    <img src="../../assests/InventoryImages/btn-edit.svg" class="edit-icon" />
                                </button>
                            </form>
                        </td>
                        <td>
                            <!-- Delete Button -->
                            <form action="../../Control/InventoryController/removeInventory.php" method="POST" style="display:inline;">
                                <input type="hidden" name="inventoryId" value="{inventoryId}" />
                                <input type="hidden" name="itemType" value="{itemType}" />
                                <button type="submit" class="delete-btn">
                                    <img src="../../assests/InventoryImages/btn-close.svg" class="delete-icon" />
                                </button>
                            </form>
                        </td>
                    </tr>
                </xsl:for-each>
            </table>

            <script>
                // JavaScript to handle modal functionality
                var modal = document.getElementById("filterModal");
                var filterButton = document.querySelector(".filter");
                var closeButton = document.querySelector(".close");
            
                // Get references to elements
                var filterbtn = document.getElementById("filterbtn");
                const addItemButton = document.getElementById("newbtn");

                filterButton.onclick = function() {
                modal.style.display = "block";
                }
                closeButton.onclick = function() {
                modal.style.display = "none";
                }
                window.onclick = function(event) {
                if (event.target == modal) {
                modal.style.display = "none";
                }
                }
            </script>
        </div>
    </xsl:template>
</xsl:stylesheet>
