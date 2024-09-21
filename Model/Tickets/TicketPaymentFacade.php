<?php

require_once __DIR__ . '/CustomerTicketModel.php';
require_once __DIR__ . '/PaymentModel.php';
require_once __DIR__ . '/../XmlGenerator.php';

class TicketPaymentFacade {

    private $ticketModel;
    private $paymentModel;
    private $xmlGenerator;

    // Constructor to initialize the models
    public function __construct($ticketModel = null, $paymentModel = null, $xmlGenerator = null) {
        $this->ticketModel = $ticketModel ?? new CustomerTicketModel();
        $this->paymentModel = $paymentModel ?? new PaymentModel();
        $this->xmlGenerator = $xmlGenerator ?? new XmlGenerator();
    }

    public function displayAvailableTickets() {
        // Fetch and display available tickets using the TicketModel
        return $this->ticketModel->getAvailableTickets();
    }

    public function handleTicketSelection($selectedTickets, $visitDate) {
        // Store selected tickets and visit date in session
        $_SESSION['selected_tickets'] = $selectedTickets;
        $_SESSION['visit_date'] = $visitDate;
    }

    public function processPayment($paymentDetails) {
        // Pass ticket and payment details to the PaymentSystem
        return $this->paymentModel->processPayment($_SESSION['selected_tickets'], $paymentDetails);
    }

    // Getters and Setters for ticketModel
    public function getTicketModel() {
        return $this->ticketModel;
    }

    public function setTicketModel($ticketModel) {
        $this->ticketModel = $ticketModel;
    }

    // Getters and Setters for paymentModel
    public function getPaymentModel() {
        return $this->paymentModel;
    }

    public function setPaymentModel($paymentModel) {
        $this->paymentModel = $paymentModel;
    }

    // Getters and Setters for xmlGenerator
    public function getXmlGenerator() {
        return $this->xmlGenerator;
    }

    public function setXmlGenerator($xmlGenerator) {
        $this->xmlGenerator = $xmlGenerator;
    }

    // Process the ticket purchase
    public function purchaseTickets($userId, $visitDate, $quantities) {
        try {
            // Process the ticket purchase
            $this->ticketModel->processPurchase($userId, $visitDate, $quantities);

            // Generate the XML file for the purchase
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
