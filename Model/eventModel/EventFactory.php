<?php
require_once 'Config/databaseConfig.php';
require_once 'PublicEvent.php';
require_once 'PrivateEvent.php';
require_once 'EventFactoryInterface.php';
require_once 'createXMLFromDatabase.php';

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
    
    

    public function createEvent($type, $title, $description, $location, $date = null, $starttime = null, $endtime = null, $price = null, $capacity = null, $deposit = null, $numberOfAttendees = null) {
        if ($this->connection === null) {
            $this->logEvent("Database connection is not set.", 'ERROR');
            throw new Exception("Database connection is not set.");
        }
        
        $this->connection->beginTransaction();

        try {
            $Location = strtoupper($location);

            // Insert into events table
            $query = "INSERT INTO events (type, title, location, description) VALUES (:type, :title, :location, :description)";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':location', $Location);
            $stmt->bindParam(':description', $description);

            if (!$stmt->execute()) {
                $errorInfo = $stmt->errorInfo();
                $this->logEvent("Failed to insert event into events table: " . $errorInfo[2], 'ERROR');
                throw new Exception("Failed to insert event into events table: " . $errorInfo[2]);
            }

            $eventId = $this->connection->lastInsertId();        

            if ($type === "Public") {
                // Insert into public_event table
                $query = "INSERT INTO public_event (type, title, location, description, event_id, date, starttime, endtime, price, capacity) VALUES (:type, :title, :location, :description,:event_id, :date, :starttime, :endtime, :price, :capacity)";
                $stmt = $this->connection->prepare($query);
                $stmt->bindParam(':type', $type);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':location', $Location);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':event_id', $eventId);
                $stmt->bindParam(':date', $date);
                $stmt->bindParam(':starttime', $starttime);
                $stmt->bindParam(':endtime', $endtime);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':capacity', $capacity);

                if (!$stmt->execute()) {
                    $errorInfo = $stmt->errorInfo();
                    $this->logEvent("Failed to insert public event data: " . $errorInfo[2] ,'ERROR');
                    throw new Exception("Failed to insert public event data: " . $errorInfo[2]);
                }

                $event = new PublicEvent($type, $title, $Location, $description, $eventId, $title, $price, $date, $starttime, $endtime, $location, $description, $capacity);
            } elseif ($type === "Private") {
                // Insert into private_event table
                $query = "INSERT INTO private_event (type, title, location, description, event_id, deposit, number_of_attendees) VALUES (:type, :title, :location, :description,:event_id, :deposit, :number_of_attendees)";
                $stmt = $this->connection->prepare($query);
                $stmt->bindParam(':type', $type);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':location', $Location);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':event_id', $eventId);
                $stmt->bindParam(':deposit', $deposit);
                $stmt->bindParam(':number_of_attendees', $numberOfAttendees);

                if (!$stmt->execute()) {
                    $errorInfo = $stmt->errorInfo();
                    $this->logEvent("Failed to insert private event data: " . $errorInfo[2] ,'ERROR');
                    throw new Exception("Failed to insert private event data: " . $errorInfo[2]);
                }

                $event = new PrivateEvent( $type, $title, $Location, $description, $eventId, $title, $location, $description, $deposit, $numberOfAttendees);
            } else {
                $this->logEvent("Invalid event type". $errorInfo[2] ,'ERROR');
                throw new Exception("Invalid event type");
            }

            $this->connection->commit();

            // Generate XML file after creating the event
            $xmlGenerator = new createXMLFromDatabase();
            $xmlGenerator->createXMLFileByTableName(); // Generates the XML file

            return $event;
        } catch (Exception $e) {
            $this->connection->rollBack();
            $this->logEvent("Failed to create event: " . $e->getMessage() ,'ERROR');
            throw new Exception("Failed to create event: " . $e->getMessage());
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

            // Generate XML file after deletion
            $xmlGenerator = new createXMLFromDatabase();
            $xmlGenerator->createXMLFileByTableName(); // Generates the XML file
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
