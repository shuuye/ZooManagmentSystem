<!--This is my whole animal system Home page, if i want to do summary report i will do it here-->
<?php
include_once 'C:\xampp\htdocs\ZooManagementSystem\View\adminTopNavHeader.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/ZooManagementSystem/Css/AnimalN/ani_Nav.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    <script src="chart.js" defer></script>
    <title>Animal Home Page</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php?controller=animal&action=anilist">Animal Management</a></li>
            <li><a href="/ZooManagementSystem/View/AnimalView/habitatViewOnly.php">Animal Habitat Management</a></li>
            <li><a href="/ZooManagementSystem/View/AnimalView/list_healthRecords.php">Animal Health Management</a></li>
            <li><a href="/ZooManagementSystem/View/AnimalView/feeding_report.php">Animal Food Management</a></li>
        </ul>
    </nav>
    
    <h1>Animal Dashboard</h1>
    
    <div id="chart">
        <h2>Animal Categories Pie Chart</h2><!--Web Service 1 one consume-->
        <canvas id="myPieChart"></canvas>
    </div>
  
    <div id="Dcount">
        <h2>Total Animal Health Status Count : </h2>
        <?php
        include 'C:\xampp\htdocs\ZooManagementSystem\Control\AnimalControllerN\HealthController.php';
        $controller = new HealthController();
        $controller->displayHealthStatusCounts();
        ?>
    </div>
    
</body>
</html>