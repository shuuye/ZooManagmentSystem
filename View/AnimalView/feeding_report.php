<?php
// Include the FoodManagementController
include_once '../../Control/AnimalControllerN/FoodManagementController.php';

// Create an instance of FoodManagementController
$controller = new FoodManagementController();

// Get all feeding records
$feedingRecords = $controller->getAllFeedingRecords();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feeding Reports</title>
</head>
<body>
    <h1>Feeding Reports</h1>
    <table>
        <thead>
            <tr>
                <th>Animal ID</th>
                <th>Food ID</th>
                <th>Feeding Time</th>
                <th>Quantity Fed</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($feedingRecords as $record) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['animal_id']); ?></td>
                    <td><?php echo htmlspecialchars($record['food_id']); ?></td>
                    <td><?php echo htmlspecialchars($record['feeding_time']); ?></td>
                    <td><?php echo htmlspecialchars($record['quantity_fed']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
