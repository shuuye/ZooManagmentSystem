<?php $content ='
<div class="main-content">

    <h2>Add New Item to Inventory</h2>

    <form action="../../Control/InventoryController/addNewItem.php" method="post">
        <table>
            <tr>
                <td>Item Name:</td>
                <td><input type="text" name="item_name" required></td>
            </tr>
            <tr>
                <td>Item Type:</td>
                <td>
                    <select name="item_type" required>
                        <option value="">Select Species</option>
                        <option value="animal">Animal</option>
                        <option value="cleaning">Cleaning Supplies</option>
                        <option value="habitat">Habitat Item</option>
                        <option value="food">Food</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Supplier ID:</td>
                <td><input type="text" name="supplier_id"></td>
            </tr>
            <tr>
                <td>Storage Location:</td>
                <td><input type="text" name="storageLocation" required></td>
            </tr>
            <tr>
                <td>Reorder Threshold (Food/Supplies):</td>
                <td><input type="number" name="reorder_threshold"></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" value="Add Item"></td>
            </tr>
        </table>
    </form>
</div>
        '; ?>
