<?php
require_once 'Event.php';

class PublicEvent extends Event {
    private $price;
    private $date;
    private $starttime;
    private $endtime;
    private $capacity;

    public function __construct($id, $title, $description, $location, $price, $date, $starttime, $endtime, $capacity) {
        parent::__construct($id, $title, $description, $location); // Call the parent constructor
        $this->price = $price;
        $this->date = $date;
        $this->starttime = $starttime;
        $this->endtime = $endtime;
        $this->capacity = $capacity;
    }
    public function getId() {
        return parent::getId(); // Use the parent's getter
    }

    public function getTitle() {
        return parent::getTitle(); // Use the parent's getter
    }

    public function getDescription() {
        return parent::getDescription(); // Use the parent's getter
    }

    public function getLocation() {
        return parent::getLocation(); // Use the parent's getter
    }
    public function getType() {
        return 'Public';
    }

    public function getPrice() {
        return $this->price;
    }

    public function getNumberOfAttendees() {
        return null; // Adjust based on your logic
    }

    public function getCapacity() {
        return $this->capacity;
    }

    public function getDeposit() {
        return null; // Adjust based on your logic
    }

    public function getEndTime() {
        return $this->endtime;
    }

    public function getStartTime() {
        return $this->starttime;
    }

    public function getDate() {
        return $this->date;
    }
}
?>
