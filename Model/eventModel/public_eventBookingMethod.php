<?php
require_once 'Config/databaseConfig.php'; 
require_once 'Model/XmlGenerator1.php'; 

class public_eventBookingMethod extends databaseConfig {
    private $connection;
    private $logFile;

    public function __construct() {
        $this->connection = $this->getConnection();
        $this->logFile = 'C:\\xampp\\apache\\logs\\publiceventbookingmethod.log'; // Path to the log file
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

    public function fetchallpubliceventBookings($customerid) {
        if ($this->connection === null) {
        $this->logEvent("Database connection is not set.", 'ERROR');
        throw new Exception("Database connection is not set.");      
    }try{
        $query = "SELECT * FROM publiceventbooking WHERE customerid = :customerid";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':customerid', $customerid, PDO::PARAM_INT);
        $stmt->execute();
        $booking = $stmt->fetchAll(PDO::FETCH_ASSOC);   
        return $booking;
    }catch (Exception $e) {
        // Log error and rethrow
        $this->logEvent("Failed to fetch public event bookings " . $e->getMessage(), 'ERROR');
        throw new Exception("Failed to fetch public event bookings " . $e->getMessage());       
    }
    }
    public function fetchPublicEventById($eventId) {
        if ($this->connection === null) {
        $this->logEvent("Database connection is not set.", 'ERROR');
        throw new Exception("Database connection is not set."); 
        }
        if (!is_numeric($eventId)) {
            $this->logEvent('Event ID must be a valid number.', 'ERROR');
            throw new InvalidArgumentException('Event ID must be a valid number.');
            
        }try{
        $query = "SELECT * FROM public_event WHERE event_id = :event_id AND type = 'Public'"; 
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
        $this->logEvent("Failed to fetch public event by id " . $e->getMessage(), 'ERROR');
        throw new Exception("Failed to fetch public event by id " . $e->getMessage());       
    }
    }
             
    public function fetchPublicEvents($type = null) {
        if ($this->connection === null) {
        $this->logEvent("Database connection is not set.", 'ERROR');
        throw new Exception("Database connection is not set."); 
        }try{
        $query = 'SELECT e.id, e.type, e.title, e.location, e.description, 
                         p.date, p.starttime, p.endtime, p.price, p.capacity
                  FROM events e 
                  INNER JOIN public_event p ON e.id = p.event_id';

        if ($type === 'Public') {
            $query .= ' WHERE e.type = :type'; // Adjusted to filter by type
        }

        $stmt = $this->connection->prepare($query);
        if ($type === 'Public') {
            $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch (Exception $e) {
        // Log error and rethrow
        $this->logEvent("Failed to fetch all public event  " . $e->getMessage(), 'ERROR');
        throw new Exception("Failed to fetch all public event " . $e->getMessage());       
    }
    }
    
    // Modify fetchPublicBookingDetails to fetch based on last inserted booking_id
    public function fetchPublicBookingDetails($bookingId) {
    if ($this->connection === null) {
        $this->logEvent("Database connection is not set.", 'ERROR');
        throw new Exception("Database connection is not set.");
    }

    try {
        // Fetch the booking details based on the booking_id
        $query = "SELECT * FROM publiceventbooking WHERE bookingid = :bookingid";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':bookingid', $bookingId, PDO::PARAM_INT);
        $stmt->execute();

        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return booking details
        return $booking;

    } catch (Exception $e) {
        $this->logEvent("Failed to fetch booking details: " . $e->getMessage(), 'ERROR');
        throw new Exception("Failed to fetch booking details: " . $e->getMessage());
    }
}


    public function isCapacityAvailable($eventId, $numberofTickets) {
        if ($this->connection === null) {
        $this->logEvent("Database connection is not set.", 'ERROR');
        throw new Exception("Database connection is not set.");
    }

    try {
        $query = "SELECT IFNULL(SUM(ticket_number), 0) FROM publiceventbooking WHERE event_id = :event_id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->execute();
        $currentBookedTickets = $stmt->fetchColumn();

        $query = "SELECT capacity FROM public_event WHERE event_id = :event_id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->execute();
        $capacity = $stmt->fetchColumn();

        if ($capacity === false) {
            $this->logEvent("Event not found." , 'ERROR');
            throw new Exception("Event not found.");
        }

        return ($currentBookedTickets + $numberofTickets) <= $capacity;
    }catch (Exception $e) {
        $this->logEvent("Failed to check capacity: " . $e->getMessage(), 'ERROR');
        throw new Exception("Failed to check capacity: " . $e->getMessage());
    }
}

    // Modify bookingPublicEvent to return the last inserted booking_id
    public function bookingPublicEvent($customerid, $fullname, $eventId, $title, $price, $date, $starttime, $endtime, $location, $type, $ticket_number, $totalprice) {
    if ($this->connection === null) {
        $this->logEvent("Database connection is not set.", 'ERROR');
        throw new Exception("Database connection is not set.");
    }

    $this->connection->beginTransaction();

    try {
        // Insert a new record
        $query = "INSERT INTO publiceventbooking (customerid, fullname, event_id, title, price, date, starttime, endtime, location, type, ticket_number, totalprice) 
                  VALUES (:customerid, :fullname, :event_id, :title, :price, :date, :starttime, :endtime, :location, :type, :ticket_number, :totalprice)";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':customerid', $customerid, PDO::PARAM_INT);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':starttime', $starttime);
        $stmt->bindParam(':endtime', $endtime);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':ticket_number', $ticket_number, PDO::PARAM_INT);
        $stmt->bindParam(':totalprice', $totalprice);

        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo();
            $this->logEvent("Failed to create booking: " . $errorInfo[2], 'ERROR');
            throw new Exception("Failed to create booking: " . $errorInfo[2]);
        }

        // Get the last inserted booking_id
        $lastBookingId = $this->connection->lastInsertId();
    
        $this->connection->commit();

        // Optionally generate XML
        $xmlGenerator = new XmlGenerator();
        $xmlGenerator->createXMLFileByTableName(
            'publiceventbooking',
            'publiceventbooking.xml',
            'Bookings',
            'Booking',
            'event_id'
        );
        return $lastBookingId;
    } catch (Exception $e) {
        $this->connection->rollBack();
        $this->logEvent("Failed to create booking: " . $e->getMessage(), 'ERROR');
        throw new Exception("Failed to create booking: " . $e->getMessage());
    }
}
}
?>
