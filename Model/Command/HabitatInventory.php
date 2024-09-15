<?php

require_once 'Inventory.php';

class HabitatInventory extends Inventory {

    private $description;
    private $habitatType; // e.g. aquarium, terrarium, etc.
    private $material;
    private $expected_lifetime;
    private $installation_instructions;
    private $disposal_instructions;

    public function __construct($itemName, $itemType, $storageLocation, $reorderThreshold, $quantity = null, $description = null, $habitatType = null, $material = null, $expected_lifetime = null, $installation_instructions = null, $disposal_instructions = null) {
        parent::__construct($itemName, $itemType, $storageLocation, $reorderThreshold, $quantity);
        $this->description = $description;
        $this->habitatType = $habitatType;
        $this->material = $material;
        $this->expected_lifetime = $expected_lifetime;
        $this->installation_instructions = $installation_instructions;
        $this->disposal_instructions = $disposal_instructions;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getHabitatType() {
        return $this->habitatType;
    }

    public function getMaterial() {
        return $this->material;
    }

    public function getExpected_lifetime() {
        return $this->expected_lifetime;
    }

    public function getInstallation_instructions() {
        return $this->installation_instructions;
    }

    public function getDisposal_instructions() {
        return $this->disposal_instructions;
    }

    public function setItemType($itemType): void {
        $this->itemType = $itemType;
    }

    public function setDescription($description): void {
        $this->description = $description;
    }

    public function setHabitatType($habitatType): void {
        $this->habitatType = $habitatType;
    }

    public function setMaterial($material): void {
        $this->material = $material;
    }

    public function setExpected_lifetime($expected_lifetime): void {
        $this->expected_lifetime = $expected_lifetime;
    }

    public function setInstallation_instructions($installation_instructions): void {
        $this->installation_instructions = $installation_instructions;
    }

    public function setDisposal_instructions($disposal_instructions): void {
        $this->disposal_instructions = $disposal_instructions;
    }

    public function toString() {
        return "Habitat Item: " . $this->name . "<br>" .
                "Item Type: " . $this->itemType . "<br>" .
                "Description: " . $this->description . "<br>" .
                "Habitat Type: " . $this->habitatType . "<br>";
    }

    public function addItemRecord() {
        
    }

    public function removeItemRecord($records) {
        return $this->removeRecordFromDB($this->itemType, $records);
    }
}
