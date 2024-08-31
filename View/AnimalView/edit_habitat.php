<?php

// Retrieve the habitat ID from the URL parameter
$habitat_id = $_POST['habitat_id'];

// Include the controller
require_once '../../Control/AnimalControllerN/HabitatControllerObserver.php';

// Create an instance of the controller
$habitatController = new HabitatControllerObserver();

// Fetch the habitat data
$habitat = $habitatController->getHabitatById($habitat_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Habitat</title>
    <link rel="stylesheet" type="text/css" href="../../Css/Habitat/habitat.css">
</head>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <input type="hidden" name="habitat_id" value="<?php echo $habitat_id; ?>">
    <label for="habitat_name">Habitat Name:</label>
    <input type="text" name="habitat_name" value="<?php echo $habitat['habitat_name']; ?>"><br><br>
    <label for="availability">Availability:</label>
    <select name="availability">
        <option value="Available" <?php echo ($habitat['availability'] == 'Available') ? 'selected' : ''; ?>>Available</option>
        <option value="Unavailable" <?php echo ($habitat['availability'] == 'Unavailable') ? 'selected' : ''; ?>>Unavailable</option>
    </select><br><br>
    <label for="capacity">Capacity:</label>
    <input type="number" name="capacity" value="<?php echo $habitat['capacity']; ?>"><br><br>
    <label for="environment">Environment:</label>
    <select name="environment">
        <option value="hot" <?php echo ($habitat['environment'] == 'hot') ? 'selected' : ''; ?>>Hot</option>
        <option value="cold" <?php echo ($habitat['environment'] == 'cold') ? 'selected' : ''; ?>>Cold</option>
        <option value="water" <?php echo ($habitat['environment'] == 'water') ? 'selected' : ''; ?>>Water</option>
    </select><br><br>
    <label for="description">Description:</label>
    <textarea name="description"><?php echo $habitat['description']; ?></textarea><br><br>
    <input type="submit" value="Update Habitat">
</form>

<?php

$habitatController->handleFormSubmission();

?>