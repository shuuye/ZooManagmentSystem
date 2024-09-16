<?php

require_once __DIR__ . '/../../Config/databaseConfig.php';

class CustomerTicketModel extends databaseConfig {

    public function getTicketsByIds($ticketIds) {
        $db = $this->getConnection();
        $in = str_repeat('?,', count($ticketIds) - 1) . '?';
        $sql = "SELECT * FROM tickets WHERE id IN ($in)";
        $stmt = $db->prepare($sql);
        $stmt->execute($ticketIds);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailableTickets() {
        $db = $this->getConnection();
        $sql = "SELECT * FROM tickets";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function processPurchase($userId, $visitDate, $quantities) {
        $db = $this->getConnection();

        $db->beginTransaction();
        try {
            foreach ($quantities as $ticketId => $quantity) {
                if ($quantity > 0) {
                    $sql = "INSERT INTO ticket_purchases (ticket_id, userID, quantity, visit_date) VALUES (:ticket_id, :userID, :quantity, :visit_date)";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([
                        ':ticket_id' => $ticketId,
                        ':userID' => $userId,
                        ':quantity' => $quantity,
                        ':visit_date' => $visitDate
                    ]);
                }
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw new Exception("Error processing purchase: " . $e->getMessage());
        }
    }
    
    // Add this method for getting visitors by date
    public function getVisitorsByDate($visitDate) {
        $db = $this->getConnection();
        $sql = "SELECT * FROM ticket_purchases WHERE visit_date = :visit_date";
        $stmt = $db->prepare($sql);
        $stmt->execute([':visit_date' => $visitDate]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}

?>
