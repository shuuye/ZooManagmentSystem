<?php
// Include the FoodManagementController
include_once '../../Control/AnimalControllerN/FoodManagementController.php';

// Create an instance of FoodManagementController
$controller = new FoodManagementController();

// Get the animal IDs and food items
$animalIds = $controller->getAnimalIds();
$foods = $controller->getFoodItem();


// Handle form submission
$controller->handleAddfeedingRecordForm();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Animal Feeding Form</title>
</head>
<body>
    <h1>Animal Feeding Form</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        
        <label for="animal_id">Select an animal ID:</label>
        <select name="animal_id" id="animal_id">
            <?php foreach ($animalIds as $id) { ?>
                <option value="<?php echo htmlspecialchars($id); ?>"><?php echo htmlspecialchars($id); ?></option>
            <?php } ?>
        </select>

        <label for="food_id">Food:</label>
        <select name="food_id" id="food_id">
            <?php foreach ($foods as $food): ?>
                <option value="<?php echo htmlspecialchars($food['id']); ?>"><?php echo htmlspecialchars($food['foodName']); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="feeding_time">Feeding Time:</label>
        <input type="datetime-local" name="feeding_time" id="feeding_time" required>

        <label for="quantity_fed">Quantity Fed:</label>
        <input type="number" step="1" name="quantity_fed" id="quantity_fed" required>

        <button type="submit">Add Feeding Record</button>
    </form>
</body>
</html>
