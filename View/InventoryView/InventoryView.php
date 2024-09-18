<?php

include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Inventory\InventoryModel.php';
include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\XSLTransformation.php';

class InventoryView extends InventoryModel {

    private $masterPage;

    public function __construct($masterPage) {
        $this->masterPage = $masterPage;
    }

    public function render($template, $data) {
        extract($data);
        include dirname(__DIR__, 2) . '/View/adminTopNavHeader.php';
        include dirname(__DIR__, 2) . '/View/htmlHead.php';
        ob_start();
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
        $currentDate = date('Y-m-d'); // Format as needed
        $xslTransformation->setParameter('reportDate', $currentDate);
        $transformedContent = $xslTransformation->transform();
        include dirname(__DIR__, 2) . '/View/adminTopNavHeader.php';
        ob_start();
        extract($data);
        include dirname(__DIR__, 2) . '/View/htmlHead.php';

        echo $transformedContent;

        $renderedContent = ob_get_contents();
        ob_clean();
        include $this->masterPage;
        echo $renderedContent;
    }
}
