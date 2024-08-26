<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/Animal/animal.css">
</head>

<body>
    
    <div class="sidebar">
        <h1>Pam</h1>
        <ul>
          <li><a href="animal_home.php">Animal Management</a></li> 
          <li><a href="habitat_home.php">Habitat Management</a></li>
        </ul>
     </div>
    
    <div class="container2">
        <h2>List of Animals</h2>
        <div class="table-container">
            <table class="table">
            <thead>
                <tr>
                    <th>Images</th>
                    <th>Animal ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Species</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            
            <tbody>
                <?php
                require_once '../../Config/databaseConfig.php';
                require_once '../../Model/Command/AnimalInventory.php';
                require_once '../..//Model/Observer/AnimalManagementSystem.php';
                
                // Initialize the Animal Management System
                $animalManagementSystem = new AnimalManagementSystem();

                // Load all animals from the database
                $animalManagementSystem->loadAnimalsFromDatabase();

                // Get the list of animals
                $animals = $animalManagementSystem->getAnimals();
                
                // Get the category from the URL (if provided)
                $categories = isset($_GET['categories']) ? $_GET['categories'] : null;

                // Filter animals based on category (if provided)
                $filteredAnimals = array_filter($animals, function ($animal) use ($categories) {
                    return $categories === null || $animal->getCategories() === $categories;
                });

                // Check if filtered animals exist in the array
                if ($filteredAnimals) {
                    // Loop through the filtered animals and display them
                    foreach ($filteredAnimals as $animal) {
                        // Get the animal image
                        $animalImage = null;
                        $db = new databaseConfig();
                        $pdo = $db->getConnection();
                        $stmt = $pdo->prepare("SELECT * FROM animal_image WHERE animal_id = ?");
                        $stmt->execute([$animal->getId()]);
                        $imageResult = $stmt->fetch();
                        if ($imageResult) {
                            $animalImage = $imageResult['image_path'];
                        }

                        echo '<tr>';
                        echo '<td><img src="' . $animalImage . '" alt="Animal Image" width="50" height="50"></td>';
                        echo '<td>' . htmlspecialchars($animal->getId()) . '</td>';
                        echo '<td>' . htmlspecialchars($animal->getName()) . '</td>';
                        echo '<td>' . htmlspecialchars($animal->getCategories()) . '</td>';
                        echo '<td>' . htmlspecialchars($animal->getSpecies()) . '</td>';
                        echo '<td>' . htmlspecialchars($animal->getAge()) . '</td>';
                        echo '<td>' . htmlspecialchars($animal->getGender()) . '</td>';
                        echo '<td>' . htmlspecialchars($animal->getDescription()) . '</td>';
                        echo '<td><button class="btn btn-primary" data-animal-id="' . $animal->getId() . '" data-toggle="modal" data-target="#animalDetailsModal">Edit Details</button></td>'; // Still need to do
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5">No animals found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
           
                <!-- Conditionally display Add New Animal button based on category -->
            <?php if ($categories === 'Amphibians') : ?>
                <a href="add_animal.php?categories=Amphibians" class="button2">Add New Amphibian</a>
            <?php elseif ($categories === 'Birds') : ?>
                <a href="add_animal.php?categories=Birds" class="button2">Add New Bird</a>
            <?php elseif ($categories === 'Mammals') : ?>
                <a href="add_animal.php?categories=Mammals" class="button2">Add New Mammal</a>
            <?php endif; ?>
                
        </div>
        
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <!-- Pagination links can be added here -->
            </ul>
        </nav>
    </div>
</body>
</html>
