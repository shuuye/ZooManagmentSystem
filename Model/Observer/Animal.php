<?php

// This animal class at the end will combine with shuye.

class Animal {
    private $id;
    private $name;
    private $species;
    private $age;

    // Constructor to initialize the Animal object with data from the database
    public function __construct($id, $name, $species, $age) {
        $this->id = $id;
        $this->name = $name;
        $this->species = $species;
        $this->age = $age;
    }

    // Getters for the animal properties
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getSpecies() {
        return $this->species;
    }

    public function getAge() {
        return $this->age;
    }

    // Setters for the animal properties if needed
    public function setName($name) {
        $this->name = $name;
    }

    public function setSpecies($species) {
        $this->species = $species;
    }

    public function setAge($age) {
        $this->age = $age;
    }

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

?>

