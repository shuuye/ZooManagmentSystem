<!--This is my whole animal system Home page, if i want to do summary report i will do it here-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../../Css/AnimalN/ani_Nav.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="chart.js" defer></script>
    <title>Animal Home Page</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="animal_list.php?action=anilist">Animal Management</a></li>
            <li><a href="habitatViewOnly.php">Animal Habitat Management</a></li>
            <li><a href="list_healthRecords.php">Animal Health Management</a></li>
            <li><a href="feeding_report.php">Animal Food Management</a></li>
        </ul>
    </nav>
    
     <div id="chart">
    <h2>Animal Categories Pie Chart</h2><!--Web Service 1 one consume-->
    <canvas id="myPieChart"></canvas>
    </div>
    
    
    
</body>
</html>