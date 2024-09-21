<?php
interface EventInterface {
    public function getId();
    public function getTitle();
    public function getDescription();
    //public function getImage();
    public function getLocation();
    
    public function getDate();
    public function getStartTime();
    public function getEndTime();
    public function getType();
    
    public function getPrice();
    public function getNumberOfAttendees();
    public function getCapacity();
    public function getDeposit();
}
?>
