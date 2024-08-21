<?php
class HabitatObserver implements Observer {
    public function update($subject) {
        echo "Habitat Observer: Updating habitat status.\n";
        // Logic to manage habitat conditions
    }
}

