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
                    <a>
                        <img src="../../assests/InventoryImages/btn-plus.svg" class="plus-icon" />
                        New Brand
                    </a>
                </div>
            </div>
            <!-- Pop-up Modal -->
            <div id="newItemPop" class="modal">
                <div class="modal-content">
                    <span class="close">x</span>
                    <form action="../../Control/InventoryController/newBrand.php" method="POST" id="upload" enctype="multipart/form-data">

                        
                        <table class="displayingTable">
                            <tr>
                                <th colspan="2">New Food Brand for <xsl:value-of select="inventory[inventoryId = $inventoryID]/itemName" /></th>
                            </tr>
                            <tr>
                                <td>
                                    Brand Name: 
                                </td>
                                <td>
                                    <input type="text" id="brandName" name="brandName"></input>
                                    <br/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Daily Quantity Required:  
                                </td>
                                <td>
                                    <input type="text" id="quatntity" name="quatntity"></input>
                                    <br/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Nutrition Information:   
                                </td>
                                <td>
                                    <input type="text" id="information" name="information"></input>
                                    <br/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Upload an Image:  
                                </td>
                                <td>
                                    <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/jpg"></input>
                                    <br/>
                                    <span class="error" id="error"></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Select Supplier:   
                                </td>
                                <td>
                                    <select id="supplier" name="supplierId">
                                        <xsl:attribute name="required">required</xsl:attribute>
                                        <option value="">
                                            <xsl:attribute name="disabled">disabled</xsl:attribute>
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                            Select a supplier
                                        </option>
                                        <option value="5">Food Suppliers Sdn Bhd</option>
                                        <option value="6">Animal Care Sdn Bhd</option>
                                        <option value="10">Animal Feed Sdn Bhd</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Price:   
                                </td>
                                <td>
                                    <xsl:attribute name="required">required</xsl:attribute>
                                    <input type="number" id="price" name="price" step="0.01" min="0"></input>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input type="hidden" name="inventoryId" value="{$inventoryID}" />
                                    <input type="hidden" name="itemType" value="{/root/inventory[inventoryId = $inventoryID]/itemType}" />
                                </td>
                            </tr>
                            
                            
                        </table>
                        <br/>
                        <br/>
                        <button type="submit">Add</button>
                    </form>
                </div>
            </div>
            <!-- Pop-up Modal for Editing Item -->
            <div id="editItemPop" class="modal">
                <div class="modal-content">
                    <span class="close-edit">x</span>
                    <form id="editItemForm" action="../../Control/InventoryController/editRecord.php" method="POST">
                        <input type="hidden" name="inventoryId" id="editInventoryId" />
                        <input type="hidden" name="itemId" id="editItemId" />
                        <input type="hidden" name="itemType" id="editItemType" value="Food" />
                        <table class="displayingTable">
                            <tr>
                                <th colspan="2">Edit Food Item</th>
                            </tr>
                            <tr>
                                <td>Brand Name:</td>
                                <td>
                                    <input type="text" id="editBrandName" name="brandName" />
                                </td>
                            </tr>
                            <tr>
                                <td>Nutrition Information: </td>
                                <td>
                                    <input type="text" id="editNutrition" name="nutritionInfo" />
                                </td>
                            </tr>
                            <tr>
                                <td>Daily Quantity Required: </td>
                                <td>
                                    <input type="number" name="quantity" id="editQuantity" step="0.5" />
                                </td>
                            </tr>
                        </table>
                        <br/>
                        <button type="submit">Save Changes</button>
                    </form>
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
                            <button type="button" class="edit-btn" data-id="{id}" data-inventoryid="{inventoryId}"
                                    data-brandname="{foodName}"
                                    data-nutrition="{nutritionInfo}"
                                    data-quantity="{daily_quantity_required}"
                            >
                                <img src="../../assests/InventoryImages/btn-edit.svg" class="edit-icon" />
                            </button>
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
            <script>
                // JavaScript to handle modal functionality
                var newItemModal = document.getElementById("newItemPop");
                var editItemModal = document.getElementById("editItemPop");
                var newItemButton = document.querySelector(".new-item");
                var closeNewItemButton = document.querySelector(".close");
                var closeEditItemButton = document.querySelector(".close-edit");

                // Show new item modal
                newItemButton.onclick = function() {
                newItemModal.style.display = "block";
                }

                // Close new item modal
                closeNewItemButton.onclick = function() {
                newItemModal.style.display = "none";
                }

                // Show edit item modal with data
                document.querySelectorAll(".edit-btn").forEach(function(button) {
                button.onclick = function() {
                var data = button.dataset;
                document.getElementById("editInventoryId").value = this.getAttribute("data-inventoryid");
                document.getElementById("editItemId").value = this.getAttribute("data-id");
                document.getElementById("editBrandName").value = data.brandname;
                document.getElementById("editNutrition").value = data.nutrition;
                document.getElementById("editQuantity").value = data.quantity;
                editItemModal.style.display = "block";
                }
                });

                // Close edit item modal
                closeEditItemButton.onclick = function() {
                editItemModal.style.display = "none";
                }

                window.onclick = function(event) {
                if (event.target == newItemModal) {
                newItemModal.style.display = "none";
                }
                if (event.target == editItemModal) {
                editItemModal.style.display = "none";
                }
                }

                // Form validation
                document.getElementById('upload').addEventListener('submit', function(event) {
                const image = document.getElementById('image').files[0];
                const errorElement = document.getElementById('error');

                // Reset error message
                errorElement.textContent = '';

                // Check if file is selected
                if (!image) {
                errorElement.textContent = 'Please select an image file.';
                event.preventDefault();
                return;
                }

                // File size validation (2MB limit)
                const maxSize = 2 * 1024 * 1024; // 2MB in bytes
                if (image.size > maxSize) {
                errorElement.textContent = 'File size must be less than 2MB.';
                event.preventDefault();
                return;
                }

                // File format validation (JPEG and PNG only)
                const allowedFormats = ['image/jpeg', 'image/png'];
                if (!allowedFormats.includes(image.type)) {
                errorElement.textContent = 'Only JPEG and PNG formats are allowed.';
                event.preventDefault();
                }
                });
            </script>
        </div>
    </xsl:template>
</xsl:stylesheet>
