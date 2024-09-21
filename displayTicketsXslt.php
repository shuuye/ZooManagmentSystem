<style>
    .topnav {
        overflow: hidden;
        background-color: darkblue;
        height: 30px;
    }

    .topnav a {
        float: left;
        color: #f2f2f2;
        text-align: center;
        padding: 5px 16px;
        text-decoration: none;
        font-size: 17px;
    }

    .topnav a:hover {
        background-color: #ddd;
        color: black;
    }

    .topnav a.active {
        background-color: #04AA6D;
        color: white;
    }
    /* General Table Styling */
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 18px;
        text-align: left;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
    }

    /* Table Header Styling */
    th, td {
        padding: 12px 15px;
    }

    th {
        background-color: #000000;
        color: white;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        text-align: center;
    }

    /* Table Row and Column Styling */
    tr {
        border-bottom: 1px solid #dddddd;
    }

    tr:nth-of-type(even) {
        background-color: #f9f9f9;
    }

    tr:nth-of-type(odd) {
        background-color: #ffffff;
    }

    tr:last-of-type {
        border-bottom: 2px solid #000000;
    }

    /* Row Hover Effect */
    tr:hover {
        background-color: #e0e0e0;
        cursor: pointer;
    }

    /* Table Cell Styling */
    td {
        text-align: center;
        color: #333;
    }

    /* Highlighted Row */
    tr.selected {
        background-color: #000000;
        color: white;
    }


</style>

<div class="topnav">
    <a href="index.php?controller=admin&action=displayAdminMainPanel">Admin Main Panel</a>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['currentUserModel'])) {
        echo '<a href="index.php?controller=user&action=logOut" style="float: right">Log Out</a>';
        echo '<a href="index.php?controller=user&action=showUserProfile" style="float: right">Profile</a>';
        echo '<p style="float: right; color: white; margin-top:7px;">Welcome, ' . $_SESSION['currentUserModel']['username'] . '</p>';
    }
    ?>
</div>

<!--place link-->
<div class="topnav" style="background-color: blue;">
    <a href="createanddeletefunction.php" class="link-box">Event Management</a>
    <a href="index.php?controller=user&action=userManagementMainPanel" class="link-box">User Management</a>
    <a href="index.php?controller=inventory&action=index" class="link-box">Inventory Management Panel</a>
    <?php
    if (isset($_SESSION['currentUserModel']) && $_SESSION['currentUserModel']['role']['roleID'] == 1 && !in_array('edit', $_SESSION['currentUserModel']['permissions'])) {
        echo'<a href="/ZooManagementSystem/displayTicketsXslt.php" class="link-box">Ticketing & Payment Management Panel</a>';
    } else {
        echo'<a href="/ZooManagementSystem/adminTicketPage.php" class="link-box">Ticketing & Payment Management Panel</a>';
    }
    ?>
    <a href="View/AnimalView/animal_home.php" class="link-box">Animal Management Panel</a>
</div>
<?php
// Set the content type to XML
header("Content-type: text/html");

// Load the XML file
$xml = new DOMDocument;
$xml->load(__DIR__ . '/Model/Xml/tickets.xml');

// Load the XSLT file
$xsl = new DOMDocument;
$xsl->load(__DIR__ . '/Model/Xml/tickets.xsl');

// Configure the transformer
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl); // Attach the XSL rules
// Transform the XML and display the result
echo $proc->transformToXML($xml);
?>
