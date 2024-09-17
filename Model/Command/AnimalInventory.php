<?php

require_once 'C:\xampp\htdocs\ZooManagementSystem\Config\databaseConfig.php';
require_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\Inventory.php';

class AnimalInventory extends Inventory {

    private $id; // animal id of individual animal
    private $name; // animal name like Leo, Billion
    private $species; //lion, giraffee
    private $subspecies; //Panthera leo
    private $categories;
    private $age;
    private $gender;
    private $date_of_birth;
    private $avg_lifespan;
    private $description;
    private $height;
    private $weight;
    private $healthStatus;
    private $habitatid;

    //deleted id from constructor because id is auto generated in database
    public function __construct($itemName, $itemType, $storageLocation, $reorderThreshold, $quantity = null, $name = null, $species = null, $subspecies = null, $categories = null, $age = null, $gender = null, $date_of_birth = null, $avg_lifespan = null, $description = null, $height = null, $weight = null, $healthStatus = null, $habitatid = null) {
        parent::__construct($itemName, $itemType, $storageLocation, $reorderThreshold, $quantity);
        $this->name = $name;
        $this->species = $species;
        $this->subspecies = $subspecies;
        $this->categories = $categories;
        $this->age = $age;
        $this->gender = $gender;
        $this->date_of_birth = $date_of_birth;
        $this->avg_lifespan = $avg_lifespan;
        $this->description = $description;
        $this->height = $height;
        $this->weight = $weight;
        $this->healthStatus = $healthStatus;
        $this->habitatid = $habitatid;
    }
    
    public function addItemRecord($data) {
        
    }

// Getters and setters for Animal-specific attributes
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getSpecies() {
        return $this->species;
    }

    public function setSpecies($species) {
        $this->species = $species;
    }

    public function getAge() {
        return $this->age;
    }

    public function setAge($age) {
        $this->age = $age;
    }

    public function getGender() {
        return $this->gender;
    }

    public function setGender($gender) {
        $this->gender = $gender;
    }

    public function getDateOfBirth() {
        return $this->date_of_birth;
    }

    public function setDateOfBirth($date_of_birth) {
        $this->date_of_birth = $date_of_birth;
    }

    public function getAvgLifespan() {
        return $this->avg_lifespan;
    }

    public function setAvgLifespan($avg_lifespan) {
        $this->avg_lifespan = $avg_lifespan;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getHeight() {
        return $this->height;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function getWeight() {
        return $this->weight;
    }

    public function setWeight($weight) {
        $this->weight = $weight;
    }

    public function getHealthStatus() {
        return $this->healthStatus;
    }

    public function setHealthStatus($healthStatus) {
        $this->healthStatus = $healthStatus;
    }

    public function getHabitatid() {
        return $this->habitatid;
    }

    public function setHabitatid($habitatid): void {
        $this->habitatid = $habitatid;
    }

    public function getHabitat() {
        return $this->habitat;
    }

    public function setHabitat($habitat) {
        $this->habitat = $habitat;
    }

    public function getCategories() {
        return $this->categories;
    }

    public function setCategories($categories): void {
        $this->categories = $categories;
    }

    // Method to add a new animal to the database !!!!!!!!!!!should put in model
    public function addAnimal() {
        $db = new databaseConfig();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare(
                "INSERT INTO animalinventory 
            (name, species, subspecies, categories, age, gender, date_of_birth, avg_lifespan, description, height, weight, healthStatus, habitat_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            $this->name, $this->species, $this->subspecies, $this->categories, $this->age,
            $this->gender, $this->date_of_birth, $this->avg_lifespan, $this->description, $this->height,
            $this->weight, $this->healthStatus, $this->habitatid
        ]);

        return $pdo->lastInsertId();
    }

    public function removeItemRecord($records) {
        return $this->removeRecordFromDB($this->itemType, $records);
    }
    
    public function getLastRecordID(){
        
    }
    
    public function updateImage($uniqueFileName, $itemId){
        
    }
    
     public function editItemRecord($data) {
       
    }
    
    public function getRecordDetails($inventoryId, $itemId) {
    }
}
