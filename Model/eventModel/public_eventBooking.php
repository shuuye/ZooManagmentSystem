<?php

class public_eventBooking {
    private $customerid;
    private $firstname;
    private $lastname;
    private $event_id;
    private $title;
    private $price;
    private $date;
    private $starttime;
    private $endtime;
    private $location;
    private $type;
    private $number;

    public function __construct($customerid, $firstname, $lastname, $event_id, $title, $price, $number, $date, $starttime, $endtime, $location, $type) {
        $this->customerid = $customerid;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->event_id = $event_id;       
        $this->title = $title;                      
        $this->price = $price;
        $this->number = $number;
        $this->date = $date;
        $this->starttime = $starttime;
        $this->endtime = $endtime;
        $this->location = $location;
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
    
    public function getPrice() {
        return $this->price;
    }
    
    public function getNumber() {
        return $this->number;
    }
    
    public function getDate() {
        return $this->date;
    }

    public function getStartTime() {
        return $this->starttime;
    }

    public function getEndTime() {
        return $this->endtime;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getType() {
        return $this->type;
    }
}
?>
