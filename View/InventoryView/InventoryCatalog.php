<?php


// Check if data variables exist in the template

$content = '
<div class="main-content">

    <h2>Inventory</h2>

    <div class="recordingFunctions">
        <div class="filter">
            <img src="../../assests/InventoryImages/btn-filter.svg" class="filter-icon"  />
            Filter
        </div>
        <div class="new-item">
            <a href="?action=addInventoryItem">

                <img src="../../assests/InventoryImages/btn-plus.svg" class="plus-icon"  />
                New Item
            </a>
        </div>
        <div class="edit">
            <img src="../../assests/InventoryImages/btn-edit.svg" class="edit-icon"  />
            Edit
        </div>
    </div>

    <table class="displayingTable">
        <tr>
            <th></th>
            <th>Inventory ID</th>
            <th>Item Name</th>
            <th>Item Type</th>
            <th>Supplier ID</th>
            <th>Storage Location</th>
            <th>Reorder Threshold</th>
        </tr>';
foreach ($data["inventory"] as $row) {
    $content .= '
            <tr>
                <td><input type="checkbox" name="record[]" value="' . $row["inventoryId"] . '"></td>
                <td>' . $row["inventoryId"] . '</td>
                <td>' . $row["itemName"] . '</td>
                <td>' . $row["itemType"] . '</td>
                <td>' . $row["supplierId"] . '</td>
                <td>' . $row["storageLocation"] . '</td>
                <td>' . $row["reorderThreshold"] . '</td>
            </tr>';
}
$content .= '</table>

    <tr>
        <td colspan="10">
            <div class="nextpage">
                <div class="prev">';
if ($data["pageNum"] > 1) {
    $content .= '<a href="?page=' . ($data["pageNum"] - 1) . '">Prev</a>';
} else {
    $content .= '&lt; Prev';
}
$content .= '</div>
                Page ' . $data["pageNum"] . ' of ' . $data["totalPages"] . '
                <div class="next">';
if ($data["pageNum"] < $data["totalPages"]) {
    $content .= '<a href="?page=' . ($data["pageNum"] + 1) . '">Next &gt;</a>';
} else {
    $content .= 'Next &gt;';
}
$content .= '</div>
            </div>
        </td>
    </tr>
</div>';
