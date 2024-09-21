<?php

interface EventFactoryInterface {
    public function createEvent($type, $title,$description, $location, $date = null, $starttime = null, $endtime = null, $price = null, $capacity = null, $deposit = null, $numberOfAttendees = null);
    public function fetchEventsByType($type = null);
    public function deleteEvents($eventIds);
    public function isPublicEventExists($date, $location);
    public function isPrivateEventExists($location);
    public function getPublicBookingsCountByEventId($eventIds);
    public function getPrivateBookingsCountByEventId($eventIds);
    
    
    
}
