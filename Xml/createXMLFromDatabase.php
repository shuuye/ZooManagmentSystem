<?php

require_once __DIR__ . '/../Config/databaseConfig.php';

class createXMLFromDatabase {

    private function fetchData($tableName, $db) { //fetches data from a specified database table.
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
        //generates XML elements from the fetched database data.
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
                $elementInRoot->setAttribute($attributeForFirstElement, htmlspecialchars($row[$firstColumnKey]));
                unset($row[$firstColumnKey]); // Remove 'id' from the row to avoid duplication as child element
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

    private function generateXMLDataFromTable($tableName, $outputFileName, $db, $rootElementName, $elementsName, $attributeForFirstElement) {
        //fetching data from the database and creating an XML file.
        try {
            $dataOfTable = $this->fetchData($tableName, $db);

            if ($dataOfTable->rowCount() > 0) {
                // Start the XML document
                $xml = new DOMDocument('1.0', 'UTF-8');
                $xml->formatOutput = true;

                $xml = $this->setXMLElements($xml, $dataOfTable, $rootElementName, $elementsName, $attributeForFirstElement);

                // Save the XML file
                $xml->save($outputFileName);
                echo "XML file created successfully at $outputFileName";
            } else {
                echo "0 results found in the table.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function createXMLFileByTableName($tableName = "", $outputFileName = "", $rootElementName = "", $elementsName = "", $attributeForFirstElement = "") {
        //creating an XML file from a database table
        $database = new databaseConfig();
        $db = $database->getConnection();

        if (!empty($tableName) && !empty($outputFileName)) {
            $this->generateXMLDataFromTable($tableName, $outputFileName, $db, $rootElementName, $elementsName, $attributeForFirstElement);
        } else {
            echo "tableName or outputFileName cannot be Empty";
        }
    }

    public function displayXMLData($xmlFileName) {
        //displays the contents of an XML file.
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

    public function transformXMLUsingXSLT($xmlFileName, $xsltFileName, $outputFileName) { //pamela
        //applies an XSLT transformation to an XML file and saves the result to a new file.
        if (file_exists($xmlFileName) && file_exists($xsltFileName)) {
            $xml = new DOMDocument();
            $xml->load($xmlFileName);

            $xsl = new DOMDocument();
            $xsl->load($xsltFileName);

            $proc = new XSLTProcessor();
            $proc->importStyleSheet($xsl);

            $output = $proc->transformToXML($xml);
            file_put_contents($outputFileName, $output);

            echo "Transformation complete. Output saved to $outputFileName";
        } else {
            echo "One or both files do not exist.";
        }
    }

    public function XSLTTransformation($xmlfilename, $xslfilename) {
        //without saving to file
        if (file_exists($xmlFileName) && file_exists($xsltFileName)) {
            $xml = new DOMDocument();
            $xml->load($xmlfilename);

            $xsl = new DOMDocument();
            $xsl->load($xslfilename);

            $proc = new XSLTProcessor();
            $proc->importStylesheet($xsl);

            echo $proc->transformToXml($xml);
        } else {
            echo "One or both files do not exist.";
        }
    }

    public function queryXMLUsingXPath($xmlFileName, $xpathQuery) {
        //queries an XML file using an XPath expression and displays the results.
        if (file_exists($xmlFileName)) {
            $xml = new DOMDocument();
            $xml->load($xmlFileName);

            $xpath = new DOMXPath($xml);
            $entries = $xpath->query($xpathQuery);

            foreach ($entries as $entry) {
                echo $entry->nodeValue . "<br/>";
            }
        } else {
            echo "The file $xmlFileName does not exist.";
        }
    }
}

//$xmlGenerator = new createXMLFromDatabase();
//$xmlGenerator->createXMLFileByTableName("", "");
?>