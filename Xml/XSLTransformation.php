<?php


class XSLTransformation {
    public function __construct($xmlfilename, $xslfilename) {
        $xml = new DOMDocument();
        $xml ->load($xmlfilename);
        
        $xsl = new DOMDocument();
        $xsl->load($xslfilename);
        
        $proc = new XSLTProcessor();
        $proc->importStylesheet($xsl);
        
        echo $proc->transformToXml($xml);
        
    }
}


$worker = new XSLTransformation("inventory.xml", "../Xsl/inventory.xsl");
