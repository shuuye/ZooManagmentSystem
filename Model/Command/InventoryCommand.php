<?php

interface InventoryCommand {
    public function execute();
    public function undo();
}
