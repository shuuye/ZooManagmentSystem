<?php
require_once 'Config/databaseConfig.php'; 
require_once 'Model/XmlGenerator1.php';  

class private_eventBookingMethod extends databaseConfig {
    private $connection;
    private $logFile;

    public function __construct() {
        $this->connection = $this->getConnection();
        $this->logFile = 'C:\\xampp\\apache\\logs\\privateeventbookingmethod.log'; // Path to the log file
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
    public function fetchPrivateEventById($eventId) {
        if ($this->connection === null) {
        $this->logEvent("Database connection is not set.", 'ERROR');
        throw new Exception("Database connection is not set.");      
        }
        if (!is_numeric($eventId)) {
            $this->logEvent('Event ID must be a valid number.', 'ERROR');
            throw new InvalidArgumentException('Event ID must be a valid number.');
        }try{
        $query = "SELECT * FROM private_event WHERE event_id = :event_id AND type = 'Private'"; 
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($event === false) {
            $this->logEvent('Event not found.', 'ERROR');
            throw new Exception('Event not found.');
        } 
        return $event;
    }catch (Exception $e) {
        // Log error and rethrow
        $this->logEvent("Failed to fetch private event by id" . $e->getMessage(), 'ERROR');
        throw new Exception("Failed to fetch private event by id" . $e->getMessage());       
    }
    }
        

    
    
    public function fetchPrivateEvents($type = null) {
        if ($this->connection === null) {
        $this->logEvent("Database connection is not set.", 'ERROR');
        throw new Exception("Database connection is not set.");      
        }try{
        $query = 'SELECT e.id, e.type, e.title, e.location, e.description, 
                         p.deposit, p.number_of_attendees
                  FROM events e 
                  INNER JOIN private_event p ON e.id = p.event_id';

        if ($type === 'Private') {
            $query .= ' WHERE e.id IN (SELECT event_id FROM private_event)';
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch (Exception $e) {
        // Log error and rethrow
        $this->logEvent("Failed to fetch all private event :" . $e->getMessage(), 'ERROR');
        throw new Exception("Failed to all fetch private event :" . $e->getMessage());       
    }
    }
    
    public function fetchPrivateBookingById($bookingId,$customerid) {
    if ($this->connection === null) {
        // Log an error if the connection is not set
        $this->logEvent("Database connection is not set.", 'ERROR');
        throw new Exception("Database connection is not set.");
    }

    if (!is_numeric($bookingId)) {
        // Log an error if the booking ID is not numeric
        $this->logEvent("Booking ID must be a valid number.", 'ERROR');
        throw new InvalidArgumentException("Booking ID must be a valid number.");
    }

    try {
        // Prepare the query to fetch the booking details
        $query = "SELECT * FROM privateeventbooking WHERE bookingid = :bookingid AND customerid = :customerid";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':bookingid', $bookingId, PDO::PARAM_INT);
        $stmt->bindParam(':customerid', $customerid, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the result
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($booking === false) {
            // Log an error if the booking is not found
            $this->logEvent("Booking not found for bookingid: $bookingId", 'ERROR');
            throw new Exception("Booking not found for bookingid: $bookingId.");
        }

        return $booking;
    } catch (Exception $e) {
        // Log the error and rethrow it
        $this->logEvent("Failed to fetch booking by ID: " . $e->getMessage(), 'ERROR');
        throw new Exception("Failed to fetch booking by ID: " . $e->getMessage());
    }
}
    public function fetchPrivateBookingDetails($customerid, $eventId, $date) {
    if ($this->connection === null) {
        $this->logEvent("Database connection is not set." , 'ERROR');
        throw new Exception("Database connection is not set.");
    }

    try {
        // Fetch the booking details
        $query = "SELECT * FROM privateeventbooking 
                  WHERE event_id = :event_id AND customerid = :customerid AND date = :date";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->bindParam(':customerid', $customerid, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date);
        $stmt->execute();

        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return booking details
        return $booking;
    } catch (Exception $e) {
        $this->logEvent("Failed to fetch private event booking details: " . $e->getMessage() , 'ERROR');
        throw new Exception("Failed to fetch private event booking details: " . $e->getMessage());
    }
}

    public function fetchallprivateeventBookings($customerid) {
        if ($this->connection === null) {
        $this->logEvent("Database connection is not set.", 'ERROR');
        throw new Exception("Database connection is not set.");
    }try{
        $query = "SELECT * FROM privateeventbooking WHERE customerid = :customerid AND type = 'Private'";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':customerid', $customerid, PDO::PARAM_INT);
        $stmt->execute();
        $booking = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $booking;
    }catch (Exception $e) {
        $this->logEvent("Failed to fetch private event bookings " . $e->getMessage() , 'ERROR');
        throw new Exception("Failed to fetch private event bookings " . $e->getMessage());       
    }
    }
    
    public function isNumberodAttendeesAvailable($eventId, $numberofattendees) {
        if ($this->connection === null) {
        $this->logEvent("Database connection is not set.", 'ERROR');
        throw new Exception("Database connection is not set.");
    }try{
        $query = "SELECT number_of_attendees FROM private_event WHERE event_id = :event_id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->execute();
        $number_of_attendees = $stmt->fetchColumn();

        if ($number_of_attendees === false) {
            $this->logEvent("Event not found.", 'ERROR');
            throw new Exception("Event not found.");
        }

        return ($numberofattendees) <= $number_of_attendees;
    }catch (Exception $e) {
        $this->logEvent("Failed to check number of attendees " . $e->getMessage() , 'ERROR');
        throw new Exception("Failed to check number of attendees " . $e->getMessage());       
    }
    }
    
    
public function checkIfBookingDuplicate($customerid, $eventId, $date, $location) {
    if ($this->connection === null) {
        $this->logEvent("Database connection is not set.", 'ERROR');
        throw new Exception("Database connection is not set.");
    }

    try {
        // Query to check if the event is booked by any other customer on the same date and location
        $query = "
            SELECT 
                (SELECT COUNT(*) FROM privateeventbooking 
                 WHERE event_id = :event_id AND date = :date AND customerid != :customerid) AS privateCount,
                (SELECT COUNT(*) FROM public_event
                 WHERE date = :date AND location = :location) AS publicCount
        ";
        
        // Prepare and bind parameters
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':customerid', $customerid, PDO::PARAM_INT); // Exclude current customer
        $stmt->bindParam(':location', $location); // Bind location for public bookings
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if any booking exists in either table
        return $result['privateCount'] > 0 || $result['publicCount'] > 0;
    } catch (Exception $e) {
        // Log error and rethrow
        $this->logEvent("Failed to check if booking exists: " . $e->getMessage(), 'ERROR');
        throw new Exception("Failed to check if booking exists: " . $e->getMessage());
    }
}


    
    
    public function checkIfBookingExists($customerid, $eventId, $date) {
    if ($this->connection === null) {
        $this->logEvent("Database connection is not set.", 'ERROR');
        throw new Exception("Database connection is not set.");
    }

    try {
        // Check if the user has already booked the same private event on the same date
        $query = "SELECT COUNT(*) FROM privateeventbooking 
                  WHERE event_id = :event_id AND customerid = :customerid AND date = :date";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->bindParam(':customerid', $customerid, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date);
        $stmt->execute();

        $exists = $stmt->fetchColumn();
        // Return true if a booking exists, false otherwise
        return $exists > 0;
    } catch (Exception $e) {
        // Log error and rethrow
        $this->logEvent("Failed to check if booking exists: " . $e->getMessage());
        throw new Exception("Failed to check if booking exists: " . $e->getMessage());
    }
}


    public function bookingPrivateEvent($customerid, $fullname, $eventId, $title, $date, $starttime, $endtime, $location, $type, $deposit, $numberofattendees) {
    if ($this->connection === null) {
        $this->logEvent("Database connection is not set.", 'ERROR');
        throw new Exception("Database connection is not set.");
    }

    $this->connection->beginTransaction();

    try {      
        // Insert a new booking
        $query = "INSERT INTO privateeventbooking (customerid, fullname, event_id, title, date, starttime, endtime, location, type, deposit, numberofattendees) 
                  VALUES (:customerid, :fullname, :event_id, :title, :date, :starttime, :endtime, :location, :type, :deposit, :numberofattendees)";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':customerid', $customerid, PDO::PARAM_INT);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);           
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':starttime', $starttime);
        $stmt->bindParam(':endtime', $endtime);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':deposit', $deposit);
        $stmt->bindParam(':numberofattendees', $numberofattendees);

        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo();
            $this->logEvent("Failed to create booking: " . $errorInfo[2], 'ERROR');
            throw new Exception("Failed to create booking: " . $errorInfo[2]);
        }
        // Commit the transaction
        $this->connection->commit();

        $xmlGenerator = new XmlGenerator();
        $xmlGenerator->createXMLFileByTableName(
            'privateeventbooking', // Table name
            'privateeventbooking.xml', // Output XML file name
            'Bookings', // Root element name
            'Booking', // Element name for each row
            'booking_id' // Attribute for the first element
        );

    } catch (Exception $e) {
        // Roll back the transaction on failure
        $this->connection->rollBack();
        $this->logEvent("Failed to create booking: " . $e->getMessage(), 'ERROR');
        throw new Exception("Failed to create booking: " . $e->getMessage());
    }
}
}
?>
