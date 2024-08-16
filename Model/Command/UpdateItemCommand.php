<?php

require_once 'Inventory.php';
require_once 'InventoryCommand.php';

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