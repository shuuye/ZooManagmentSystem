<?php

require_once __DIR__ . '/CustomerTicketModel.php';
require_once __DIR__ . '/PaymentModel.php';
require_once __DIR__ . '/../XmlGenerator.php';

class TicketPaymentFacade {

    private $ticketModel;
    private $paymentModel;
    private $xmlGenerator;

    public function __construct() {
        $this->ticketModel = new CustomerTicketModel();
        $this->paymentModel = new PaymentModel();
        $this->xmlGenerator = new XmlGenerator();
    }

    // Process the ticket purchase
    public function purchaseTickets($userId, $visitDate, $quantities) {
        try {
            // Step 1: Process the ticket purchase
            $this->ticketModel->processPurchase($userId, $visitDate, $quantities);

            // Step 2: Generate the XML file for the purchase
            $ticketIds = array_keys($quantities);
            $tickets = $this->ticketModel->getTicketsByIds($ticketIds);

            // Prepare data for XML
            $data = [];
            foreach ($tickets as $ticket) {
                if (isset($quantities[$ticket['id']])) {
                    $quantity = $quantities[$ticket['id']];
                    if ($quantity > 0) {
                        $total = $quantity * $ticket['price'];
                        $data[] = [
                            'id' => $ticket['id'],
                            'type' => $ticket['type'],
                            'quantity' => $quantity,
                            'price' => $ticket['price'],
                            'total' => $total,
                            'visit_date' => $visitDate
                        ];
                    }
                }
            }

            // Generate XML
            $this->xmlGenerator->createXMLFileFromArray('ticket_purchases', 'ticket_purchases.xml', 'Tickets', 'Ticket', 'id', $data);
        } catch (Exception $e) {
            throw new Exception("Error in purchase process: " . $e->getMessage());
        }
    }
}

?>
