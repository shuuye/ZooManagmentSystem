<?php

class Inventory 
{
    protected $inventory_id;
    protected $item_name;
    protected $quantity;

    public function __construct($inventory_id, $item_name, $quantity = 0)
    {
        $this->inventory_id = $inventory_id;
        $this->item_name = $item_name;
        $this->quantity = $quantity;
    }

    public function add($quantity)
    {
        $this->quantity += $quantity;
        echo "Added $quantity units. New quantity: " . $this->quantity . "\n";
    }

    public function remove($quantity)
    {
        $this->quantity -= $quantity;
        if ($this->quantity < 0) {
            $this->quantity = 0;
        }
        echo "Removed $quantity units. New quantity: " . $this->quantity . "\n";
    }

    public function update($newQuantity)
    {
        $this->quantity = $newQuantity;
        echo "Quantity updated to: " . $this->quantity . "\n";
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    // Common getters and setters for inventory
    public function getInventoryId()
    {
        return $this->inventory_id;
    }

    public function getItemName()
    {
        return $this->item_name;
    }

    public function setItemName($item_name)
    {
        $this->item_name = $item_name;
    }
}