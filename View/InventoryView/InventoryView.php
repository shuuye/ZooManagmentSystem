<?php

include_once '../../Model/Inventory/InventoryModel.php';
include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\XSLTransformation.php';

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
            $xslTransformation->setParameter('inventoryID', $data['inventoryID']);
        }
        if (isset($data['itemID'])) {
            $xslTransformation->setParameter('itemID', $data['itemID']);
        }
        if (isset($data['imageDirectory'])) {
            $xslTransformation->setParameter('imageDirectory', $data['imageDirectory']);
        }
        if (isset($data['POid'])) {
            $xslTransformation->setParameter('POid', $data['POid']);
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
