<?php

require_once 'Inventory.php';
require_once 'InventoryCommand.php';

class InventoryManagement
{
    protected $commandHistory = [];
    public function executeCommand(InventoryCommand $command)
    {
        $command->execute();
        array_push($this->commandHistory,$command);
    }   

    public function undoCommand()
    {
        if (count($this->commandHistory) != 0){
            $command = array_pop($this->commandHistory);
            $command->undo();
        }else{
            echo "<br>No undo available.<br>";
        }
        
    }
}
