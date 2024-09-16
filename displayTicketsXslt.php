<?php
// Set the content type to XML
header("Content-type: text/html");

// Load the XML file
$xml = new DOMDocument;
$xml->load(__DIR__ . '/Model/Xml/tickets.xml');

// Load the XSLT file
$xsl = new DOMDocument;
$xsl->load(__DIR__ . '/Model/Xml/tickets.xsl');

// Configure the transformer
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl); // Attach the XSL rules

// Transform the XML and display the result
echo $proc->transformToXML($xml);
?>
