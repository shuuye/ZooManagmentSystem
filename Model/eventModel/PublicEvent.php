<?php
require_once 'Event.php';

class PublicEvent extends Event {
    private $price;
    private $date;
    private $starttime;
    private $endtime;
    private $capacity;

    public function __construct($id, $title,$price, $date, $starttime, $endtime, $location, $description, $capacity) {
        parent::__construct($id, $title, $description, $location);
        $this->price = $price;
        $this->date = $date;
        $this->starttime = $starttime;
        $this->endtime = $endtime;
        $this->capacity = $capacity;
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

    public function getType() {
        return 'Public';
    }

    public function getPrice() {
        return $this->price;
    }

    public function getNumberOfAttendees() {
        return null;
    }

    public function getCapacity() {
        return $this->capacity;
    }

    public function getDeposit() {
        return null;
    }
}
?>
