<?php

class Habitat {
    private $id;
    private $name;
    private $availability;
    private $capacity;
    private $environment;
    private $description;

    public function __construct($id, $name, $availability,$capacity, $environment, $description) {
        $this->id = $id;
        $this->name = $name;
        $this->availability = $availability;
        $this->capacity = $capacity;
        $this->environment = $environment;
        $this->description = $description;
    }

    // Getters and Setters for all properties
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getAvailability() {
        return $this->availability;
    }

    public function getCapacity() {
        return $this->capacity;
    }

    public function getEnvironment() {
        return $this->environment;
    }

    public function getDescription() {
        return $this->description;
    }

    
    // Add setters if you need them
}
?>
