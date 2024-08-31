<?php

class XSLTransformation {

    private $xml;
    private $xsl;
    private $proc;

    public function __construct($xmlfilename, $xslfilename) {
        $this->xml = new DOMDocument();
        
        if (is_array($xmlfilename)) {
            $root = $this->xml->createElement('root'); // Create a root element to hold multiple XML files
            $this->xml->appendChild($root);

            foreach ($xmlfilename as $xmlFile) {
                if (file_exists($xmlFile)) {
                    $tempXml = new DOMDocument();
                    $tempXml->load($xmlFile);

                    // Import each node from the temporary document into the main document
                    foreach ($tempXml->documentElement->childNodes as $node) {
                        $importedNode = $this->xml->importNode($node, true);
                        $root->appendChild($importedNode);
                    }
                } else {
                    throw new Exception("XML file not found: " . $xmlFile);
                }
            }
            $this->xml->save('../../Model/Xml/combined.xml');
        } else {
            if (file_exists($xmlfilename)) {
                $this->xml->load($xmlfilename);
            } else {
                throw new Exception("XML file not found: " . $xmlfilename);
            }
        }
        


        $this->xsl = new DOMDocument();
        $this->xsl->load($xslfilename);

        $this->proc = new XSLTProcessor();
        $this->proc->importStylesheet($this->xsl);
    }

    public function transform() {
        return $this->proc->transformToXml($this->xml);
    }

    public function setParameter($name, $value) {
        $this->proc->setParameter('', $name, $value);
    }
}
