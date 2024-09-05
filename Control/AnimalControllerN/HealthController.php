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
    

    public function manageHealthRecord($animalId, $lastCheckup, $treatments, $healthStatus) {
       // Check if the animal already has a health record
       $healthRecordId = $this->animalModel->getHealthRecordIdByAnimalId($animalId);

       if ($healthRecordId) {
           // Update the existing health record
           $this->animalModel->editHealthRecord($healthRecordId, $animalId, $lastCheckup, $treatments, $healthStatus);
       } else {
           // Insert a new health record
           $healthRecordId = $this->animalModel->insertHealthRecord($animalId, $lastCheckup, $treatments, $healthStatus);
           // Update the animal inventory table with the new health record ID
           $this->animalModel->updateAnimalHealthRecordId($animalId, $healthRecordId);
       }

       // Notify the observers
       $this->healthObserver->update($this->animalModel);

       // Redirect or return a success message
       echo'Successful update';
       exit();
   }

    
     public function handleAddHealthRecordForm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $animal_id = $_POST['animal_id'];
            $lastCheckup = $_POST['last_checkup'];
            $treatments = $_POST['treatments'];
            $healthStatus = $_POST['healthStatus'];
            $this->manageHealthRecord($animal_id, $lastCheckup, $treatments, $healthStatus);
        }
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
