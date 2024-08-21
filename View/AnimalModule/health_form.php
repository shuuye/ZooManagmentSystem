<?php
require_once '..\..\Control\AnimalController\HealthController.php';
require_once '..\..\Model\Observer\HealthObserver.php';

$controller = new HealthController();
$animal_id = $_GET['animal_id'] ?? ''; // Get the animal_id from the request

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Form submission handling
    $animal_id = $_POST['animal_id'];
    $last_checkup = $_POST['last_checkup'];
    $treatments = $_POST['treatments'];
    $condition = $_POST['condition'];

    if (isset($_POST['is_new_record'])) {
        // Add new health record
        $controller->addHealthRecord($animal_id, $last_checkup, $treatments, $condition);
    } else {
        // Update existing health record
        $controller->updateHealth($animal_id, $last_checkup, $treatments, $condition);
    }
}

// Fetch the current health record if it exists
$healthRecord = $controller->checkHealth($animal_id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Health Record Form</title>
</head>
<body>
    <h1>Health Record for Animal ID: <?php echo $animal_id; ?></h1>

    <form method="POST">
        <input type="hidden" name="animal_id" value="<?php echo $animal_id; ?>" />

        <!--new record -->
        <?php if (!$healthRecord->getCondition()) : ?>
            <input type="hidden" name="is_new_record" value="1" />
        <?php endif; ?>

        <label for="last_checkup">Last Checkup:</label>
        <input type="date" name="last_checkup" value="<?php echo $healthRecord->last_checkup ?? ''; ?>" required /><br/>

        <label for="treatments">Treatments:</label>
        <textarea name="treatments" required><?php echo $healthRecord->treatments ?? ''; ?></textarea><br/>

        <label for="condition">Condition:</label>
        <input type="text" name="condition" value="<?php echo $healthRecord->condition ?? ''; ?>" required /><br/>

        <button type="submit">Save Health Record</button>
    </form>
</body>
</html>
