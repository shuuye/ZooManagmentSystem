<?php
require_once '../../Model/Observer/Habitat.php';

class habitat_home {
    private $habitatModel;

    public function __construct() {
        $this->habitatModel = new Habitat();
    }

    // Display all habitats in a table format
    public function displayAllHabitats() {
        $habitats = $this->habitatModel->getAllHabitats();

        echo "<h1>Habitats List</h1>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Name</th><th>Availability</th><th>Actions</th></tr>";

        foreach ($habitats as $habitat) {
            echo "<tr>";
            echo "<td>{$habitat['habitat_id']}</td>";
            echo "<td>{$habitat['habitat_name']}</td>";
            echo "<td>{$habitat['availability']}</td>";
            echo "<td>
                    <a href='habitat_manage.php?id={$habitat['habitat_id']}'>Edit</a>
                  </td>";
            echo "</tr>";
        }

        echo "</table>";
    }

  
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../../Css/Animal/animal_main.css">
</head>
<body>

<div class="sidebar">
    <h1>Pam</h1>
    <ul>
        <li><a href="animal_home.php">Animal Management</a></li> 
        <li><a href="#">Habitat Management</a></li>
    </ul>
</div>
    
<div class="content">
    <div class="title">
        <h1>Animal Management</h1>
    </div>

    <div class="section-title">Our Habitats</div>
  
    <div class="container">
        <!-- Display the list of habitats -->
        <?php
        $habitatHome = new habitat_home();
        $habitatHome->displayAllHabitats();
        ?>
        
        <a href="habitat_manage.php" class="button2">Add New Habitat</a>
    </div>
    
    

  
  
</div>
</body>
</html>
