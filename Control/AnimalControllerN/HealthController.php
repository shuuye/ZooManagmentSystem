<?php


//require_once '../../Model/ObserverN/AnimalModel.php';
//require_once '../../Model/XmlGenerator.php';
include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\ObserverN\AnimalModel.php';
include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\XmlGenerator.php';


class HealthController {
    
    private $animalModel;

    public function __construct() {
        $this->animalModel = new AnimalModel();
//        $this->healthObserver = new HealthObserver();
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
//        header('Location: ../../View/AnimalView/list_healthRecords.php');
       header('Location: /ZooManagementSystem/View/AnimalView/list_healthRecords.php');
        exit();

   }

    
     public function handleAddHealthRecordForm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['form_type'] === 'health_form') {
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
        
        $xmlGenerator->transformXMLUsingXSLTForDisplay(
            "../../Model/Xml/health_records.xml",
            "../../Model/ObserverN/health_records.xsl"
        );
        
        // Load and filter XML with XPath
        $filteredXmlContent = $xmlGenerator->queryXMLUsingXPath(
            "../../Model/Xml/health_records.xml",
            "//HealthRecord[healthStatus='Warning']"
        );

        // Transform the filtered XML using XSLT and save to output file
        $xmlGenerator->transformFilteredXMLUsingXSLT(
            $filteredXmlContent,
            "../../Model/ObserverN/health2_records.xsl",
            "animal_health_report.html"
        ); 
        
    }
    
        public function displayHealthStatusCounts() {
        $xmlGenerator = new XmlGenerator();

        // Generate XML from health_records table
        $xmlGenerator->createXMLFileByTableName(
            "health_records",
            "../../Model/Xml/health_records.xml",
            "HealthRecords",
            "HealthRecord",
            "hRecord_id"
        );
        // Get health status counts
          $statusCounts = $xmlGenerator->countHealthStatuses("../../Model/Xml/health_records.xml");
        if ($statusCounts) {
            foreach ($statusCounts as $status => $count) {
                echo "Total $status: $count<br/>";
            }
        }
    }

    
}

// Initialize and handle the request
$controller = new HealthController();
?>
