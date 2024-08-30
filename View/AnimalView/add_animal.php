<?php
// Include the controller
include_once '../../Control/AnimalControllerN/AnimalController.php';

// Create an instance of the controller
$animalController = new AnimalController();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Animal</title>
    <link rel="stylesheet" type="text/css" href="../../Css/Animal/animal.css">
</head>
<body>
    <h1>Add New Animal</h1>
    <form action="../Controller/AnimalController.php" method="POST">
        <label for="animalName">Animal Name:</label>
        <input type="text" id="animalName" name="animalName" required><br>

        <label for="species">Species:</label>
        <input type="text" id="species" name="species" required><br>

        <label for="height">Height:</label>
        <input type="text" id="height" name="height" required><br>

        <label for="weight">Weight:</label>
        <input type="text" id="weight" name="weight" required><br>

        <label for="habitatId">Habitat ID:</label>
        <input type="number" id="habitatId" name="habitatId" required><br>

        <label for="healthStatus">Health Status:</label>
        <select id="healthStatus" name="healthStatus" required>
            <option value="Healthy">Healthy</option>
            <option value="Normal">Normal</option>
            <option value="Treatment">Treatment</option>
            <option value="Warning">Warning</option>
        </select><br>

        <label for="supplierId">Supplier ID:</label>
        <input type="number" id="supplierId" name="supplierId" required><br>

        <label for="storageLocation">Storage Location:</label>
        <input type="text" id="storageLocation" name="storageLocation" required><br>

        <label for="reorderThreshold">Reorder Threshold:</label>
        <input type="number" id="reorderThreshold" name="reorderThreshold" required><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br>

        <button type="submit" name="submit">Add Animal</button>
    </form>
</body>
</html>
