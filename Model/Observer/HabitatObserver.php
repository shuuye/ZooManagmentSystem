<?php

require_once 'Observer.php'; // Ensure this is your Observer interface

class HabitatObserver implements Observer {
    public function update($subject) {
        // You can define what happens when the habitat system is updated
        // For example, logging or refreshing the UI
        echo "Habitat system has been updated!";
    }
}
?>
