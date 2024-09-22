<?php
/*Author name: Lim Shuye*/


// use to bind witht the inventory to execute command
// $inventoryManager->executeCommand(new AddItemCommand($inventory)); --> /Model/Command/InventoryCommand.php

require_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\Inventory.php';
require_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryCommand.php';

class InventoryManagement {

    protected $commandHistory = [];

    public function executeCommand(InventoryCommand $command) {
        try {
            $command->execute();
            array_push($this->commandHistory, $command);
            return true; // Command executed successfully
        } catch (Exception $e) {
            // Log the exception or handle it as needed
            // echo "Error: " . $e->getMessage(); // Optional: for debugging
            return false; // Command execution failed
        }
    }

    public function undoCommand() {
        if (count($this->commandHistory) != 0) {
            $command = array_pop($this->commandHistory);
            $command->undo();
        } else {
            echo "<br>No undo available.<br>";
        }
    }
}
