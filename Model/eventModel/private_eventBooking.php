<?php

class private_eventBooking {
    private $customerid;
    private $firstname;
    private $lastname;
    private $event_id;
    private $title; 
    private $location;
    private $type;
    private $deposit; 
    private $numberOfAttendees;

    public function __construct($customerid, $firstname, $lastname, $event_id, $title, $price, $number, $location, $type, $deposit, $numberOfAttendees) {
        $this->customerid = $customerid;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->event_id = $event_id;       
        $this->title = $title;                      
        $this->price = $price;
        $this->location = $location;
        $this->number = $number;
        $this->type = $type;
    }

    // Getters
    public function getCustomerID() {
        return $this->customerid;
    }
    
    public function getFirstName() {
        return $this->firstname;
    }
    
    public function getLastName() {
        return $this->lastname;
    }
    
    public function getEventID() {
        return $this->event_id;
    }
    
    public function getTitle() {
        return $this->title;
    }
    
    public function getNumberOfAttendees() {
        return $this->numberOfAttendees;
    }
  
    public function getDeposit() {
        return $this->deposit;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getType() {
        return $this->type;
    }
}
?>
