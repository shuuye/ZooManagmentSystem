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
    <link rel="stylesheet" type="text/css" href="../../Css/AnimalN/ani_Nav.css">
    <link rel="stylesheet" type="text/css" href="../../Css/AnimalN/animal_form.css">
    <title>Add Health Record</title>
</head>
<body>
    <h2>Add | Update New Health Record</h2>
    
    <nav>
        <ul>
            <li><a href="list_healthRecords.php">View Health</a></li>

        </ul>
    </nav>
    
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        
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
        <select name="treatments" id="treatments" required>
            <option value="">Select a treatment</option>
            <option value="area_check">Area Check</option>
            <option value="body_check">Body Check</option>
            <option value="vaccination">Vaccination</option>
            <option value="dental_check">Dental Check</option>
        </select>
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
