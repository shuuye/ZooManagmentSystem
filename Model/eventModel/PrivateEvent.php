<?php
require_once 'Event.php';

class PrivateEvent extends Event {
    private $deposit;
    private $numberOfAttendees;

    public function __construct($id, $title, $location, $description, $deposit, $numberOfAttendees) {
        parent::__construct($id, $title, $description,$location);
        $this->deposit = $deposit;
        $this->numberOfAttendees = $numberOfAttendees;
    }

    public function getDate() {
        return null;
    }

    public function getStartTime() {
        return null;
    }

    public function getEndTime() {
        return null;
    }

    public function getType() {
        return 'Private';
    }

    public function getPrice() {
        return null;
    }

    public function getNumberOfAttendees() {
        return $this->numberOfAttendees;
    }

    public function getCapacity() {
        return null;
    }

    public function getDeposit() {
        return $this->deposit;
    }
}
?>
