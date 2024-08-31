<?php

include_once '../../Model/Inventory/InventoryModel.php';
include_once 'C:\xampp\htdocs\ZooManagementSystem\Xml\createXMLFromDatabase.php';
include_once 'C:\xampp\htdocs\ZooManagementSystem\Xml\XSLTransformation.php';

class InventoryView extends InventoryModel {

    private $masterPage;

    public function __construct($masterPage) {
        $this->masterPage = $masterPage;
    }

    public function render($template, $data) {
        ob_start();
        extract($data);
        include $template . '.php';
        $template = ob_get_clean();
        include $this->masterPage;
    }

    public function renderXML($xmlfilename, $xslfilename, $data) {
        $xslTransformation = new XSLTransformation($xmlfilename, $xslfilename);
        if (isset($data['inventoryID'])) {
            $xslTransformation->setParameter('id', $data['inventoryID']);
        }
        $transformedContent = $xslTransformation->transform();
        ob_start();
        extract($data);
        echo $transformedContent;

        $renderedContent = ob_get_contents();
        ob_clean();
        include $this->masterPage;
        echo $renderedContent;
    }
}
