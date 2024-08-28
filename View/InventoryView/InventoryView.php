<?php

include_once '../../Model/Inventory/InventoryModel.php';

class InventoryView extends InventoryModel {

    private $masterPage;

    public function __construct($masterPage) {
        $this->masterPage = $masterPage;
    }

    public function render($template,$data) {
        ob_start();
        extract($data);
        include $template . '.php';
        $template = ob_get_clean();
        include $this->masterPage;
        
    }

    
}
