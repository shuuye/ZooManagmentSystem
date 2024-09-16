<?php
// Create a new DOMDocument instance for XML and XSLT
$xml = new DOMDocument;
$xsl = new DOMDocument;

// Load the XML and XSL files
$xmlFile = __DIR__ . '/../Xml/tickets.xml'; // Update path to XML
$xslFile = __DIR__ . '/../Xml/tickets.xsl'; // Update path to XSL

if (!file_exists($xmlFile)) {
    die("Error: XML file not found.");
}

if (!file_exists($xslFile)) {
    die("Error: XSL file not found.");
}

// Load XML and XSL files
$xml->load($xmlFile);
$xsl->load($xslFile);

// Create a new XSLTProcessor instance and import the XSL stylesheet
$proc = new XSLTProcessor;
$proc->importStylesheet($xsl);

// Transform XML using the XSL stylesheet
$xmlOutput = $proc->transformToXML($xml);

if ($xmlOutput === false) {
    die("Error: XSL transformation failed.");
}

// Display the transformed XML
echo $xmlOutput;
?>
