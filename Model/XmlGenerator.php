<?php

require_once __DIR__ . '/../Config/databaseConfig.php';

class XmlGenerator extends databaseConfig {

    private function fetchData($tableName, $db) {
        if (empty($tableName)) {
            throw new Exception("Table name cannot be empty.");
        }

// Prepare the SQL query
        $sql = "SELECT * FROM " . $tableName;
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new Exception("Statement preparation failed: " . implode(" ", $db->errorInfo()));
        }

// Execute the query
        $stmt->execute();

        return $stmt;
    }

    private function setXMLElements($xml, $dataOfTable, $rootElementName, $elementsName, $attributeForFirstElement) {
// Create root element
        $root = $xml->createElement($rootElementName);
        $xml->appendChild($root);

// Fetching data and adding to XML
        while ($row = $dataOfTable->fetch(PDO::FETCH_ASSOC)) {
// Create a new element within the root
            $elementInRoot = $xml->createElement($elementsName);

// Get the first column key
            $firstColumnKey = key($row);

// Check if the first column is same as the attribute passed in
            if ($firstColumnKey === $attributeForFirstElement) {
//set the attribute to the element
                $elementInRoot->setAttribute($attributeForFirstElement, htmlspecialchars($row[$firstColumnKey]));
                unset($row[$firstColumnKey]); // Remove the attribute from the row to avoid duplication as child element
            }

            foreach ($row as $column => $value) {
// Create child elements for each column
                $element = $xml->createElement($column, htmlspecialchars($value));
                $elementInRoot->appendChild($element);
            }

            $root->appendChild($elementInRoot);
        }

        return $xml;
    }

    private function saveXmlFile($xml, $outputFileName) {
// Save the XML file
        $directory = dirname(__DIR__) . "/Model/Xml";

// Check if the directory exists
        if (!is_dir($directory)) {
// Create the directory if it doesn't exist
            mkdir($directory, 0755, true);
        }

// Save the XML file in the directory
        $filePath = $directory . "/" . $outputFileName;
        $xml->save($filePath);
       // echo "XML file created successfully at $outputFileName";
    }

    private function generateXMLDataFromTable($tableName, $outputFileName, $db, $rootElementName, $elementsName, $attributeForFirstElement) {
        try {
            $dataOfTable = $this->fetchData($tableName, $db);

            if ($dataOfTable->rowCount() > 0) {
// Start the XML document
                $xml = new DOMDocument('1.0', 'UTF-8');
                $xml->formatOutput = true;

//set the value in the elements
                $xml = $this->setXMLElements($xml, $dataOfTable, $rootElementName, $elementsName, $attributeForFirstElement);

                $this->saveXmlFile($xml, $outputFileName);
            } else {
                echo "0 results found in the table.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function createXMLFileByTableName($tableName = "", $outputFileName = "", $rootElementName = "", $elementsName = "", $attributeForFirstElement = "") {
//connect to database
        $db = $this->getConnection();

        if (!empty($tableName) && !empty($outputFileName)) {
//generate the XML data from the data in the database by using the table name
            $this->generateXMLDataFromTable($tableName, $outputFileName, $db, $rootElementName, $elementsName, $attributeForFirstElement);
        } else {
            echo "tableName or outputFileName cannot be Empty";
        }
    }

    public function displayXMLData($xmlFileName) {
        if (file_exists($xmlFileName)) {
            $xml = new DOMDocument();
            $xml->load($xmlFileName);
            $xml->preserveWhiteSpace = false;
            $xml->formatOutput = true;

            echo "<pre>" . htmlspecialchars($xml->saveXML()) . "</pre>";
        } else {
            echo "The file $xmlFileName does not exist.";
        }
    }
    
    public function transformXMLUsingXSLTForDisplay($xmlFileName, $xsltFileName) { //pam
        // Applies an XSLT transformation to an XML file and directly displays the result.
        if (file_exists($xmlFileName) && file_exists($xsltFileName)) {
            $xml = new DOMDocument();
            $xml->load($xmlFileName);

            $xsl = new DOMDocument();
            $xsl->load($xsltFileName);

            $proc = new XSLTProcessor();
            $proc->importStyleSheet($xsl);

            // Transform the XML and display the output directly
            $output = $proc->transformToXML($xml);
            if ($output) {
                echo $output; // Display the transformed XML as HTML
            } else {
                echo "Transformation failed.";
            }
        } else {
            echo "One or both files do not exist.";
        }
    }

    
        public function transformFilteredXMLUsingXSLT($filteredXmlContent, $xsltFileName, $outputFileName) { //pam
        // Transforms filtered XML content using XSLT and saves the result to a file.
        if ($filteredXmlContent && file_exists($xsltFileName)) {
            $xml = new DOMDocument();
            $xml->loadXML($filteredXmlContent);

            $xsl = new DOMDocument();
            $xsl->load($xsltFileName);

            $proc = new XSLTProcessor();
            $proc->importStyleSheet($xsl);

            $output = $proc->transformToXML($xml);
            file_put_contents($outputFileName, $output);

            echo "Transformation complete. Output saved to $outputFileName";
        } else {
            echo "Filtered XML content is empty or XSLT file does not exist.";
        }
    }

    
    public function queryXMLUsingXPath($xmlFileName, $xpathQuery) { //pam
        // Queries an XML file using an XPath expression and returns the filtered XML content.
        if (file_exists($xmlFileName)) {
            $xml = new DOMDocument();
            $xml->load($xmlFileName);

            $xpath = new DOMXPath($xml);
            $entries = $xpath->query($xpathQuery); // Way 1 pass  Query as XPATH

            $newXml = new DOMDocument();
            $root = $newXml->createElement("HealthRecords");

            foreach ($entries as $entry) {
                $importedNode = $newXml->importNode($entry, true);
                $root->appendChild($importedNode);
            }

            $newXml->appendChild($root);
            return $newXml->saveXML();
        } else {
            echo "The file $xmlFileName does not exist.";
            return null;
        }
    }
    
    public function countHealthStatuses($xmlFileName) { // Pam
    if (file_exists($xmlFileName)) {
        $xml = new DOMDocument();
        $xml->load($xmlFileName);

        $xpath = new DOMXPath($xml);

        // Query for different health statuses
        $statusCounts = [];
        $statuses = ['Healthy', 'Normal', 'Treatment','Warning']; // List your health statuses here
        foreach ($statuses as $status) {
            $query = "//HealthRecord[healthStatus='$status']"; // XPATH count Health status
            $entries = $xpath->query($query);
            $statusCounts[$status] = $entries->length;
        }

        return $statusCounts;
    } else {
        echo "The file $xmlFileName does not exist.";
        return null;
    }
}
    
    public function transformXmlWithXsl($xml, $xsl) {
        $xmlDoc = new DOMDocument();
        $xmlDoc->loadXML($xml);
        $xslDoc = new DOMDocument();
        $xslDoc->load($xsl);
        $proc = new XSLTProcessor();
        $proc->importStylesheet($xslDoc);
        return $proc->transformToXML($xmlDoc);
    }

    // For Vanness Ticket Facade
    public function createXMLFileFromArray($tableName, $outputFileName, $rootElementName, $elementsName, $attributeForFirstElement, $data) {
        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;

        // Create root element
        $root = $xml->createElement($rootElementName);
        $xml->appendChild($root);

        foreach ($data as $row) {
            $elementInRoot = $xml->createElement($elementsName);
            $firstColumnKey = key($row);

            if ($firstColumnKey === $attributeForFirstElement) {
                $elementInRoot->setAttribute($attributeForFirstElement, htmlspecialchars($row[$firstColumnKey]));
                unset($row[$firstColumnKey]);
            }

            foreach ($row as $column => $value) {
                $element = $xml->createElement($column, htmlspecialchars($value));
                $elementInRoot->appendChild($element);
            }

            $root->appendChild($elementInRoot);
        }

        $this->saveXmlFile($xml, $outputFileName);
    }
}
