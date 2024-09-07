<?php
// Include the HealthController
include_once '../../Control/AnimalControllerN/HealthController.php';

// Create an instance of HealthController
$controller = new HealthController();

// Get the animal IDs without complete health records from the controller
$animalIds = $controller->getAnimalIds();

// Handle form submission
$controller ->handleAddHealthRecordForm();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Health Record</title>
</head>
<body>
    <h2>Add New Health Record</h2>
    
    <form method="POST" action="add_health.php">
        
         <input type="hidden" name="form_type" value="health_form">
         
        <label for="animal_id">Select an animal ID:</label>
        <select name="animal_id" id="animal_id">
            <?php foreach ($animalIds as $id) { ?>
                <option value="<?php echo $id; ?>"><?php echo $id; ?></option>
            <?php } ?>
        </select>
        
        <br/>
        <label for="last_checkup">Last Checkup:</label>
        <input type="date" name="last_checkup" required>
        <br/>
        <label for="treatments">Treatments:</label>
        <textarea name="treatments" required></textarea>
        <br/>
        <label for="healthStatus">Health Status:</label>
        <select name="healthStatus" required>
            <option value="Healthy">Healthy</option>
            <option value="Normal">Normal</option>
            <option value="Treatment">Treatment</option>
            <option value="Warning">Warning</option>
        </select>
        <br/>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
