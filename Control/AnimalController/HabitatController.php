<?php
require_once '../../Model/Observer/Habitat.php';

$habitatModel = new Habitat();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $habitat_id = $_POST['habitat_id'] ?? null;
    $habitat_name = $_POST['habitat_name'];
    $availability = $_POST['availability'];

    try {
        if ($habitat_id) {
            // Edit existing habitat
            $result = $habitatModel->editHabitat($habitat_id, $habitat_name, $availability);
            if ($result > 0) {
                echo "Habitat updated successfully!";
            } else {
                echo "No changes were made.";
            }
        } else {
            // Add new habitat
            $newId = $habitatModel->addHabitat($habitat_name, $availability);
            echo "New habitat added successfully! ID: $newId";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    // Redirect back to the habitat list or the management page
    header('Location: habitat_home.php');
    exit;
}
?>
