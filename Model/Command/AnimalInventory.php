<?php

require_once '../../Config/AnimalDB/dbConnection.php';
require_once 'Inventory.php';
require_once 'InventoryCommand.php';

class AnimalInventory extends Inventory
{
    private $id; // animal id of individual animal
    private $name; // animal name like Leo, Billion
    //private $category;  replace with subspecies
    private $species; //lion, giraffee
    private $subspecies; //Panthera leo
    private $age;
    private $gender;
    private $date_of_birth;
    private $avg_lifespan;
    private $description;
    private $height;
    private $weight;
    private $healthStatus;
    private $habitatid;

    
    public function __construct(
    $id , $name ,$category, $species, $age, $gender, $description, 
    $inventory_id = null, $item_name = null, $date_of_birth = null, 
    $avg_lifespan = null, $height = null, $weight = null, $healthStatus = null, 
    $habitatid = null, $quantity = 0
) {
    parent::__construct($inventory_id, $item_name, $quantity);
    $this->id = $id;
    $this->name = $name;
    $this->category = $category;
    $this->species = $species;
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
    
    public function getSpecies()
    {
        return $this->species;
    }

    public function setSpecies($species)
    {
        $this->species = $species;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function setAge($age)
    {
        $this->age = $age;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    public function getDateOfBirth()
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth($date_of_birth)
    {
        $this->date_of_birth = $date_of_birth;
    }

    public function getAvgLifespan()
    {
        return $this->avg_lifespan;
    }

    public function setAvgLifespan($avg_lifespan)
    {
        $this->avg_lifespan = $avg_lifespan;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    public function getHealthStatus()
    {
        return $this->healthStatus;
    }

    public function setHealthStatus($healthStatus)
    {
        $this->healthStatus = $healthStatus;
    }

    public function getHabitatid() {
        return $this->habitatid;
    }

    public function setHabitatid($habitatid): void {
        $this->habitatid = $habitatid;
    }

        
    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category): void {
        $this->category = $category;
    }
    
    public function addAnimal() {
        $db = new dbConnection();
        $pdo = $db->getPDO();
        
        $stmt = $pdo->prepare(
            "INSERT INTO animals 
            (animal_name, category, species, age, gender, date_of_birth, avg_lifespan, description, animal_height, animal_weight, health_status, habitat_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        
        $stmt->execute([
            $this->name, $this->category, $this->species, $this->age, $this->gender,
            $this->date_of_birth, $this->avg_lifespan, $this->description, $this->height,
            $this->weight, $this->healthStatus, $this->habitatid
        ]);

        return $pdo->lastInsertId();
    }
    
 
//   public function addAnimal($name, $category, $species, $age, $gender, $description) {
//    $db = new dbConnection();
//    $pdo = $db->getPDO();
//    
//    // Correct SQL query (No NULL, placeholders must match the number of fields)
//    $stmt = $pdo->prepare("INSERT INTO animals (animal_name, category, species, age, gender, description) VALUES (?, ?, ?, ?, ?, ?)");
//    
//    // Execute the query with the provided data
//    $stmt->execute([$name, $category, $species, $age, $gender, $description]);
//
//    // Return the last inserted ID
//    return $pdo->lastInsertId();
//}


    // Optionally, add a method to update the animal details in the database
    // 
//    public function updateAnimalInDatabase() {
//        $db = new dbConnection();
//        $pdo = $db->getPDO();
//
//        $stmt = $pdo->prepare("UPDATE animals SET name = ?, species = ?, age = ? WHERE id = ?");
//        $stmt->execute([$this->name, $this->species, $this->age, $this->id]);
//    }
    
    
}