<?php
require_once 'Observer.php';

class AnimalObserver implements Observer {
    public function update(subject $subject) {
        echo "Animal updated successfully.\n";
    }
}
?>
