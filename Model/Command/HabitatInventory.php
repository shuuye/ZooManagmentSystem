<?php

require_once 'Inventory.php';
require_once 'HabitatInventory.php';

class HabitatInventory extends Inventory {
    private $itemType;
    private $description;
    private $habitatType; // e.g. aquarium, terrarium, etc.

    public function __construct($id, $name, $quantity, $itemType, $description, $habitatType) {
        parent::__construct($id, $name, $quantity);
        $this->itemType = $itemType;
        $this->description = $description;
        $this->habitatType = $habitatType;
    }

    public function getItemType() {
        return $this->itemType;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getHabitatType() {
        return $this->habitatType;
    }

    public function toString() {
        return "Habitat Item: " . $this->name . "<br>" .
               "Item Type: " . $this->itemType . "<br>" .
               "Description: " . $this->description . "<br>" .
               "Habitat Type: " . $this->habitatType . "<br>" .
               "Quantity: " . $this->quantity . "<br>";
    }
}
