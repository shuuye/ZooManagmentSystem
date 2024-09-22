<?php
require_once 'Config/databaseConfig.php';
require_once 'PublicEvent.php';
require_once 'PrivateEvent.php';
require_once 'EventFactoryInterface.php';

class EventFactory extends databaseConfig implements EventFactoryInterface {
    private $connection;
    private $logFile;
    public function __construct() {
        $this->connection = $this->getConnection();
        $this->logFile = 'C:\\xampp\\apache\\logs\\event_factory.log'; // Path to the log file
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
     
  

    public function isPublicEventExists($date, $location) {
        if ($this->connection === null) {
            $this->logEvent("Database connection is not set.", 'ERROR');
            throw new Exception("Database connection is not set.");
        }try{
        $Location = strtoupper($location);     
        $query = "SELECT COUNT(*) FROM public_event                
                  WHERE date = :date AND location = :location";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':location', $Location);
        $stmt->execute();
        
        $count = $stmt->fetchColumn();
        return $count > 0;
    }catch (Exception $e) {
            $this->connection->rollBack();
            $this->logEvent("Failed to check public event exists: " . $e->getMessage(), 'ERROR');
            throw new Exception("Failed to check public event exists: " . $e->getMessage());
        }
    }
    
    public function isPrivateEventExists($location) {
        if ($this->connection === null) {
            $this->logEvent("Database connection is not set.", 'ERROR');
            throw new Exception("Database connection is not set.");
        }try{
        $Location = strtoupper($location);       
        $query = "SELECT COUNT(*) FROM private_event 
                  WHERE location = :location";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':location', $Location);
        $stmt->execute();
        
        $count = $stmt->fetchColumn();
        return $count > 0;
    }catch (Exception $e) {
            $this->connection->rollBack();
            $this->logEvent("Failed to check private event exists: " . $e->getMessage(),'ERROR');
            throw new Exception("Failed to check private event exists: " . $e->getMessage());
        }
    }
    
public function createEvent($type, $title, $description, $location, $price = null, $capacity = null, $date = null, $starttime = null, $endtime = null, $deposit = null, $numberOfAttendees = null) {
    // Step 1: Insert into events table
    $stmt = $this->connection->prepare("INSERT INTO events (title, description, location, type) VALUES (?, ?, ?, ?)");
    if (!$stmt->execute([$title, $description, strtoupper($location), $type])) {
        $errorInfo = $stmt->errorInfo();
        $this->logEvent("Failed to insert event into events table: " . $errorInfo[2], 'ERROR');
        throw new Exception("Failed to insert event into events table: " . $errorInfo[2]);
    }

    // Step 2: Get the last inserted ID
    $eventId = $this->connection->lastInsertId();

    // Step 3: Create the appropriate event instance
    if ($type === 'Public') {
        $publicEvent = new PublicEvent($eventId, $title, $description, $location, $price, $date, $starttime, $endtime, $capacity); 
        // Step 4: Insert into the public_event table
        $this->insertPublicEvent($publicEvent);
        return $publicEvent; // Return the created event instance

    } elseif ($type === 'Private') {
        $privateEvent = new PrivateEvent($eventId, $title, $location, $description, $deposit, $numberOfAttendees, $capacity);
        // Step 4: Insert into the private_event table
        $this->insertPrivateEvent($privateEvent);
        return $privateEvent; // Return the created event instance

    } else {
        throw new Exception("Invalid event type");
    }
}

// Method to insert a PublicEvent
public function insertPublicEvent(PublicEvent $event) {
    $stmt = $this->connection->prepare("
        INSERT INTO public_event (event_id, title, description, location, price, date, starttime, endtime, capacity, type) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
  
    // Use the correct getters to fetch values
    if (!$stmt->execute([
        $event->getId(), 
        $event->getTitle(), 
        $event->getDescription(), 
        $event->getLocation(), 
        $event->getPrice(), 
        $event->getDate(), 
        $event->getStartTime(), 
        $event->getEndTime(), 
        $event->getCapacity(),
        'Public' // Explicitly set the type as 'Public'
    ])) {
        $errorInfo = $stmt->errorInfo();
        $this->logEvent("Failed to insert public event data: " . $errorInfo[2], 'ERROR');
        throw new Exception("Failed to insert public event data: " . $errorInfo[2]);
    }
}

// Method to insert a PrivateEvent
public function insertPrivateEvent(PrivateEvent $event) {
    $stmt = $this->connection->prepare("
        INSERT INTO private_event (event_id, title, description, location, deposit, number_of_attendees, type) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    if (!$stmt->execute([
        $event->getId(), 
        $event->getTitle(), 
        $event->getDescription(), 
        $event->getLocation(), 
        $event->getDeposit(), 
        $event->getNumberOfAttendees(),
        'Private' // Explicitly set the type as 'Private'
    ])) {
        $errorInfo = $stmt->errorInfo();
        $this->logEvent("Failed to insert private event data: " . $errorInfo[2], 'ERROR');
        throw new Exception("Failed to insert private event data: " . $errorInfo[2]);
    }
}




    

    public function fetchEventsByType($type = null) {
        if ($this->connection === null) {
            $this->logEvent("Database connection is not set.", 'ERROR');
            throw new Exception("Database connection is not set.");
        }try{
        $query = 'SELECT e.id, e.type, e.title, e.location, e.description, 
                         p.date, p.starttime, p.endtime, p.price, p.capacity, 
                         pr.deposit, pr.number_of_attendees
                  FROM events e 
                  LEFT JOIN public_event p ON e.id = p.event_id
                  LEFT JOIN private_event pr ON e.id = pr.event_id';

        if ($type === 'Public') {
            $query .= ' WHERE e.id IN (SELECT event_id FROM public_event)';
        } elseif ($type === 'Private') {
            $query .= ' WHERE e.id IN (SELECT event_id FROM private_event)';
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {
            $this->connection->rollBack();
            $this->logEvent("Failed to fetch event by its type: " . $e->getMessage(),'ERROR');
            throw new Exception("Failed to fetch event by its type:" . $e->getMessage());
        }
    }

    public function deleteEvents($eventIds) {
        if ($this->connection === null) {
            $this->logEvent("Database connection is not set.", 'ERROR');
            throw new Exception("Database connection is not set.");
        }
        try {
            // Begin transaction
            $this->connection->beginTransaction();

            // Delete from public_event
            $placeholders = implode(',', array_fill(0, count($eventIds), '?'));
            $query = "DELETE FROM public_event WHERE event_id IN ($placeholders)";
            $stmt = $this->connection->prepare($query);
            $stmt->execute($eventIds);

            // Delete from private_event
            $query = "DELETE FROM private_event WHERE event_id IN ($placeholders)";
            $stmt = $this->connection->prepare($query);
            $stmt->execute($eventIds);

            // Delete from events
            $query = "DELETE FROM events WHERE id IN ($placeholders)";
            $stmt = $this->connection->prepare($query);
            $stmt->execute($eventIds);
            // Commit transaction
            $this->connection->commit();
        } catch (Exception $e) {
            // Rollback transaction if something goes wrong       
            $this->connection->rollBack();
            $this->logEvent("Failed to delete events :" . $e->getMessage(),'ERROR');
            throw new Exception("Failed to delete events :" . $e->getMessage());
              
        }
    }
    
    
  public function getPublicBookingsCountByEventId($eventIds) {
      if ($this->connection === null) {
            $this->logEvent("Database connection is not set.", 'ERROR');
            throw new Exception("Database connection is not set.");
        }try{
    $placeholders = implode(',', array_fill(0, count($eventIds), '?'));
    $query = "SELECT COUNT(*) as booking_count FROM publiceventbooking WHERE event_id IN ($placeholders)";
    $stmt = $this->connection->prepare($query);
    $stmt->execute($eventIds);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['booking_count'] > 0;
} catch (Exception $e) {
            // Rollback transaction if something goes wrong       
            $this->connection->rollBack();
            $this->logEvent("Failed to get public event bookings count by event id :" . $e->getMessage(),'ERROR');
            throw new Exception("Failed to get public event bookings count by event id :" . $e->getMessage());
              
        }
    }

 public function getPrivateBookingsCountByEventId($eventIds) {
     if ($this->connection === null) {
            $this->logEvent("Database connection is not set.", 'ERROR');
            throw new Exception("Database connection is not set.");
        }try{
    $placeholders = implode(',', array_fill(0, count($eventIds), '?'));
    $query = "SELECT COUNT(*) as booking_count FROM privateeventbooking WHERE event_id IN ($placeholders)";
    $stmt = $this->connection->prepare($query);
    $stmt->execute($eventIds);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['booking_count'] > 0;
}catch (Exception $e) {
            // Rollback transaction if something goes wrong       
            $this->connection->rollBack();
            $this->logEvent("Failed to get private event bookings count by event id :" . $e->getMessage(),'ERROR');
            throw new Exception("Failed to get private event bookings count by event id :" . $e->getMessage());
              
        }
    }
}
?>
