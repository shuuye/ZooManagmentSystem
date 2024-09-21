<?php
require_once 'Model/eventModel/private_eventBookingMethod.php'; // Ensure this path is correct
require_once 'View/eventView/eventBookingView.php'; // Ensure this path is correct

class private_eventController extends private_eventBookingMethod{
    private $view;
    private $model;
    private $logFile;

    public function __construct($view) {
        parent::__construct();
        $this->view = $view;
        $this->model = $this; // Create a new instance of the model
        $this->logFile = 'C:\\xampp\\apache\\logs\\privateeventcontroller.log'; // Path to the log file
    }
    
    private function logEvent($message, $type = 'INFO') {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [$type] $message";

    // Generate a cryptographic hash for the log entry
    $hash = hash('sha256', $logMessage);

    // Append the hash to the log message
    $logMessageWithHash = $logMessage . " | Hash: " . $hash . PHP_EOL;

    // Save the log message with the hash to the log file
    file_put_contents($this->logFile, $logMessageWithHash, FILE_APPEND);
}

    public function fetchAndDisplayEvents($type = null) {
        try {                     
            $events = $this->model->fetchPrivateEvents($type);
            $this->view->displayPrivateEventsForm($events);
        } catch (Exception $e) {
            $this->logEvent( $this->view->displayError($e->getMessage()), 'ERROR');
            $this->view->displayError($e->getMessage());
        }
        
    }

    public function displayEventDetails($eventId) {
        try {
            $event = $this->model->fetchPrivateEventById($eventId);
            if ($event) {
                $this->view->displayPrivateEventDetailsForm($event);
            } else {
                $this->view->displayError("Event not found.");
                $this->logEvent($this->view->displayError("Event not found."),'ERROR');
            }
        } catch (Exception $e) {
            $this->view->displayError($e->getMessage());
        }
    }
    
    public function fetchPrivateEventBookings($customerid) {
        try {
            $booking = $this->model->fetchAllPrivateEventBookings($customerid);
            if ($booking) {
                $this->view->displayAllPrivateEventReservation($booking);
            } else {
                $this->view->displayError("Reservation List not found.");
                $this->logEvent(displayError("Reservation List not found."),'ERROR');
            }
        } catch (Exception $e) {
            $this->view->displayError($e->getMessage());
        }
    }
    
    public function displayPrivateBookingSummary($customerid, $eventId, $date) {
    try {
        // Fetch the booking details
        $booking = $this->model->fetchPrivateBookingDetails($customerid, $eventId, $date);
        if ($booking) {
            $this->view->displayPrivateBookingSummary($booking);
        } else {
            $this->view->displayError("Booking details not found.");
            $this->logEvent(displayError("Booking details not found."),'ERROR');
        }
    } catch (Exception $e) {
        $this->view->displayError($e->getMessage());
    }
}

     public function validateAndProcessTicketPurchase($customerid, $fullname,$eventId, $numberofattendees, $number_of_attendees, $type, $title, $location, $deposit, $date, $starttime, $endtime) {
       $errors = [];
        // Validate the inputs
    if (empty($date)) {
        $errors['date'] = 'Date is required.';
        $this->logEvent('Validation error: Date is required.','ERROR');
        }elseif ($date && strtotime($date) < strtotime(date('Y-m-d'))) {
            $errors['date'] = 'Event date cannot be in the past.';
            $this->logEvent('Validation error: Event date cannot be in the past.','ERROR');
        }
    if (!is_numeric($numberofattendees) || $numberofattendees <= 0) {
            $errors['numberofattendees'] = 'Number of Attendees must be a positive number.';  
            $this->logEvent('Validation error: Number of Attendees must be a positive number.','ERROR');
        }
    if (empty($starttime)) {
        $errors['starttime'] = 'Start time is required.';
        $this->logEvent('Validation error: Start time is required.','ERROR');
    } elseif (!preg_match("/^([01]\d|2[0-3]):([0-5]\d)$/", $starttime)) {
        $errors['starttime'] = 'Invalid start time format. Use "HH:MM" in 24-hour format.';
        $this->logEvent('Validation error: Invalid start time format. Use "HH:MM" in 24-hour format.','ERROR');
    }
    if (empty($endtime)) {
        $errors['endtime'] = 'End time is required.';
        $this->logEvent('Validation error: End time is required.','ERROR');
    } elseif (!preg_match("/^([01]\d|2[0-3]):([0-5]\d)$/", $endtime)) {
        $errors['endtime'] = 'Invalid end time format. Use "HH:MM" in 24-hour format.';
        $this->logEvent('Validation error: Invalid end time format. Use "HH:MM" in 24-hour format.','ERROR');
    } else {
        $startDateTime = DateTime::createFromFormat('H:i', $starttime);
        $endDateTime = DateTime::createFromFormat('H:i', $endtime);
        if ($startDateTime >= $endDateTime) {
            $errors['endtime'] = 'End time must be after start time.';
            $this->logEvent('Validation error: End time must be after start time.','ERROR');
        }
    } 
    if (!$this->model->isNumberodAttendeesAvailable($eventId, $numberofattendees)) {
            $errors['duplicate'] = 'The number of attendees cannot be more than the maximum number of attendees';
            $this->logEvent('The number of attendees cannot be more than the maximum number of attendees','ERROR');
        }     
        
    if ($this->model->checkIfBookingExists($customerid, $eventId, $date)) {
            $errors['duplicate'] = 'Same Private Event cannot be reserve at the same date';
            $this->logEvent('Same Private Event cannot be reserve at the same date','ERROR');
        } else if ($this->model->checkIfBookingDuplicate($customerid, $eventId, $date,$location)) {
            $errors['duplicate'] = 'This event date has been booked, Please select another date or select another private event!';
            $this->logEvent('This event date has been booked, Please select another date or select another private event!','ERROR');
        } 

        if (!empty($errors)) {
            $eventData = [
                'event_id' => $eventId,
                'type' => $type,
                'title' => $title,
                'location' => $location,
                'deposit' => $deposit,
                'number_of_attendees' => $number_of_attendees                    
            ];
            $this->view->displayPrivateEventDetailsForm($eventData, $errors); // Re-display the form with errors            
            return;
        }
        try {
            $this->model->bookingPrivateEvent($customerid, $fullname, $eventId, $title, $date, $starttime, $endtime, $location, $type, $deposit, $numberofattendees);
            $this->displayPrivateBookingSummary($customerid, $eventId, $date);
        } catch (Exception $e) {
            $this->view->displayError($e->getMessage());
            $this->logEvent(displayError($e->getMessage()),'ERROR');
        }
    }

}

?>
