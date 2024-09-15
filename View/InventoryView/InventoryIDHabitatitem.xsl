<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" xmlns:php="http://php.net/xsl">
    <xsl:output method="html" encoding="UTF-8" />

    <!-- Match the root element where all XML files are aggregated -->
    <xsl:template match="/root">
        <div class="main-content">
            <div class="navigation">
                <a class="hrefText" href="#">
                    Habitat Items 
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
                                <th colspan="2">New Habitat Item Brand for <xsl:value-of select="inventory[inventoryId = $inventoryID]/itemName" /></th>
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
                                    Description:   
                                </td>
                                <td>
                                    <input type="text" id="description" name="description"></input>
                                    <br/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Habitat Type:    
                                </td>
                                <td>
                                    <input type="text" id="habitatType" name="habitatType"></input>
                                    <br/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Material: 
                                </td>
                                <td>
                                    <input type="text" id="material" name="material"></input>
                                    <br/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Expected Life Time:  
                                </td>
                                <td>
                                    <input type="number" id="lifeTime" name="lifeTime" step="0.5" min="0"></input>
                                    <br/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Installation Instruction: 
                                </td>
                                <td>
                                    <input type="text" id="installationInstru" name="installationInstru"></input>
                                    <br/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Disposal Instruction: 
                                </td>
                                <td>
                                    <input type="text" id="disposalInstru" name="disposalInstru"></input>
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
                                        <option value="0">
                                            <xsl:attribute name="disabled">disabled</xsl:attribute>
                                            <xsl:attribute name="required">required</xsl:attribute>
                                            <xsl:attribute name="selected">selected</xsl:attribute>
                                            Select a supplier
                                        </option>
                                        <option value="3">Habitat Builders Sdn Bhd</option>
                                        <option value="7">Zoo Equipment Sdn Bhd</option>
                                        <option value="9">Zoo Supplies Sdn Bhd</option>
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
            <table class="displayingTable">
                <tr>
                    <th>Inventory ID</th>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Habitat Type</th>
                    <th>Material</th>
                    <th>Expected Life Time</th>
                    <th>Installation Instruction</th>
                    <th>Disposal Instruction</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                <xsl:for-each select="habitatinventory[inventoryId = $inventoryID]">
                    <tr>
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
                                <xsl:value-of select="habitatItemName" />
                            </a>                           
                        </td>
                        <td>
                            <xsl:value-of select="description" />
                        </td>
                        <td>
                            <xsl:value-of select="habitatType" />
                        </td>
                        <td>
                            <xsl:value-of select="material" />
                        </td>
                        <td>
                            <xsl:value-of select="expected_lifetime" />
                        </td>
                        <td>
                            <xsl:value-of select="installation_instructions" />
                        </td>
                        <td>
                            <xsl:value-of select="disposal_instructions" />
                        </td>
                        <td>
                            <!-- Create PO Button -->
                            <a class="hrefText reorder" href="#">
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
                                <input type="hidden" name="itemType" value="Habitat" />
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
                                <input type="hidden" name="itemType" value="Habitat" />
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
                var modal = document.getElementById("newItemPop");
                var newButton = document.querySelector(".new-item");
                var closeButton = document.querySelector(".close");
            
                // Get references to elements
                var filterbtn = document.getElementById("newbtn");

                newButton.onclick = function() {
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
