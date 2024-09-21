<?php
require_once 'Model/eventModel/public_eventBookingMethod.php'; // Ensure this path is correct
require_once 'View/eventView/eventBookingView.php'; // Ensure this path is correct

class public_eventController extends public_eventBookingMethod {
    private $view;
    private $model;
    private $logFile;

    public function __construct($view) {
        parent::__construct();
        $this->view = $view;
        $this->model = $this; // Create a new instance of the model
        $this->logFile = 'C:\\xampp\\apache\\logs\\publiceventcontroller.log'; // Path to the log file
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
            $events = $this->model->fetchPublicEvents($type);
            $this->view->displayPublicEventsForm($events);
        } catch (Exception $e) {           
            $this->view->displayError($e->getMessage());
            $this->logEvent($this->view->displayError($e->getMessage()), 'ERROR');           
        }
    }
    public function displayEventDetails($eventId) {
        try {
            $event = $this->model->fetchPublicEventById($eventId);
            if ($event) {
                $this->view->displayEventDetailsForm($event);
            } else {
                $this->view->displayError("Event not found.");
                $this->logEvent($this->view->displayError("Event not found."), 'ERROR');
            }
        } catch (Exception $e) {
            $this->view->displayError($e->getMessage());
            $this->logEvent( $this->view->displayError($e->getMessage()), 'ERROR'); 
        }
    }
    public function fetchPublicEventBookings($customerid) {
        try {
            $booking = $this->model->fetchallpubliceventBookings($customerid);
            if ($booking) {
                $this->view->displayAllPublicEventReservation($booking);
            } else {
                $this->view->displayError("Reservation List not found.");
                $this->logEvent($this->view->displayError("Reservation List not found."), 'ERROR'); 
            }
        } catch (Exception $e) {
            $this->view->displayError($e->getMessage());
            $this->logEvent( $this->view->displayError($e->getMessage()), 'ERROR'); 
        }
    }

    public function validateAndProcessTicketPurchase($customerid, $fullname,$eventId, $ticket_number, $type, $title, $location, $price, $date, $starttime, $endtime) {
        $errors = [];
         //Validate the inputs
        if (!$this->model->isCapacityAvailable($eventId, $ticket_number)) {
            $errors['duplicate'] = 'The requested number of tickets exceeds available capacity.';
            $this->logEvent('The requested number of tickets exceeds available capacity.', 'ERROR');
        }

        if (!empty($errors)) {
            $eventData = [
                'event_id' => $eventId,
                'type' => $type,
                'title' => $title,
                'location' => $location,
                'price' => $price,
                'date' => $date,
                'starttime' => $starttime,
                'endtime' => $endtime,
            ];
            $this->view->displayEventDetailsForm($eventData, $errors); // Re-display the form with errors            
            return;
        }       
         $this->view->displayPublicBookingSummary($customerid, $fullname, $eventId, $ticket_number, $type, $title, $location, $price, $date, $starttime, $endtime);       
        try {         
            $totalprice = $price * $ticket_number;           
            $this->model->bookingPublicEvent($customerid, $fullname, $eventId, $title, $price, $date, $starttime, $endtime, $location, $type, $ticket_number, $totalprice);        
    } catch (Exception $e) {
        $this->view->displayError($e->getMessage());
         $this->logEvent($this->view->displayError($e->getMessage()), 'ERROR');
    }     
    }
}
?>
