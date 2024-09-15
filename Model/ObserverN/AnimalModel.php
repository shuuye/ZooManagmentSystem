<?php

//This model represents the data for an animal. When the animal's information changes, it will notify all observers. It is the concrete subject.
//The AnimalModel class implements the subject interface and is responsible for managing animal data. It maintains a list of observers and notifies them when data changes.

include_once '../../Config/databaseConfig.php';
require_once '../../Model/Inventory/InventoryModel.php';
require_once 'subject.php';
require_once 'HealthObserver.php';
require_once 'HabitatObserver.php';
require_once 'AnimalObserver.php';
require_once 'FoodObserver.php';

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
        $this->attach(new AnimalObserver());
        $this->attach(new FoodObserver());
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
    
    //Animal  
    function setId($id): void {
        $this->id = $id;
    }
    
    function getId() {
        return $this->id;
    }

    //Habitat
    public function setHabitatData($habitatData) {
        $this->habitatData = $habitatData;
        $this->notify(); // Notify observers when habitat data changes
    }
    
    public function getHabitatData() {
        return $this->habitatData;
    }
    
    //Health
    public function setHealthRecordId($healthRecordId) {
        $this->healthRecordId = $healthRecordId;
        $this->notify(); // Notify observers of the health record change
    }

    public function getHealthRecordId() {
        return $this->healthRecordId;
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

        // Execute the query old version
//        return $stmt->execute();
        
        // Execute the query
        $success = $stmt->execute();

        if ($success) {
            $animalId = $this->getLastInsertedAnimalId();
            $this->setId($animalId);
            $this->notify(); // Notify observers of the new animal addition
        }
        
        return $success;
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
    
      public function getAnimalById($id) {
        $query = "SELECT * FROM animalinventory WHERE id = :id";
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC); // Return animal as an associative array
    }
    
    // Method to update an animal
    public function updateAnimal($animalId, $animalDetails) {
        $query = "UPDATE animalinventory SET name = :name, species = :species, subspecies = :subspecies, categories = :categories, age = :age, gender = :gender, date_of_birth = :date_of_birth, avg_lifespan = :avg_lifespan, description = :description, height = :height, weight = :weight, habitat_id = :habitat_id WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($query);

        $stmt->bindParam(':id', $animalId, PDO::PARAM_INT);
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

        return $stmt->execute();
    }

    // Method to delete an animal
    public function deleteAnimal($animalId) {
        $query = "DELETE FROM animalinventory WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $animalId, PDO::PARAM_INT);
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
      $this->setHealthRecordId($healthRecordId); // Update and notify observers
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
        $this->setHealthRecordId($healthRecordId); // Update and notify observers
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
    
    $this->notify([ // observer
            'animal_id' => $animal_id,
            'food_id' => $food_id,
            'feeding_time' => $feeding_time,
            'quantity_fed' => $quantity_fed
        ]);
    
}

}
?>

