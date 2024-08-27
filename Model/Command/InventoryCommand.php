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
class AddItemCommand implements InventoryCommand
{
    private $inventory;

    public function __construct(Inventory $inventory)
    {
        $this->inventory = $inventory;
    }

    public function execute()
    {
        $this->inventory->addNewItem();
    }

    public function undo()
    {
        $this->inventory->remove($this->quantity);
        echo '<br>undo successfull<br>';
    }
}

class RemoveItemCommand implements InventoryCommand
{
    private $inventory;
    private $quantity;

    public function __construct($inventory, $quantity)
    {
        $this->inventory = $inventory;
        $this->quantity = $quantity;
    }

    public function execute()
    {
        $this->inventory->remove($this->quantity);
    }

    public function undo()
    {
        $this->inventory->add($this->quantity);
    }
}

class UpdateItemCommand implements InventoryCommand
{
    private $inventory;
    private $oldQuantity;
    private $newQuantity;

    public function __construct($inventory, $newQuantity)
    {
        $this->inventory = $inventory;
        $this->oldQuantity = $inventory->getQuantity();
        $this->newQuantity = $newQuantity;
    }

    public function execute()
    {
        $this->inventory->update($this->newQuantity);
    }

    public function undo()
    {
        $this->inventory->update($this->oldQuantity);
    }
}

