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
            case 'viewItembasedOnInventoryID':
                $inventoryId = isset($_GET['inventoryId']) ? $_GET['inventoryId'] : null;
                $itemType = isset($_GET['itemType']) ? $_GET['itemType'] : null;
                $this->viewItembasedOnInventoryID($inventoryId, $itemType);
                break;
            case 'viewSpecificDetails':
                $inventoryId = isset($_GET['inventoryId']) ? $_GET['inventoryId'] : null;
                $itemType = isset($_GET['itemType']) ? $_GET['itemType'] : null;
                $itemID = isset($_GET['itemID']) ? $_GET['itemID'] : null;
                $this->viewSpecific($inventoryId, $itemType, $itemID);
                break;
            case 'createPO':
                $inventoryId = isset($_GET['inventoryId']) ? $_GET['inventoryId'] : null;
                $itemType = isset($_GET['itemType']) ? $_GET['itemType'] : null;
                $itemID = isset($_GET['itemID']) ? $_GET['itemID'] : null;
                $this->createPO($inventoryId, $itemType, $itemID);
                break;
            case 'index':
            default:
                $this->index();
                break;
        }
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
        $xmlFile = '../../Model/Xml/inventory.xml';
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
        $xmlFile = '../../Model/Xml/inventory.xml';
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
        $xmlFile = '../../Model/Xml/inventory.xml';
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
        $xmlFile = '../../Model/Xml/inventory.xml';
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\CleaningInventoryCatalog.xsl';
        $data = [
            'activePage' => 'Inventory Management',
            'pageCss' => 'mainInventoryTracking.css',
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFile, $xslFile, $data);
        echo $output;
    }

    public function viewSpecific($inventoryId, $itemType, $itemID) {
        $xmlFiles = [
            '../../Model/Xml/cleaninginventory.xml',
            '../../Model/Xml/foodinventory.xml',
            '../../Model/Xml/habitatinventory.xml',
            '../../Model/Xml/purchaseorder.xml',
            '../../Model/Xml/purchaseorderlineitem.xml',
            '../../Model/Xml/inventory.xml',
            '../../Model/Xml/itemImage.xml'
        ];

        switch ($itemType) {
            case 'Food':
                $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\FoodInventoryItemDetails.xsl';
                break;
            case 'Habitat':
                $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\HabitatInventoryItemDetails.xsl';
                break;
            case 'Cleaning':
                $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\CleaningInventoryItemDetails.xsl';
                break;
            // add more cases for other item types
            default:
                throw new Exception("Unknown itemType: $itemType");
        }

        $data = [
            'activePage' => 'Inventory Management',
            'pageCss' => 'InventoryItemDetails.css',
            'xslt_transform' => true,
            'inventoryID' => $inventoryId,
            'itemID' => $itemID,
            'imageDirectory' => '../../assests/InventoryImages/'
        ];

        $output = $this->view->renderXML($xmlFiles, $xslFile, $data);
        echo $output;
    }

    public function viewItembasedOnInventoryID($inventoryId, $itemType) {
        $xmlFiles = [
            '../../Model/Xml/cleaninginventory.xml',
            '../../Model/Xml/foodinventory.xml',
            '../../Model/Xml/habitatinventory.xml',
            '../../Model/Xml/purchaseorder.xml',
            '../../Model/Xml/purchaseorderlineitem.xml',
            '../../Model/Xml/inventory.xml'
        ];

        switch ($itemType) {
            case 'Food':
                $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\InventoryIDFooditem.xsl';
                break;
            case 'Habitat':
                $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\InventoryIDHabitatItem.xsl';
                break;
            case 'Cleaning':
                $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\InventoryIDCleaningItem.xsl';
                break;
            // add more cases for other item types
            default:
                throw new Exception("Unknown itemType: $itemType");
        }

        $data = [
            'activePage' => 'Inventory Management',
            'pageCss' => 'mainInventoryTracking.css',
            'inventoryID' => $inventoryId,
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFiles, $xslFile, $data);
        echo $output;
    }

//    public function createPO($itemType, $itemID) {
//        $xmlFiles = [
//            '../../Model/Xml/cleaninginventory.xml',
//            '../../Model/Xml/foodinventory.xml',
//            '../../Model/Xml/habitatinventory.xml',
//            '../../Model/Xml/purchaseorder.xml',
//            '../../Model/Xml/purchaseorderlineitem.xml',
//            '../../Model/Xml/inventory.xml'
//        ];
//
//        switch ($itemType) {
//            case 'Food':
//                $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\InventoryIDFooditem.xsl';
//                break;
//            case 'Habitat':
//                $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\InventoryIDHabitatItem.xsl';
//                break;
//            case 'Cleaning':
//                $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\InventoryIDCleaningItem.xsl';
//                break;
//            // add more cases for other item types
//            default:
//                throw new Exception("Unknown itemType: $itemType");
//        }
//
//        $data = [
//            'activePage' => 'Inventory Management',
//            'pageCss' => 'purchaseorder.css',
//            'inventoryID' => $inventoryId,
//            'itemID' => $itemID,
//            'xslt_transform' => true
//        ];
//
//        $output = $this->view->renderXML($xmlFiles, $xslFile, $data);
//        echo $output;
//    }
    // Method to handle the default page (Inventory Catalog)
    public function createPO($inventoryId, $itemType, $itemID) {
        $POid = $this->model->getLatestPOID() + 1;
        $itemName = $this->model->getItemNameById($itemID, $itemType);
        $price = $this->model->getSupplyUnitPrice($itemID, $itemType);
        $suppliersID = $this->model->getSupplierIdBasedOnItemId($itemID, $itemType);

        foreach ($suppliersID as $supplierId) {
            $details = $this->model->getSupplierDetailsById($supplierId);
            if ($details) {
                $supplierDetails[$supplierId] = $details; // Store details with supplierId as key
            }
        }

        $data = [
            'POid' => $POid,
            'itemName' => $itemName,
            'itemID' => $itemID,
            'price' => $price,
            'supplierDetails' => $supplierDetails,
            'image' => "../../assests/InventoryImages/" . $itemType . "_" . $inventoryId . "_" . $itemID . ".jpg",
            'activePage' => 'Inventory Management',
            'pageCss' => 'purchaseorder.css',
            'xslt_transform' => false
        ];

        $this->view->render('PurchaseOrder', $data);
    }
}
