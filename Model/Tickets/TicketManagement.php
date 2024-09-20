<?php

require_once __DIR__ . '/../../Config/databaseConfig.php';
require_once __DIR__ . '/../XmlGenerator.php'; // Include XML Generator

class TicketManagement extends DatabaseConfig {

    private $pdo;

    public function __construct() {
        $dbConfig = new DatabaseConfig();
        $this->pdo = $dbConfig->getConnection();
    }

    private function getNextId() {
        $query = "SELECT MAX(id) AS max_id FROM tickets";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['max_id'] === null) {
            return 1; // Table is empty, start with ID 1
        } else {
            return $row['max_id'] + 1;
        }
    }

    public function getTickets() {
        $query = "SELECT * FROM tickets";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addTicket($type, $description, $price) {
        $id = $this->getNextId(); // Get the next available ID
        $query = "INSERT INTO tickets (id, type, description, price) VALUES (:id, :type, :description, :price)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->execute();
    }

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
                $stmt->bindParam(':price', $price, PDO::PARAM_STR); // or PDO::PARAM_INT if price is numeric
                $stmt->execute();

                return ['success' => true, 'message' => 'Ticket updated successfully.'];
            } else {
                return ['success' => false, 'message' => 'Ticket with ID ' . $id . ' does not exist.'];
            }
        } catch (PDOException $e) {
            // Catch any database exceptions and return the error
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function deleteTicket($id) {
        try {
            // Check if the ticket exists before attempting to delete
            $query = "SELECT COUNT(*) FROM tickets WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $exists = $stmt->fetchColumn();

            if ($exists) {
                // Attempt to delete the ticket
                $query = "DELETE FROM tickets WHERE id = :id";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                return ['success' => true, 'message' => 'Ticket deleted successfully.'];
            } else {
                return ['success' => false, 'message' => 'Ticket with ID ' . $id . ' does not exist.'];
            }
        } catch (PDOException $e) {
            // Handle any foreign key constraint violations or other errors
            if ($e->getCode() == 23000) {
                return ['success' => false, 'message' => 'Cannot delete the ticket because it has related purchases.'];
            } else {
                return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
            }
        }
        $response = $ticketModel->deleteTicket($id);

        if ($response['success']) {
            echo $response['message'];
        } else {
            echo 'Error: ' . $response['message'];
        }
    }

    public function getTicketById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tickets WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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

        // Generate XML
        $xmlGenerator = new XmlGenerator();
        $xmlGenerator->createXMLFileFromArray("tickets", "selectedTickets.xml", "Tickets", "Ticket", "id", $tickets);
    }
}

?>
