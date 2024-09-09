<?php
class HabitatObserver implements Observer {
    public function update(subject $subject) {
        echo "Habitat data updated in HabitatObserver.";
    }
}
?>
