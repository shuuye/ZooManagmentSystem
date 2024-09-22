<?xml version="1.0" encoding="UTF-8"?>
<!-- Author name: Lim Shuye -->
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="UTF-8" />
    <xsl:template match="/">
        <div class="main-content">
            <h2>Inventory</h2>
            <div class="recordingFunctions">
                <div class="filter" id="filterbtn">
                    <img src="/ZooManagementSystem/assests/InventoryImages/btn-filter.svg" class="filter-icon" />
                    Filter
                </div>
                <div class="new-item" id="newbtn">
                    <a href="?controller=inventory&amp;action=addInventoryItem">
                        <img src="/ZooManagementSystem/assests/InventoryImages/btn-plus.svg" class="plus-icon" />
                        New Item
                    </a>
                </div>
            </div>
            
            <!-- Pop-up Modal -->
            <div id="filterModal" class="modal">
                <div class="modal-content">
                    <span class="close">x</span>
                    <h2>Filter Options</h2>
                    <form action="/ZooManagementSystem/Control/InventoryController/filter.php" method="POST">
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
            <!-- Pop-up Modal for Editing Item -->
            <div id="editModal" class="modal">
                <div class="modal-content">
                    <span class="close-edit-modal">x</span>
                    <form id="editItemForm" action="/ZooManagementSystem/Control/InventoryController/editInventory.php" method="POST">
                        <input type="hidden" name="inventoryId" id="editInventoryId" />
                        <input type="hidden" name="itemType" id="edititemType" />
                        <table class="displayingTable">
                            <tr>
                                <th colspan="2">Edit Inventory Item Details</th>
                            </tr>
                            <tr>
                                <td>
                                    <label for="editItemName">Item Name:</label>
                                </td>
                                <td>
                                    <input type="text" name="itemName" id="editItemName" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="editStorageLocation">Storage Location:</label>
                                </td>
                                <td>
                                    <input type="text" name="storageLocation" id="editStorageLocation" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="editReorderThreshold">Reorder Threshold:</label>
                                </td>
                                <td>
                                    <input type="number" name="reorderThreshold" id="editReorderThreshold" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button type="submit">Save Changes</button>
                                </td>
                            </tr>
                        </table>
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
                                    <xsl:value-of select="concat('?controller=inventory&amp;action=viewItembasedOnInventoryID&amp;inventoryId=', inventoryId, '&amp;itemType=', itemType)" />
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
                                    <xsl:value-of select="concat('?controller=inventory&amp;action=viewItembasedOnInventoryID&amp;inventoryId=', inventoryId, '&amp;itemType=', itemType)" />
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
                            <button class="edit-btn" data-inventoryid="{inventoryId}"
                                    data-itemName="{itemName}" 
                                    data-itemType="{itemType}" 
                                    data-storageLocation="{storageLocation}" 
                                    data-reorderThreshold="{reorderThreshold}" 
                            >
                                <img src="/ZooManagementSystem/assests/InventoryImages/btn-edit.svg" class="edit-icon" />
                            </button>
                        </td>
                        <td>
                            <!-- Delete Button -->
                            <form action="/ZooManagementSystem/Control/InventoryController/removeInventory.php" method="POST" style="display:inline;">
                                <input type="hidden" name="inventoryId" value="{inventoryId}" />
                                <input type="hidden" name="itemType" value="{itemType}" />
                                <button type="submit" class="delete-btn">
                                    <img src="/ZooManagementSystem/assests/InventoryImages/btn-close.svg" class="delete-icon" />
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
            
                // JavaScript to handle modal functionality
                var editModal = document.getElementById("editModal");
                var closeEditModal = document.querySelector(".close-edit-modal");

                document.querySelectorAll(".edit-btn").forEach(function(button) {
                button.addEventListener("click", function() {
                // Retrieve data from button's data attributes
                document.getElementById("editInventoryId").value = this.getAttribute("data-inventoryid");
                document.getElementById("editItemName").value = this.getAttribute("data-itemName");
                document.getElementById("edititemType").value = this.getAttribute("data-itemType");
                document.getElementById("editStorageLocation").value = this.getAttribute("data-storageLocation");
                document.getElementById("editReorderThreshold").value = this.getAttribute("data-reorderThreshold");

                // Show modal
                editModal.style.display = "block";
                });
                });

                closeEditModal.onclick = function() {
                editModal.style.display = "none";
                }
                window.onclick = function(event) {
                if (event.target == editModal) {
                editModal.style.display = "none";
                }
                }

            </script>
        </div>
    </xsl:template>
</xsl:stylesheet>
