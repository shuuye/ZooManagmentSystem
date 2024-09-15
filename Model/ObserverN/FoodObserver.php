<?php
class FoodObserver implements Observer {
    public function update($data) {
         echo "\nFeeding report has been updated.\n";
    }
}
?>
