<?php

//used to route users to the pages

include_once '../../Model/Inventory/InventoryModel.php';
require_once '../../View/InventoryView/InventoryView.php';

class InventoryController extends InventoryModel {

    private $model;
    private $view;

    public function __construct(InventoryModel $model, $view) {
        $this->model = $model;
        $this->view = $view;
        $this->model->updateXML();
    }

    // Method to handle the default page (Inventory Catalog)
//    public function index() {
//        $pageNum = isset($_GET['page']) ? (int) $_GET['page'] : 1;
//        $recordsPerPage = 10;
//        $offset = ($pageNum - 1) * $recordsPerPage;
//
//        $inventory = $this->model->getInventory($offset, $recordsPerPage);
//        $totalRecords = $this->model->getTotalRecords();
//        $totalPages = ceil($totalRecords / $recordsPerPage);
//
//        $data = [
//            'inventory' => $inventory,
//            'pageNum' => $pageNum,  
//            'totalPages' => $totalPages,
//            'activePage' => 'Inventory Management',
//            'pageCss' => 'mainInventoryTracking.css'
//        ];
//
//        $this->view->render('InventoryCatalog', $data);
//    }
    public function index() {
        $xmlFile = '../../Xml/inventory.xml';
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\InventoryCatalog.xsl';
        $data = [
            'activePage' => 'Inventory Management',
            'pageCss' => 'mainInventoryTracking.css',
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFile, $xslFile, $data);
        echo $output;
    }

    // Method to handle the Add Inventory Item page
    public function addInventoryItem() {
        $data = [
            'activePage' => 'Add Inventory Item',
            'pageCss' => 'addInventoryItem.css',
            'xslt_transform' => false
        ];

        $this->view->render('AddNewInventItem', $data);
    }

    public function viewHabitatItem() {
        $xmlFile = '../../Xml/inventory.xml';
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\HabitatInventoryCatalog.xsl';
        $data = [
            'activePage' => 'Inventory Management',
            'pageCss' => 'mainInventoryTracking.css',
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFile, $xslFile, $data);
        echo $output;
    }

    public function viewFoodItem() {
        $xmlFile = '../../Xml/inventory.xml';
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\FoodInventoryCatalog.xsl';
        $data = [
            'activePage' => 'Inventory Management',
            'pageCss' => 'mainInventoryTracking.css',
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFile, $xslFile, $data);
        echo $output;
    }
    
    public function viewCleaningItem() {
        $xmlFile = '../../Xml/inventory.xml';
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\CleaningInventoryCatalog.xsl';
        $data = [
            'activePage' => 'Inventory Management',
            'pageCss' => 'mainInventoryTracking.css',
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFile, $xslFile, $data);
        echo $output;
    }

    // Routing logic based on a simple 'action' parameter
    public function route() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';

        switch ($action) {
            case 'addInventoryItem':
                $this->addInventoryItem();
                break;
            case 'habitatItem':
                $this->viewHabitatItem();
                break;
            case 'foodItem':
                $this->viewFoodItem();
                break;
            case 'cleaningItem':
                $this->viewCleaningItem();
                break;
            case 'index':
            default:
                $this->index();
                break;
        }
    }
}
