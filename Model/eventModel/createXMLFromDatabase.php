<?php

require_once 'Config/databaseConfig.php';

class createXMLFromDatabase extends databaseConfig {

    private function fetchData($tableName, $db) {
        if (empty($tableName)) {
            throw new Exception("Table name cannot be empty.");
        }

        $sql = "SELECT * FROM " . $tableName;
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new Exception("Statement preparation failed: " . implode(" ", $db->errorInfo()));
        }

        $stmt->execute();
        return $stmt;
    }

    private function setXMLElements($xml, $dataOfTable, $rootElementName, $elementsName, $attributeForFirstElement) {
        $root = $xml->createElement($rootElementName);
        $xml->appendChild($root);

        while ($row = $dataOfTable->fetch(PDO::FETCH_ASSOC)) {
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

        return $xml;
    }

    private function generateXMLDataFromTables($db, $outputFileName) {
        try {
            // Fetch data from events table
            $eventsData = $this->fetchData('events', $db);
            $eventsXml = new DOMDocument('1.0', 'UTF-8');
            $eventsXml->formatOutput = true;

            // Root element for all events
            $eventsRoot = $eventsXml->createElement('events');
            $eventsXml->appendChild($eventsRoot);

            while ($event = $eventsData->fetch(PDO::FETCH_ASSOC)) {
                $eventId = $event['id'];
                $eventType = $event['type'];

                // Create event element
                $eventElement = $eventsXml->createElement('event');
                $eventElement->setAttribute('id', htmlspecialchars($eventId));

                // Common attributes
                foreach (['type', 'title', 'location', 'description'] as $attribute) {
                    if (isset($event[$attribute])) {
                        $element = $eventsXml->createElement($attribute, htmlspecialchars($event[$attribute]));
                        $eventElement->appendChild($element);
                    }
                }

                // Fetch additional attributes based on event type
                if ($eventType === 'Public') {
                    $publicEventData = $this->fetchData('public_event', $db);
                    while ($publicEvent = $publicEventData->fetch(PDO::FETCH_ASSOC)) {
                        if ($publicEvent['event_id'] == $eventId) {
                            foreach (['date', 'starttime', 'endtime', 'price', 'capacity'] as $attribute) {
                                if (isset($publicEvent[$attribute])) {
                                    $element = $eventsXml->createElement($attribute, htmlspecialchars($publicEvent[$attribute]));
                                    $eventElement->appendChild($element);
                                }
                            }
                            break;
                        }
                    }
                } elseif ($eventType === 'Private') {
                    $privateEventData = $this->fetchData('private_event', $db);
                    while ($privateEvent = $privateEventData->fetch(PDO::FETCH_ASSOC)) {
                        if ($privateEvent['event_id'] == $eventId) {
                            foreach (['deposit', 'number_of_attendees'] as $attribute) {
                                if (isset($privateEvent[$attribute])) {
                                    $element = $eventsXml->createElement($attribute, htmlspecialchars($privateEvent[$attribute]));
                                    $eventElement->appendChild($element);
                                }
                            }
                            break;
                        }
                    }
                }

                $eventsRoot->appendChild($eventElement);
            }

            // Save the XML file
            $eventsXml->save($outputFileName);           
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function createXMLFileByTableName($outputFileName = "Model/Xml/events.xml") {
        $db = $this->getConnection(); // Ensure getConnection() is defined in databaseConfig
        $this->generateXMLDataFromTables($db, $outputFileName);
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
}
?>
