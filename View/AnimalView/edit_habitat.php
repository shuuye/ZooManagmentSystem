<?php

$habitat_id = $_POST['habitat_id'];
require_once '../../Control/AnimalControllerN/HabitatControllerObserver.php';
$habitatController = new HabitatControllerObserver();
$habitat = $habitatController->getHabitatById($habitat_id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/AnimalN/animal_form.css">
    <title>Edit Habitat</title>
</head>

<body>
   
    <h2>Habitat Edit Form </h2>
    
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    
    <label for="habitat_name">Habitat ID :</label>
    <input type="text" name="habitat_id" value="<?php echo $habitat_id; ?>" readonly>
    
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

</body>

<?php

$habitatController->handleFormSubmission();

?>
