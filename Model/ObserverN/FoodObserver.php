<?php
require_once 'Observer.php';

class FoodObserver implements Observer {
    public function update(subject $subject) {
         echo "Feeding report updated successfully.\n";
    }
}
?>
