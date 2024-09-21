`<?php
require_once 'EventInterface.php';

abstract class Event implements EventInterface {
    protected $id;
    protected $title;
    protected $description;
    protected $location; 

    public function __construct($id, $title, $description,$location) {
        $this->id = $id;
        $this->title = $title;      
        $this->description = $description;
        $this->location = $location;            
    }

    public function getId() {
        return $this->id;
    }
    
    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }


    public function getLocation() {
        return $this->location;
    }
    
    abstract public function getType();
    abstract public function getPrice();
    abstract public function getNumberOfAttendees();
    abstract public function getCapacity();
    abstract public function getDeposit(); 
    abstract public function getEndTime(); 
    abstract public function getStartTime(); 
    abstract public function getDate(); 
}
?>
