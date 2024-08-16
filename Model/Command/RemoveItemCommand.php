<?php

require_once 'Inventory.php';
require_once 'InventoryCommand.php';

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
