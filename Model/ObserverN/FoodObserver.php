<?php
class FoodObserver implements Observer {
    public function update($data) {
        echo "Feeding report has been updated. Data: " . print_r($data, true);
    }
}
?>
