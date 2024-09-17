<?php
require_once '../../Control/AnimalControllerN/FoodManagementController.php';
$controller = new FoodManagementController();
$feedingRecords = $controller->getAllFeedingRecords(); // Get all feeding records
$animalIds = $controller->getAnimalIds(); // Get animal IDs and food items for the form
$foods = $controller->getFoodItem();
$controller->handleAddfeedingRecordForm();// Handle form submission
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feeding Reports</title>
    <link rel="stylesheet" type="text/css" href="../../Css/AnimalN/ani_Nav.css">
    <link rel="stylesheet" type="text/css" href="../../Css/AnimalN/feeding.css">
    <link rel="stylesheet" type="text/css" href="../../Css/AnimalN/health.css">
   
</head>
<body>
    <nav>
       <ul>
           <li><a href="../../View/AnimalView/animal_home.php">Animal Home</a></li>

       </ul>
   </nav>
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

<!-- Add New Animal Button -->
<button id="addAnimalBtn" type="submit">Add New Animal</button>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    
    <h1>Animal Feeding Form</h1>
    
    <form action="feeding_report.php"  method="POST">
        
        <input type="hidden" name="form_type" value="feeding_report">
        
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
        <input type="number" step="1" min="1" name="quantity_fed" id="quantity_fed" required>

        <button type="submit">Add Feeding Record</button>
        
    </form>
  </div>

</div>
<script src="../../View/AnimalView/feeding.js"></script>
</body>
</html>
