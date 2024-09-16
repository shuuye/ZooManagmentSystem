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
                $status = isset($_GET['status']) ? $_GET['status'] : '';

                switch ($status) {
                    case 'successRemoveInv':
                        echo "<p class='alert alert-success'>Inventory removed successfully.</p>";
                        break;
                    case 'errorRemoveInv':
                        echo "<p class='alert alert-error'>Failed to remove Inventory. Please try again.</p>";
                        break;
                }
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
                $status = isset($_GET['status']) ? $_GET['status'] : '';
                switch ($status) {
                    case 'success':
                        echo "<p class='alert alert-success'>New brand added successfully.</p>";
                        break;
                    case 'error':
                        echo "<p class='alert alert-error'>Failed to add new brand. Please try again.</p>";
                        break;
                    case 'successEdit':
                        echo "<p class='alert alert-success'>Brand details edited successfully.</p>";
                        break;
                    case 'errorEdit':
                        echo "<p class='alert alert-error'>Failed to edit brand details. Please try again.</p>";
                        break;
                    case 'successRemove':
                        echo "<p class='alert alert-success'>Item removed successfully.</p>";
                        break;
                    case 'errorRemove':
                        echo "<p class='alert alert-error'>Failed to remove item. Please try again.</p>";
                        break;
                }
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
            case 'sendPO':
                $POid = isset($_GET['POid']) ? $_GET['POid'] : null;
                $this->sendPO($POid);
                break;
            case 'logusage':
                $status = isset($_GET['status']) ? $_GET['status'] : '';
                $newQuantity = isset($_GET['newQuantity']) ? $_GET['newQuantity'] : '';
                switch ($status) {
                    case 'success':
                        echo "<p class='alert alert-success'>Inventory usage logged successfully. New available quantity: " . $newQuantity . "</p>";
                        break;
                    case 'error':
                        echo "<p class='alert alert-error'>Error logging inventory usage.</p>";
                        break;
                    case 'itemNotfound':
                        echo "<p class='alert alert-error'>Error: Inventory item not found.</p>";
                        break;
                    case 'invalidRequest':
                        echo "<p class='alert alert-warning'>Invalid request method.</p>";
                        break;
                }
                $this->logUsage();
                break;
            case 'showPO':
                $status = isset($_GET['status']) ? $_GET['status'] : '';

                switch ($status) {
                    case 'updateSuccess':
                        echo "<p class='alert alert-success'>Purchase order status updated successfully.</p>";
                        break;
                    case 'updateError':
                        echo "<p class='alert alert-error'>Failed to update purchase order status.</p>";
                        break;
                    case 'successPO':
                        echo "<p class='alert alert-success'>Purchase order created successfully.</p>";
                        break;
                    case 'errorPO':
                        echo "<p class='alert alert-error'>Failed to create new purchase order.</p>";
                        break;
                    case 'success':
                        echo "<p class='alert alert-success'>Purchase order deleted successfully.</p>";
                        break;
                    case 'error':
                        echo "<p class='alert alert-error'>Failed to delete purchase order.</p>";
                        break;
                    case 'invalidPOid':
                        echo "<p class='alert alert-warning'>Invalid Purchase Order ID.</p>";
                        break;
                    case 'invalidRequest':
                        echo "<p class='alert alert-warning'>Invalid request method.</p>";
                        break;
                    default:
                        break;
                }
                $this->showPO();
                break;
            case 'index':
            default:
                $status = isset($_GET['status']) ? $_GET['status'] : '';

                switch ($status) {
                    case 'successRemoveInv':
                        echo "<p class='alert alert-success'>Inventory removed successfully.</p>";
                        break;
                    case 'errorRemoveInv':
                        echo "<p class='alert alert-error'>Failed to remove Inventory. Please try again.</p>";
                        break;
                    case 'success':
                        echo "<p class='alert alert-success'>Item added successfully.</p>";
                        break;
                    case 'error':
                        echo "<p class='alert alert-error'>Failed to add item. Please try again.</p>";
                        break;
                    case 'invalidRequest':
                        echo "<p class='alert alert-warning'>Invalid request method.</p>";
                        break;
                    case 'successEdit':
                        echo "<p class='alert alert-success'>Inventory details edited successfully.</p>";
                        break;
                    case 'errorEdit':
                        echo "<p class='alert alert-error'>Failed to edit inventory details. Please try again.</p>";
                        break;
                    default:
                        break;
                }
                $this->index();
                break;
//             require_once '../../Model/Inventory/PurchaseOrder.php';
//               $email = isset($_GET['email']) ? $_GET['email'] : 'default';
//                $controller = new PurchaseOrder();
//                $controller->showPO($email);
        }
    }

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

    public function logUsage() {
        $inventoryData = $this->model->getInventory();
        $data = [
            'activePage' => 'Log Usage',
            'pageCss' => 'InventoryUsage.css',
            'xslt_transform' => false,
            'inventoryData' => $inventoryData
        ];

        $this->view->render('InventoryUsage', $data);
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

    public function createPO($inventoryId, $itemType, $itemID) {
        $POid = $this->model->getLatestPOID() + 1;
        $itemName = $this->model->getItemNameById($itemID, $itemType);
        $Allprice = $this->model->getSupplyUnitPrice($itemID, $itemType);
        $suppliersID = $this->model->getSupplierIdBasedOnItemId($itemID, $itemType);
        $imagePath = $this->model->getImageByid($itemID, $itemType);

        foreach ($suppliersID as $supplierId) {
            $details = $this->model->getSupplierDetailsById($supplierId);
            if ($details) {
                $supplierDetails[$supplierId] = $details; // Store details with supplierId as key
            }
        }
        $int = 0;
        foreach ($Allprice as $oneRecord) {

            if ($oneRecord) {
                $price[$suppliersID[$int]] = $oneRecord; // Store details with supplierId as key
                $int++;
            }
        }
        $inventoryDetails = [
            'inventoryId' => $inventoryId,
            'itemType' => $itemType,
            'itemID' => $itemID,
        ];

        $data = [
            'InventoryDetails' => $inventoryDetails,
            'POid' => $POid,
            'itemName' => $itemName,
            'itemID' => $itemID,
            'price' => $price,
            'supplierDetails' => $supplierDetails,
            'image' => "../../assests/InventoryImages/" . $imagePath,
            'activePage' => 'Inventory Management',
            'pageCss' => 'purchaseorder.css',
            'xslt_transform' => false
        ];

        $this->view->render('PurchaseOrder', $data);
    }

    public function sendPO($POid) {
        $xmlFiles = [
            '../../Model/Xml/cleaninginventory.xml',
            '../../Model/Xml/foodinventory.xml',
            '../../Model/Xml/habitatinventory.xml',
            '../../Model/Xml/purchaseorder.xml',
            '../../Model/Xml/purchaseorderlineitem.xml',
            '../../Model/Xml/inventory.xml',
            '../../Model/Xml/supplier.xml'
        ];
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\processPO.xsl';
        $data = [
            'activePage' => 'Purchase Order Management',
            'pageCss' => 'processPO.css',
            'POid' => $POid,
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFiles, $xslFile, $data);
        echo $output;
    }

    public function showPO() {
        $xmlFile = '../../Model/Xml/purchaseorder.xml';
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\showAllPO.xsl';
        $data = [
            'activePage' => 'Purchase Order Management',
            'pageCss' => 'mainInventoryTracking.css',
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFile, $xslFile, $data);
        echo $output;
    }
}
