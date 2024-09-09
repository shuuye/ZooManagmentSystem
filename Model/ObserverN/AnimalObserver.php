<?php
require_once 'Observer.php';

class AnimalObserver implements Observer {
    public function update(subject $subject) {
        echo "Animal parts have been updated.\n";
    }
}
?>
