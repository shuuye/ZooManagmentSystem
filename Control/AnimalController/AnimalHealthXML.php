<?php

require_once '../../Xml/createXMLFromDatabase.php';

 
    
 // health_records table
 // Generate XML from the health_records table
 // Apply XSLT Transformation
 // Query XML Data using XPath
    $xmlGenerator = new createXMLFromDatabase();
    $xmlGenerator->createXMLFileByTableName("health_records", "health_records.xml", "HealthRecords", "HealthRecord", "hRecord_id");
    $xmlGenerator->displayXMLData("../../Xml/health_records.xml");
    $xmlGenerator->transformXMLUsingXSLT("../../Xml/health_records.xml", "../../Xsl/health_records.xsl", "animal_health_report.html");
    $xmlGenerator->queryXMLUsingXPath("../../Xml/health_records.xml", "//HealthRecord[healthStatus='Healthy']");
    