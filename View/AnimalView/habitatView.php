<?php

require_once '../../Control/AnimalControllerN/HabitatControllerObserver.php';
require_once '../../Model/ObserverN/AnimalModel.php';

// Create instances of the model and view
$model = new AnimalModel();
$view = new HabitatView();

// Create the controller and pass the model and view to it
$controller = new HabitatControllerObserver($model, $view);

// Display habitats
$controller->displayHabitats();

class HabitatView {

    public function renderHabitat($habitatData) {
        // Render the habitat form or any other habitat-related view
        // Example form for adding/updating habitat
        ?>
        <form method="POST" action="../../Control/AnimalControllerN/HabitatControllerObserver.php">
            <input type="hidden" name="habitat_id" value="<?= htmlspecialchars($habitatData['habitat_id'] ?? '') ?>">
            <label for="habitat_name">Habitat Name:</label>
            <input type="text" name="habitat_name" value="<?= htmlspecialchars($habitatData['habitat_name'] ?? '') ?>" required>
            <label for="availability">Availability:</label>
            <input type="text" name="availability" value="<?= htmlspecialchars($habitatData['availability'] ?? '') ?>" required>
            <label for="type">Type:</label>
            <input type="text" name="type" value="<?= htmlspecialchars($habitatData['type'] ?? '') ?>" required>
            <label for="capacity">Capacity:</label>
            <input type="number" name="capacity" value="<?= htmlspecialchars($habitatData['capacity'] ?? '') ?>" required>
            <label for="environment">Environment:</label>
            <input type="text" name="environment" value="<?= htmlspecialchars($habitatData['environment'] ?? '') ?>" required>
            <label for="description">Description:</label>
            <textarea name="description" required><?= htmlspecialchars($habitatData['description'] ?? '') ?></textarea>
            <input type="submit" value="Save Habitat">
        </form>
        <?php
    }

    public function renderHabitatsList($habitats) {
        // Render the list of habitats
        ?>
        <h2>Habitats List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Availability</th>
                    <th>Type</th>
                    <th>Capacity</th>
                    <th>Environment</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($habitats as $habitat): ?>
                    <tr>
                        <td><?= htmlspecialchars($habitat['habitat_id']) ?></td>
                        <td><?= htmlspecialchars($habitat['habitat_name']) ?></td>
                        <td><?= htmlspecialchars($habitat['availability']) ?></td>
                        <td><?= htmlspecialchars($habitat['type']) ?></td>
                        <td><?= htmlspecialchars($habitat['capacity']) ?></td>
                        <td><?= htmlspecialchars($habitat['environment']) ?></td>
                        <td><?= htmlspecialchars($habitat['description']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }
}


?>
