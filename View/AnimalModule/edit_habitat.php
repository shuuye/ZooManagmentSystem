<?php
require_once '../../Control/AnimalController/HabitatController.php';

$controller = new HabitatController();
$habitat = $controller->getHabitatById($_GET['id']);

if (!$habitat) {
    echo "Habitat not found.";
    exit;
}
?>

<form action="../../Control/AnimalController/update_habitat.php" method="post">
    <input type="hidden" name="id" value="<?php echo $habitat->getId(); ?>">
    <label for="name">Name:</label>
    <input type="text" name="name" value="<?php echo $habitat->getName(); ?>"><br><br>
    <label for="availability">Availability:</label>
    <select name="availability">
        <option value="Available" <?php if ($habitat->getAvailability() == 'Available') echo 'selected'; ?>>Available</option>
        <option value="Unavailable" <?php if ($habitat->getAvailability() == 'Unavailable') echo 'selected'; ?>>Unavailable</option>
    </select><br><br>
    <label for="capacity">Capacity:</label>
    <input type="number" name="capacity" value="<?php echo $habitat->getCapacity(); ?>"><br><br>
    <label for="environment">Environment:</label>
    <select name="environment">
        <option value="hot" <?php if ($habitat->getEnvironment() == 'hot') echo 'selected'; ?>>Hot</option>
        <option value="cold" <?php if ($habitat->getEnvironment() == 'cold') echo 'selected'; ?>>Cold</option>
        <option value="water" <?php if ($habitat->getEnvironment() == 'water') echo 'selected'; ?>>Water</option>
    </select><br><br>
    <label for="description">Description:</label>
    <textarea name="description"><?php echo $habitat->getDescription(); ?></textarea><br><br>
    
    <input type="submit" value="Update">
</form>