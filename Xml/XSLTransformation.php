<?php


class XSLTransformation {
    private $xml;
    private $xsl;
    private $proc;

    public function __construct($xmlfilename, $xslfilename) {
        $this->xml = new DOMDocument();
        $this->xml->load($xmlfilename);

        $this->xsl = new DOMDocument();
        $this->xsl->load($xslfilename);

        $this->proc = new XSLTProcessor();
        $this->proc->importStylesheet($this->xsl);
    }

    public function transform() {
        return $this->proc->transformToXml($this->xml);
    }
}