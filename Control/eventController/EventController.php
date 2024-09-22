<?php
require_once 'Model/eventModel/EventFactory.php'; // Adjust based on actual location
require_once 'View/eventView/EventView.php'; // Adjust based on actual location

class EventController extends EventFactory {
    private $view;
    protected $model;
    private $logFile;

    public function __construct($view) {
        parent::__construct();
        $this->view = $view;
        $this->model = $this;
        $this->logFile = 'C:\\xampp\\apache\\logs\\event_controller.log'; // Path to the log file
        // Set up the database connection for the EventFactory      
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

   public function validateEvent($type, $title, $description, $location, $date = null, $starttime = null, $endtime = null, $price = null, $capacity = null, $deposit = null, $numberOfAttendees = null) {
       $errors = [];

    // Validate the inputs
    if (empty($title)) {
        $errors['title'] = 'Title is required.';
        $this->logEvent('Validation error: Title is required.', 'ERROR');
    }
    if (empty($description)) {
        $errors['description'] = 'Description is required.';
        $this->logEvent('Validation error: Description is required.', 'ERROR');
    }
    if (empty($location)) {
        $errors['location'] = 'Location is required.';
        $this->logEvent('Validation error: Location is required.', 'ERROR');
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $location)) {
        $errors['location'] = 'Location must contain only alphabetic characters and spaces.';
        $this->logEvent('Validation error: Location must contain only alphabetic characters and spaces.', 'ERROR');
    }

    // Validate based on event type
    if ($type === 'Public') {
        if (!is_numeric($price) || $price <= 0) {
            $errors['price'] = 'Price must be a valid number and cannot be 0.';
            $this->logEvent(' Validation error: Price must be a valid number and cannot be 0.','ERROR');
        }
        if (!is_numeric($capacity) || $capacity <= 0) {
            $errors['capacity'] = 'Capacity must be a positive number and cannot be 0.';
            $this->logEvent(' Validation error: Capacity must be a positive number and cannot be 0.','ERROR');
        }if (empty($date)) {
        $errors['date'] = 'Date is required.';
        }elseif ($date && strtotime($date) < strtotime(date('Y-m-d'))) {
            $errors['date'] = 'Event date cannot be in the past.';
            $this->logEvent('Validation error: Event date cannot be in the past.','ERROR');
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
        if ($this->model->isPublicEventExists($date, $location)) {
        $errors['duplicate'] = 'A public event with the same date and location already exists.';
        $this->logEvent('Validation error: A public event with the same date and location already exists.','ERROR');
    } 
    } elseif ($type === 'Private') {
        if (!is_numeric($deposit) || $deposit <= 0) {
            $errors['deposit'] = 'Deposit must be a positive number and cannot be 0.';
            $this->logEvent('Validation error: Deposit must be a positive number and cannot be 0.','ERROR');
        }
        if (!is_numeric($numberOfAttendees) || $numberOfAttendees <= 0) {
            $errors['numberOfAttendees'] = 'Number of Attendees must be a positive number.';
            $this->logEvent('Validation error: Number of Attendees must be a positive number.','ERROR');
        }
        if ($this->model->isPrivateEventExists($location)) {
        $errors['duplicate'] = 'A private event with the same location already exists.';
        $this->logEvent('Validation error: A private event with the same location already exists.','ERROR');
    }
    }


    // If there are validation errors, display the form with errors and previously entered values
    if (!empty($errors)) {
        $this->view->displayForm($errors, $type, $title, $price, $date, $starttime, $endtime, $location, $description, $capacity, $deposit, $numberOfAttendees);
        return;
    }
    try {
        $event = $this->model->createEvent($type, $title, $description, $location, $price, $capacity, $date, $starttime, $endtime, $deposit, $numberOfAttendees);   
        $this->view->displayEvent($event);
    } catch (Exception $e) {
        $this->view->displayError($e->getMessage());
        $this->logEvent($this->view->displayError($e->getMessage()),'ERROR');
    }
}

public function fetchAndDisplayEvents($type = null) {
        try {
            $events = $this->model->fetchEventsByType($type);
            $this->view->displayAllEvents($events, $type); // Pass events and selected type to view
        } catch (Exception $e) {
            $this->view->displayError($e->getMessage()); // Handle and display error
            $this->logEvent($this->view->displayError($e->getMessage()),'ERROR');
        }
    }

   public function deleteSelectedEvents($eventIds, $type ) {
    $errors = [];
    // Check if bookings exist based on the event type
    try {
        if ($type === 'Public') {
            if ($this->model->getPublicBookingsCountByEventId($eventIds)) {
                $errors['duplicate'] = 'There are existing bookings for the public event(s). They cannot be deleted!';
                $this->logEvent('There are existing bookings for the public event(s). They cannot be deleted!','ERROR');
            }
        } elseif ($type === 'Private') {
            if ($this->model->getPrivateBookingsCountByEventId($eventIds)) {
                $errors['duplicate'] = 'There are existing bookings for the private event(s). They cannot be deleted!';
                $this->logEvent('There are existing bookings for the private event(s). They cannot be deleted!','ERROR');
            }         
        } else {
             $errors['duplicate'] = 'Invalid event type provided.';
             $this->logEvent('Invalid event type provided.','ERROR');
        }

        // If there are errors, display them and stop further processing
        if (!empty($errors)) {
            $events = $this->model->fetchEventsByType($type); // Retrieve events to display
            $this->view->displayAllEvents($events, $type,$errors); // Display events with errors
            return;
        }

        // If no bookings exist, proceed to delete the events
        $this->model->deleteEvents($eventIds);
        $successMessage = 'The event selected has been deleted successfully!';
        $this->view->displayDeleteSuccessfulMessage($successMessage);
        $this->fetchAndDisplayEvents($type); // Refresh the event list based on the type
    } catch (Exception $e) {
        $this->view->displayError($e->getMessage()); // Handle and display error
        $this->logEvent($this->view->displayError($e->getMessage()),'ERROR');
    }
}

}
?>