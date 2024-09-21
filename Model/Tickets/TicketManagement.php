<?php

require_once __DIR__ . '/../../Config/databaseConfig.php';
require_once __DIR__ . '/../XmlGenerator.php'; // Include XML Generator

class TicketManagement extends DatabaseConfig {

    private $pdo;

    // Constructor initializes the database connection
    public function __construct() {
        $this->setConnection(); // Sets the PDO connection
    }

    // Setter and getter for PDO connection
    public function setConnection() {
        $dbConfig = new DatabaseConfig();
        $this->pdo = $dbConfig->getConnection();
    }

    public function getConnection() {
        return $this->pdo;
    }

    // Get next available ticket ID
    private function getNextId() {
        $query = "SELECT MAX(id) AS max_id FROM tickets";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($row['max_id'] === null) ? 1 : $row['max_id'] + 1; // If no tickets, start with ID 1
    }

    // Get all tickets from the database
    public function getTickets() {
        $query = "SELECT * FROM tickets";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new ticket to the database
    public function addTicket($type, $description, $price) {
        $id = $this->getNextId();
        $query = "INSERT INTO tickets (id, type, description, price) VALUES (:id, :type, :description, :price)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->execute();
    }

    // Update an existing ticket in the database
    public function updateTicket($id, $type, $description, $price) {
        try {
            // Check if the ticket exists
            $query = "SELECT COUNT(*) FROM tickets WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $exists = $stmt->fetchColumn();

            if ($exists) {
                // Update the ticket if it exists
                $query = "UPDATE tickets 
                      SET type = :type, description = :description, price = :price 
                      WHERE id = :id";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':type', $type, PDO::PARAM_STR);
                $stmt->bindParam(':description', $description, PDO::PARAM_STR);
                $stmt->bindParam(':price', $price, PDO::PARAM_STR);
                $stmt->execute();

                return true; // Return true for successful update
            } else {
                return false; // Return false if ticket does not exist
            }
        } catch (PDOException $e) {
            // Handle database errors
            return false; // Return false on error
        }
    }

    // Delete a ticket from the database
    public function deleteTicket($id) {
        try {
            // Check if the ticket exists
            $query = "SELECT COUNT(*) FROM tickets WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $exists = $stmt->fetchColumn();

            if ($exists) {
                // Delete the ticket
                $query = "DELETE FROM tickets WHERE id = :id";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                return ['success' => true, 'message' => 'Ticket deleted successfully.'];
            } else {
                return ['success' => false, 'message' => 'Ticket with ID ' . $id . ' does not exist.'];
            }
        } catch (PDOException $e) {
            // Handle foreign key constraint violations or other errors
            if ($e->getCode() == 23000) {
                return ['success' => false, 'message' => 'Cannot delete the ticket because it has related purchases.'];
            } else {
                return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
            }
        }
    }

    // Get a ticket by its ID
    public function getTicketById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tickets WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Generate an XML file for selected tickets
    public function generateTicketXml($selectedTickets) {
        $tickets = [];
        foreach ($selectedTickets as $id => $quantity) {
            if ($quantity > 0) {
                $ticket = $this->getTicketById($id);
                if ($ticket) {
                    $ticket['quantity'] = $quantity;
                    $tickets[] = $ticket;
                }
            }
        }

        // Generate XML using XmlGenerator
        $xmlGenerator = new XmlGenerator();
        $xmlGenerator->createXMLFileFromArray("tickets", "selectedTickets.xml", "Tickets", "Ticket", "id", $tickets);
    }
}

?>
