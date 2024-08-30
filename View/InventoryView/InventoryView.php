<?php

include_once '../../Model/Inventory/InventoryModel.php';

class InventoryView extends InventoryModel {

    private $masterPage;

    public function __construct($masterPage) {
        $this->masterPage = $masterPage;
    }

//    public function render($template, $data) {
//        ob_start();
//        extract($data);
//        include $template . '.php';
//        $template = ob_get_clean();
//        include $this->masterPage;
//    }

    public function render($xmlData, $xslFile) {
        // Load the XML and XSLT files
        $xml = new DOMDocument;
        $xml->loadXML($xmlData);

        $xsl = new DOMDocument;
        $xsl->load($xslFile);

        // Configure the transformer
        $proc = new XSLTProcessor;
        $proc->importStyleSheet($xsl);

        // Transform the XML using the XSLT
        $content = $proc->transformToXML($xml);

        // Render the transformed content within the master page
        ob_start();
        include $this->masterPage;
        $masterContent = ob_get_clean();

        // Replace placeholder with transformed content
        echo str_replace('{{content}}', $content, $masterContent);
    }
}
