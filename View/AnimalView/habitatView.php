<?php

require_once '../../Model/Observer/Observer.php';

class habitatView implements Observer {
    
      public function update($subject) { // function from observer model
        $this->render($subject->getHabitatData());
    }
   
    // Render a single habitat form (for add/edit)
    public function renderHabitatForm($habitat = null) {
        $habitat_id = $habitat['habitat_id'] ?? '';
        $habitat_name = $habitat['habitat_name'] ?? '';
        $availability = $habitat['availability'] ?? '';
        $type = $habitat['type'] ?? '';
        $capacity = $habitat['capacity'] ?? '';
        $environment = $habitat['environment'] ?? '';
        $description = $habitat['description'] ?? '';

        echo '<form method="POST" action="habitat_save.php">';
        echo '<input type="hidden" name="habitat_id" value="' . htmlspecialchars($habitat_id) . '">';
        echo 'Habitat Name: <input type="text" name="habitat_name" value="' . htmlspecialchars($habitat_name) . '"><br>';
        echo 'Availability: <input type="text" name="availability" value="' . htmlspecialchars($availability) . '"><br>';
        echo 'Type: <input type="text" name="type" value="' . htmlspecialchars($type) . '"><br>';
        echo 'Capacity: <input type="number" name="capacity" value="' . htmlspecialchars($capacity) . '"><br>';
        echo 'Environment: <input type="text" name="environment" value="' . htmlspecialchars($environment) . '"><br>';
        echo 'Description: <textarea name="description">' . htmlspecialchars($description) . '</textarea><br>';
        echo '<input type="submit" value="Save Habitat">';
        echo '</form>';
    }

    // Render a list of habitats
    public function renderHabitatsList($habitats) {
        echo '<h2>List of Habitats</h2>';
        echo '<table border="1">';
        echo '<tr><th>ID</th><th>Name</th><th>Availability</th><th>Type</th><th>Capacity</th><th>Environment</th><th>Description</th><th>Actions</th></tr>';
        foreach ($habitats as $habitat) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($habitat['habitat_id']) . '</td>';
            echo '<td>' . htmlspecialchars($habitat['habitat_name']) . '</td>';
            echo '<td>' . htmlspecialchars($habitat['availability']) . '</td>';
            echo '<td>' . htmlspecialchars($habitat['type']) . '</td>';
            echo '<td>' . htmlspecialchars($habitat['capacity']) . '</td>';
            echo '<td>' . htmlspecialchars($habitat['environment']) . '</td>';
            echo '<td>' . htmlspecialchars($habitat['description']) . '</td>';
            echo '<td><a href="edit_habitat.php?id=' . htmlspecialchars($habitat['habitat_id']);
        }
    }
}
