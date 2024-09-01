<?php

require_once '../../Model/ObserverN/AnimalModel.php';
require_once '../../Model/ObserverN/HealthObserver.php';
require_once '../../Model/XmlGenerator.php';

class HealthController {
    
    private $animalModel;

    public function __construct() {
        $this->animalModel = new AnimalModel();
        $this->healthObserver = new HealthObserver();
    }
    
    public function getAnimalIds() {
        return $this->animalModel->getAllAnimalIds();
    }
    
 
 
    public function getAnimalsWithoutCompleteHealthRecords() { // only allow user add the animal which records are imcomplete
        return $this->animalModel->getAnimalsWithoutCompleteHealthRecords();
    }

   
    public function addHealthRecord($animalId, $lastCheckup, $treatments, $healthStatus) {
    // Insert new health record
   $healthRecordId = $this->animalModel->insertHealthRecord($animalId, $lastCheckup, $treatments, $healthStatus);

    // Update the animalinventory table with the new health record ID
    $this->animalModel->updateAnimalHealthRecordId($animalId, $healthRecordId);

    // Notify the observers
    $this->healthObserver->update($this->animalModel);

    // Redirect to a success page or show a success message
    echo 'Successfully added new records';
    exit();
}
    
     public function handleAddHealthRecordForm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $animal_id = $_POST['animal_id'];
            $lastCheckup = $_POST['last_checkup'];
            $treatments = $_POST['treatments'];
            $healthStatus = $_POST['healthStatus'];
            $this->addHealthRecord($animal_id, $lastCheckup, $treatments, $healthStatus);
        }
    }

    public function updateHealthRecord($healthRecordId, $animalId, $lastCheckup, $treatments, $healthStatus) {
        $this->animalModel->editHealthRecord($healthRecordId, $animalId, $lastCheckup, $treatments, $healthStatus);
        // Redirect or return a success message
    }

    public function deleteHealthRecord($healthRecordId) {
        $this->animalModel->removeHealthRecord($healthRecordId);
        // Redirect or return a success message
    }
    
   public function displayHealthRecords() {
        $xmlGenerator = new XmlGenerator();
        
        // Generate XML from health_records table
        $xmlGenerator->createXMLFileByTableName(
            "health_records",
            "../../Model/Xml/health_records.xml",
            "HealthRecords",
            "HealthRecord",
            "hRecord_id"
        );
        
        // Transform XML using XSLT
        $xmlGenerator->transformXMLUsingXSLT(
            "../../Model/Xml/health_records.xml",
            "../../Model/ObserverN/health_records.xsl",
            "animal_health_report.html"
        );
        
        // Render HTML
        include 'animal_health_report.html';
    }
  

    
}
