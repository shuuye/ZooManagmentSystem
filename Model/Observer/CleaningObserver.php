<?php

class CleaningObserver implements Observer {
    public function update($subject) {
        echo "Cleaning Observer: Scheduling cleaning tasks.\n";
        // Logic to schedule cleaning based on the animal's habitats
    }
}