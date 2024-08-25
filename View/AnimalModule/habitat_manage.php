<?php
require_once '../../Model/Observer/Habitat.php';
require_once '../../Control/AnimalController/HabitatController.php';

$habitatModel = new Habitat();

// Check if there's an ID parameter for editing an existing habitat
$habitatId = $_GET['id'] ?? null;
$habitat = null;

// Fetch existing habitat details if an ID is provided
if ($habitatId) {
    $habitat = $habitatModel->getHabitatById($habitatId);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../../Css/Animal/animal_main.css">
</head>
<body>

<div class="sidebar">
    <h1>Pam</h1>
    <ul>
        <li><a href="animal_home.php">Animal Management</a></li> 
        <li><a href="habitat_manage.php">Habitat Management</a></li>
    </ul>
</div>

<div class="content">
    <div class="title">
        <h1><?php echo $habitatId ? 'Edit Habitat' : 'Add New Habitat'; ?></h1>
    </div>

    <div class="container">
        <form method="POST" action="../../Control/AnimalController/HabitatController.php">
            <input type="hidden" name="habitat_id" value="<?php echo htmlspecialchars($habitatId); ?>" />
            <label for="habitat_name">Name:</label>
            <input type="text" name="habitat_name" value="<?php echo htmlspecialchars($habitat ? $habitat['habitat_name'] : ''); ?>" required /><br />
            <label for="availability">Availability:</label>
            <input type="text" name="availability" value="<?php echo htmlspecialchars($habitat ? $habitat['availability'] : ''); ?>" required /><br />
            <input type="submit" value="<?php echo $habitatId ? 'Update Habitat' : 'Add Habitat'; ?>" />
        </form>
    </div>
</div>
</body>
</html>
