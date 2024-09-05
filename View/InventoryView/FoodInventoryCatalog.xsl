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
                <div class="edit">
                    <img src="../../assests/InventoryImages/btn-edit.svg" class="edit-icon" />
                    Edit
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
                    <th></th>
                    <th>Inventory ID</th>
                    <th>Item Name</th>
                    <th>Item Type</th>
                    <th>Storage Location</th>
                    <th>Reorder Threshold</th>
                    <th>Quantity</th>
                </tr>
                <xsl:for-each select="inventories/inventory[itemType = 'Food']">
                    <tr>
                        <td>
                            <input type="checkbox" name="record[]" value="{inventoryId}" />
                        </td>
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
                    </tr>
                </xsl:for-each>
            </table>
            <script>
                // JavaScript to handle modal functionality
                var modal = document.getElementById("filterModal");
                var filterButton = document.querySelector(".filter");
                var closeButton = document.querySelector(".close");
            
                // Get references to elements
                const checkboxes = document.querySelectorAll('input[name="record[]"]');
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
            

                // Function to check if any checkbox is selected
                function checkCheckboxes() {
                const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

                // Disable/Enable buttons based on whether any checkbox is selected
                filterbtn.style.pointerEvents = anyChecked ? 'none' : 'auto';
                addItemButton.style.pointerEvents = anyChecked ? 'none' : 'auto';
                filterbtn.style.opacity = anyChecked ? '0.5' : '1';
                addItemButton.style.opacity = anyChecked ? '0.5' : '1';
                }

                // Attach event listeners to each checkbox
                checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', checkCheckboxes);
                });

                // Initial check in case any checkbox is already selected
                checkCheckboxes();
            </script>
        </div>
    </xsl:template>
</xsl:stylesheet>
