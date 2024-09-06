<?php

//This model represents the data for an animal. When the animal's information changes, it will notify all observers. It is the concrete subject
include_once '../../Config/databaseConfig.php';
require_once '../../Model/Inventory/InventoryModel.php';
require_once 'subject.php';
require_once 'HealthObserver.php';
require_once 'HabitatObserver.php';

class AnimalModel extends databaseConfig implements subject{

    private $db;
    private $observers = array(); // Observer pattern methods
    private $inventoryModel;
    private $habitatData;
    private $id; // this is animal id
    private $healthRecordId;
    
    public function __construct() {
        $this->db = new databaseConfig();
        $this->inventoryModel = new InventoryModel(); 
        // Add observers
        $this->attach(new HealthObserver());
        $this->attach(new HabitatObserver());
    }
    
    // Observer methods ---------------------------------------------------------------------
    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer) {
        $this->observers = array_filter($this->observers, function($obs) use ($observer) {
            return $obs !== $observer;
        });
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
    
  // Setter and Getter-------------------------------------------------------------------------
  
    // Set habitat data and notify observers
    public function setHabitatData($habitatData) {
        $this->habitatData = $habitatData;
        $this->notify();
    }
    
    public function getHabitatData() {
        return $this->habitatData;
    }
    
    function getId() {
        return $this->id;
    }

    function setId($id): void {
        $this->id = $id;
    }

    
  // Animal function -------------------------------------------------------------------------------------------------------------------
 
 // Add new animal details
    public function addAnimal($inventoryId, $animalDetails) {
        $query = "INSERT INTO animalinventory (inventoryId, name, species, subspecies, categories, age, gender, date_of_birth, avg_lifespan, description, height, weight, habitat_id) 
                  VALUES (:inventoryId, :name, :species, :subspecies, :categories, :age, :gender, :date_of_birth, :avg_lifespan, :description, :height, :weight,:habitat_id)";
        $stmt = $this->db->getConnection()->prepare($query);

        // Bind parameters
        $stmt->bindParam(':inventoryId', $inventoryId, PDO::PARAM_INT);
        $stmt->bindParam(':name', $animalDetails['name']);
        $stmt->bindParam(':species', $animalDetails['species']);
        $stmt->bindParam(':subspecies', $animalDetails['subspecies']);
        $stmt->bindParam(':categories', $animalDetails['categories']);
        $stmt->bindParam(':age', $animalDetails['age'], PDO::PARAM_INT);
        $stmt->bindParam(':gender', $animalDetails['gender']);
        $stmt->bindParam(':date_of_birth', $animalDetails['date_of_birth']);
        $stmt->bindParam(':avg_lifespan', $animalDetails['avg_lifespan'], PDO::PARAM_INT);
        $stmt->bindParam(':description', $animalDetails['description']);
        $stmt->bindParam(':height', $animalDetails['height']);
        $stmt->bindParam(':weight', $animalDetails['weight']);
        $stmt->bindParam(':habitat_id', $animalDetails['habitat_id'], PDO::PARAM_INT);

        // Execute the query
        return $stmt->execute();
    }
    

   public function getAnimalsByCategory($category = null) {
        $query = "SELECT * FROM animalinventory";
        if ($category) {
            $query .= " WHERE categories = :category";
        }
        $query .= " ORDER BY id ASC";

        $stmt = $this->db->getConnection()->prepare($query);
        if ($category) {
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getLastInsertedAnimalId() {
        $query = "SELECT id FROM animalinventory ORDER BY id DESC LIMIT 1";
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['id'];
        } else {
            return false; 
        }
    }

    public function getAnimalImage($animalId) {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare("SELECT image_path FROM animal_image WHERE animal_id = ?");
        $stmt->execute([$animalId]);
        
        $imageResult = $stmt->fetch();
        
        return $imageResult ? $imageResult['image_path'] : null;
    }
    
    public function addAnimalImage($animalId, $imagePath) {
        $query = "INSERT INTO animal_image (animal_id, image_path) VALUES (:animal_id, :image_path)";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':animal_id', $animalId, PDO::PARAM_INT);
        $stmt->bindParam(':image_path', $imagePath);
        return $stmt->execute();
    }
    
    
    
    // Function for habitat-------------------------------------------------------------------------------------------------------
    // Function to insert a new habitat
    public function insertNewHabitat($habitat_name, $availability, $capacity, $environment, $description) {
        try {
            $pdo = $this->db->getConnection();
            $sql = "INSERT INTO habitats (habitat_name, availability, capacity, environment, description) 
                    VALUES (:habitat_name, :availability, :capacity, :environment, :description)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':habitat_name', $habitat_name);
            $stmt->bindParam(':availability', $availability);
            $stmt->bindParam(':capacity', $capacity);
            $stmt->bindParam(':environment', $environment);
            $stmt->bindParam(':description', $description);

            if ($stmt->execute()) {
                echo "New habitat added successfully.";
                header('Location: list_habitats.php');
            } else {
                echo "Failed to add new habitat.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public function getAvailableHabitats() { //use at add animal form
        $query = "SELECT habitat_id, habitat_name FROM habitats WHERE availability = 'Available'";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getHabitatById($habitat_id) {
      try {
          $pdo = $this->db->getConnection();
          $sql = "SELECT * FROM habitats WHERE habitat_id = :habitat_id";
          $stmt = $pdo->prepare($sql);
          $stmt->bindParam(':habitat_id', $habitat_id);
          $stmt->execute();
          $habitat = $stmt->fetch(PDO::FETCH_ASSOC);
          if (!$habitat) {
              throw new Exception("Habitat not found with ID: $habitat_id");
          }
          return $habitat;
      } catch (PDOException $e) {
          echo "Error: " . $e->getMessage();
      }
  }
  
  
    // Function to update an existing habitat
    public function updateHabitat($habitat_id, $habitat_name, $availability, $capacity, $environment, $description) {
        try {
            $pdo = $this->db->getConnection();
            $sql = "UPDATE habitats SET habitat_name = :habitat_name, availability = :availability,
                    capacity = :capacity, environment = :environment, description = :description 
                    WHERE habitat_id = :habitat_id";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':habitat_id', $habitat_id);
            $stmt->bindParam(':habitat_name', $habitat_name);
            $stmt->bindParam(':availability', $availability);
            $stmt->bindParam(':capacity', $capacity);
            $stmt->bindParam(':environment', $environment);
            $stmt->bindParam(':description', $description);

            if ($stmt->execute()) {
                echo "Habitat updated successfully.";
                
            } else {
                echo "Failed to update habitat.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Function to get all habitats
    public function getAllHabitats() {
        try {
            $pdo = $this->db->getConnection();
            $sql = "SELECT * FROM habitats";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public function deleteHabitat($habitat_id) {
        $sql = "DELETE FROM habitats WHERE habitat_id = :habitat_id";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindParam(':habitat_id', $habitat_id);
        return $stmt->execute();
    }
    
    
    // health function ----------------Use Xml and database update both-----------------------------------------------------------------------------
    // Health record functions -------------------------------------------------------------------------
    
    public function getAllAnimalIds() { 
     $query = "SELECT id FROM animalinventory ORDER BY id ASC";
     $stmt = $this->db->getConnection()->prepare($query);
     $stmt->execute();
     $animalIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
     return $animalIds;
 }

    
    public function getHealthRecordIdByAnimalId($animalId) {
        $query = "SELECT health_id FROM animalinventory WHERE id = :animal_id";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':animal_id', $animalId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn(); // returns health_id if it exists
    }

    
    public function insertHealthRecord($animalId, $lastCheckup, $treatments, $healthStatus) { // add new health record
      $conn = $this->db->getConnection();

      // Prepare the query to insert a new health record
      $query = "INSERT INTO health_records (animal_id, last_checkup, treatments, healthStatus)
                VALUES (:animal_id, :last_checkup, :treatments, :healthStatus)";

      $stmt = $conn->prepare($query);
      $stmt->bindParam(':animal_id', $animalId);
      $stmt->bindParam(':last_checkup', $lastCheckup);
      $stmt->bindParam(':treatments', $treatments);
      $stmt->bindParam(':healthStatus', $healthStatus);
      $stmt->execute();

      // Retrieve the last inserted ID
      $healthRecordId = $conn->lastInsertId();

      return $healthRecordId;
  }

   public function updateAnimalHealthRecordId($animalId, $healthRecordId) { // update to animal id and health id  inventory table
    $conn = $this->db->getConnection();

    $query = "UPDATE animalinventory
               SET health_id = :health_id
               WHERE id = :animal_id";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':health_id', $healthRecordId);
    $stmt->bindParam(':animal_id', $animalId);
    $stmt->execute();
}
 
    public function editHealthRecord($healthRecordId, $animalId, $lastCheckup, $treatments, $healthStatus) {
        $query = "UPDATE health_records SET animal_id = :animal_id, last_checkup = :last_checkup, 
                  treatments = :treatments, healthStatus = :healthStatus 
                  WHERE hRecord_id = :health_record_id";
        
        $this->healthRecordId = $healthRecordId;
        
        $stmt = $this->db->getConnection()->prepare($query);

        $stmt->bindParam(':health_record_id', $healthRecordId, PDO::PARAM_INT);
        $stmt->bindParam(':animal_id', $animalId, PDO::PARAM_INT);
        $stmt->bindParam(':last_checkup', $lastCheckup, PDO::PARAM_STR);
        $stmt->bindParam(':treatments', $treatments, PDO::PARAM_STR);
        $stmt->bindParam(':healthStatus', $healthStatus, PDO::PARAM_STR);

        $stmt->execute();
        $this->notify($healthRecordId); // notify observer
    }
    
        public function getHealthRecord($healthRecordId) {
        $query = "SELECT * FROM health_records WHERE hRecord_id = :health_record_id";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':health_record_id', $healthRecordId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    
    // Function for animal food management ...........................................................................................................................
    // Retrieve food inventory
    
    public function getFoodInventory() {
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM foodinventory");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllFeedingRecords() {
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM animalfeeding");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function feedingRecordExists($animal_id) {
     $stmt = $this->db->getConnection()->prepare(
         "SELECT COUNT(*) FROM animalfeeding 
          WHERE animal_id = :animal_id"
     );
     $stmt->execute([
         ':animal_id' => $animal_id
     ]);
     return $stmt->fetchColumn() > 0;
 }

    
public function addOrUpdateFeedingRecord($animal_id, $food_id, $feeding_time, $quantity_fed) {
    if ($this->feedingRecordExists($animal_id)) {
        // Update existing record
        $stmt = $this->db->getConnection()->prepare(
            "UPDATE animalfeeding 
             SET food_id = :food_id, feeding_time = :feeding_time, quantity_fed = :quantity_fed 
             WHERE animal_id = :animal_id"
        );
        $stmt->execute([
            ':animal_id' => $animal_id,
            ':food_id' => $food_id,
            ':feeding_time' => $feeding_time,
            ':quantity_fed' => $quantity_fed
        ]);
    } else {
        // Insert new record
        $stmt = $this->db->getConnection()->prepare(
            "INSERT INTO animalfeeding (animal_id, food_id, feeding_time, quantity_fed) 
             VALUES (:animal_id, :food_id, :feeding_time, :quantity_fed)"
        );
        $stmt->execute([
            ':animal_id' => $animal_id,
            ':food_id' => $food_id,
            ':feeding_time' => $feeding_time,
            ':quantity_fed' => $quantity_fed
        ]);
    }
    $this->notify(); // Notify observers after adding or updating a feeding record
}


//    // Add a new feeding record
//    public function addFeedingRecord($animal_id, $food_id, $feeding_time, $quantity_fed) {
//        $stmt = $this->db->getConnection()->prepare("INSERT INTO animalfeeding (animal_id, food_id, feeding_time, quantity_fed) VALUES (:animal_id, :food_id, :feeding_time, :quantity_fed)");
//        $stmt->execute([
//            ':animal_id' => $animal_id,
//            ':food_id' => $food_id,
//            ':feeding_time' => $feeding_time,
//            ':quantity_fed' => $quantity_fed
//        ]);
//        $this->notify(); // Notify observers after adding a feeding record
//    }

    // Retrieve consumption patterns for a specific animal
    public function getConsumptionPatterns($animal_id, $start_date, $end_date) {
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM foodconsumption WHERE animal_id = :animal_id AND start_date >= :start_date AND end_date <= :end_date");
        $stmt->execute([
            ':animal_id' => $animal_id,
            ':start_date' => $start_date,
            ':end_date' => $end_date
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new consumption record
    public function addConsumptionRecord($animal_id, $total_quantity_fed, $start_date, $end_date) {
        $stmt = $this->db->getConnection()->prepare("INSERT INTO foodconsumption (animal_id, total_quantity_fed, start_date, end_date) VALUES (:animal_id, :total_quantity_fed, :start_date, :end_date)");
        $stmt->execute([
            ':animal_id' => $animal_id,
            ':total_quantity_fed' => $total_quantity_fed,
            ':start_date' => $start_date,
            ':end_date' => $end_date
        ]);
        $this->notify(); // Notify observers after adding a consumption record
    }

    
  

}
?>

