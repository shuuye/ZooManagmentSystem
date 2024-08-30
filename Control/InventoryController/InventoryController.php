<?php

//used to route users to the pages

include_once '../../Model/Inventory/InventoryModel.php';
require_once '../../View/InventoryView/InventoryView.php';

class InventoryController extends InventoryModel {

    private $model;
    private $view;

    public function __construct($model, $view) {
        $this->model = $model;
        $this->view = $view;
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
            'xslt_transform'=> true
        ]; 

        $output = $this->view->renderXML($xmlFile, $xslFile, $data);
        echo $output;
    }

    // Method to handle the Add Inventory Item page
    public function addInventoryItem() {
        $data = [
            'activePage' => 'Add Inventory Item',
            'pageCss' => 'addInventoryItem.css',
            'xslt_transform'=> false
        ];

        $this->view->render('AddNewInventItem', $data);
    }

    // Routing logic based on a simple 'action' parameter
    public function route() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';

        switch ($action) {
            case 'addInventoryItem':
                $this->addInventoryItem();
                break;
            case 'index':
            default:
                $this->index();
                break;
        }
    }
}
