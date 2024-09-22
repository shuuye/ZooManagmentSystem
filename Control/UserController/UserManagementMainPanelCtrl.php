<?php
    /*Author name: Chew Wei Seng*/
    require_once __DIR__ . '/../../Config/webConfig.php';
    require_once __DIR__ . '/../../Model/XmlGenerator.php';
    require_once __DIR__ . '/../../Model/Xml/XSLTransformation.php';

    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInAdmin();

    require_once 'UserController.php';

    class UserManagementMainPanelCtrl extends UserController {
        public function __construct() {
            parent::__construct();
        }

        public function userManagementMainPanel() {
            $this->index();
        }

        public function index() {
            $data = $this->setRenderData('User Management Panel');

            // Generate XML file
            $xmlGenerator = new XmlGenerator();
            $xmlGenerator->createXMLFileByTableName('users', 'users.xml', 'users', 'user', 'id');

            // Set current date in YYYY-MM-DD format
            $currentDate = date('Y-m-d'); // Current date

            // Transform user status report
            $userStatus = new XSLTransformation("Model/Xml/users.xml", "View/UserView/userStatus.xsl");
            $userStatus->setParameter('currentDate', $currentDate);
            $transformedUserStatusReportOutput = $userStatus->transform();

            // Transform user dashboard
            $userDashboard = new XSLTransformation("Model/Xml/users.xml", "View/UserView/userDashboard.xsl");
            $userDashboard->setParameter('currentDate', $currentDate);
            $transformedDashboardOutput = $userDashboard->transform();

            // Prepare view data
            $view = [
                'adminTopNavHeader',
                'userManagementTopNav',
                'userManagementMainPanelView',
                $transformedDashboardOutput, // Ensure this is correctly used in your view
                $transformedUserStatusReportOutput
            ];

            // Render the user view
            $this->renderView($view, $data);
        }
    }
