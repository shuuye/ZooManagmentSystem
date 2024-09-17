<?php

// used to store all command to play with the inventory 
// $inventoryManager->executeCommand(new AddItemCommand($inventory)); --> /Model/Command/InventoryCommand.php
interface InventoryCommand {

    public function execute();

    public function undo();
}

//undo functiono got backup in computer, if cannot do undo function can go back see
//AddItemRecordCommand
//havent finish for removeitem and update item and the undo in add item
// do the undo function
class AddItemCommand implements InventoryCommand {

    private $inventory;

    public function __construct(Inventory $inventory) {
        $this->inventory = $inventory;
    }

    public function execute() {
        $this->inventory->addNewItem();
    }

    public function undo() {
        $this->inventory->remove($this->quantity);
        echo '<br>undo successfull<br>';
    }
}

class editItemCommand implements InventoryCommand {

    private $inventory;
    private $data;

    public function __construct(Inventory $inventory, $data) {
        $this->inventory = $inventory;
        $this->data = $data;
    }

    public function execute() {
        // Pass data directly to the edit method
        $result = $this->inventory->editItem($this->data);
        return $result;
    }

    public function undo() {
        // Implement undo if necessary
        echo 'Undo not implemented for edit';
    }
}

class UpdateItemCommand implements InventoryCommand {

    private $inventory;
    private $oldQuantity;
    private $newQuantity;

    public function __construct($inventory, $newQuantity, $oldQuantity) {
        $this->inventory = $inventory;
        $this->oldQuantity = $oldQuantity;
        $this->newQuantity = $newQuantity;
    }

    public function execute() {
        $this->inventory->updateInventoryQuantity($this->newQuantity, $this->inventory->getInventoryId());
    }

    public function undo() {
        $this->inventory->update($this->oldQuantity);
    }
}

class AddItemRecordCommand implements InventoryCommand {

    private $inventory;
    private $data;

    public function __construct(Inventory $inventory, $data) {
        $this->inventory = $inventory;
        $this->data = $data;
    }

    public function execute() {
        $result = $this->inventory->addItemRecord($this->data);
        return $result;
    }

    public function undo() {
        $this->inventory->remove($this->quantity);
        echo '<br>undo successfull<br>';
    }
}

class editItemRecordCommand implements InventoryCommand {

    private $inventory;
    private $data;

    public function __construct(Inventory $inventory, $data) {
        $this->inventory = $inventory;
        $this->data = $data;
    }

    public function execute() {
        // Pass data directly to the edit method
        $result = $this->inventory->editItemRecord($this->data);
        return $result;
    }

    public function undo() {
        // Implement undo if necessary
        echo 'Undo not implemented for edit';
    }
}

class deleteItemRecordCommand implements InventoryCommand {

    private $inventory;
    private $records;

    public function __construct($inventory, $records) {
        $this->inventory = $inventory;
        $this->records = $records;
    }

    public function execute() {
        return $this->inventory->removeItemRecord($this->records);
    }

    public function undo() {
        $this->inventory->add($this->records);
    }
}

class deleteInventoryCommand implements InventoryCommand {

    private $inventory;
    private $records;

    public function __construct($inventory, $records) {
        $this->inventory = $inventory;
        $this->records = $records;
    }

    public function execute() {
        return $this->inventory->removeInventoryRecord($this->records);
    }

    public function undo() {
        $this->inventory->add($this->records);
    }
}
