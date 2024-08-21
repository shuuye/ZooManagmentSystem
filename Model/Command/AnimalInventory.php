<?php

require_once 'Inventory.php';
require_once 'InventoryCommand.php';

class AnimalInventory extends Inventory
{
    private $species;
    private $age;
    private $gender;
    private $date_of_birth;
    private $avg_lifespan;
    private $description;
    private $height;
    private $weight;
    private $healthStatus;

    public function __construct($inventory_id, $item_name, $species, $age, $gender, $date_of_birth, $avg_lifespan, $description, $height, $weight, $healthStatus, $habitat, $quantity = 0)
    {
        parent::__construct($inventory_id, $item_name, $quantity);
        $this->species = $species;
        $this->age = $age;
        $this->gender = $gender;
        $this->date_of_birth = $date_of_birth;
        $this->avg_lifespan = $avg_lifespan;
        $this->description = $description;
        $this->height = $height;
        $this->weight = $weight;
        $this->healthStatus = $healthStatus;
        $this->habitat = $habitat;
    }

    // Getters and setters for Animal-specific attributes
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

    public function getHabitat()
    {
        return $this->habitat;
    }

    public function setHabitat($habitat)
    {
        $this->habitat = $habitat;
    }
}