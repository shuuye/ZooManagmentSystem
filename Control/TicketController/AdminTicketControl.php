<?php

require_once __DIR__ . '/../../Model/Tickets/TicketManagement.php';
require_once __DIR__ . '/../../View/TicketView/AdminTicketView.php';
require_once __DIR__ . '/../../Model/XmlGenerator.php';  // Include the XmlGenerator class

class AdminTicketControl {

    private $model;
    private $errorMessage = '';

    public function __construct() {
        $this->model = new TicketManagement();
    }

    public function handleRequest() {
        if (isset($_POST['logout'])) {
            $this->logout();
            return; // Exit to prevent further processing
        }

        $action = isset($_POST['action']) ? $_POST['action'] : '';

        switch ($action) {
            case 'Add':
                $this->handleAdd();
                break;
            case 'Edit':
                $this->handleEdit();
                break;
            case 'Delete':
                $this->handleDelete();
                break;
            default:
                $this->showForm();
                break;
        }
    }

    private function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit();
    }

    private function handleAdd() {
        if (isset($_POST['type'], $_POST['description'], $_POST['price'])) {
            $type = $_POST['type'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $this->model->addTicket($type, $description, $price);
            $this->generateTicketsXml();  // Regenerate XML file after adding a ticket
        }
        $this->showForm();
    }

    private function handleEdit() {
        if (isset($_POST['id'], $_POST['type'], $_POST['description'], $_POST['price'])) {
            $id = $_POST['id'];
            $type = $_POST['type'];
            $description = $_POST['description'];
            $price = $_POST['price'];

            // Call updateTicket and check for success
            $success = $this->model->updateTicket($id, $type, $description, $price);

            if (!$success) {
                $this->errorMessage = "Error: Ticket with ID $id does not exist.";
            } else {
                $this->generateTicketsXml();  // Regenerate XML file after editing a ticket
            }
        } else {
            $this->errorMessage = "Error: Please fill in all required fields.";
        }
        $this->showForm();
    }

    private function handleDelete() {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        
        // Call deleteTicket and check for success
        $result = $this->model->deleteTicket($id);

        // Check if deletion was successful
        if (!$result['success']) {
            $this->errorMessage = $result['message']; // Set error message from deleteTicket
        } else {
            $this->generateTicketsXml();  // Regenerate XML file after successful deletion
        }
    } else {
        $this->errorMessage = "Error: Ticket ID is required.";
    }

    $this->showForm();
}


    private function showForm() {
        $tickets = $this->model->getTickets();
        AdminTicketView::render($tickets, $this->errorMessage);
    }

    // New method to generate the XML file
    private function generateTicketsXml() {
        $xmlGenerator = new XmlGenerator();
        $xmlGenerator->createXMLFileByTableName("tickets", "tickets.xml", "Tickets", "Ticket", "ticket_id");
    }
}

?>
