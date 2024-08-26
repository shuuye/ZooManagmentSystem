<?php
require_once '../../Control/AnimalController/AnimalController.php';
require_once '../../Control/AnimalController/HabitatController.php';


session_start();

$animalController = new AnimalController();
$error_message = '';

$habitatController = new HabitatController();
$availableHabitats = $habitatController->displayAvailableHabitats();

// Get the selected category from the query parameter
$selectedCategory = isset($_GET['categories']) ? $_GET['categories'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $animalData = [
        'inventoryId' => $_POST['inventoryId'],
        'name' => $_POST['name'],
        'species' => $_POST['species'],
        'subspecies' => $_POST['subspecies'],
        'categories' => $selectedCategory,
        'age' => $_POST['age'],
        'gender' => $_POST['gender'],
        'date_of_birth' => $_POST['date_of_birth'],
        'avg_lifespan' => $_POST['avg_lifespan'],
        'description' => $_POST['description'],
        'height' => $_POST['height'],
        'weight' => $_POST['weight'],
        'healthStatus' => $_POST['healthStatus'],
        'habitat_id' => $_POST['habitat_id']
    ];

    $result = $animalController->addAnimal($animalData);

    if ($result) {
        $_SESSION['success_message'] = "Animal added successfully!";
        header('Location: animal_home.php');
        exit();
    } else {
        $error_message = $_SESSION['error_message'] ?? 'Error adding animal.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/Animal/animal.css">
    <title>Add Animal</title>
    
</head>
<body>
    
    <div class="sidebar">
        <h1>Pam</h1>
        <ul>
          <li><a href="animal_home.php">Animal Management</a></li> 
          <li><a href="habitat_home.php">Habitat Management</a></li>
        </ul>
     </div>
    
    <h2>Add Animal</h2>
    <?php if (!empty($error_message)): ?>
        <div class="error-message"><?= $error_message ?></div>
    <?php endif; ?>
        
    <form action="add_animal.php?category=<?= urlencode($selectedCategory) ?>" method="POST">
        <label for="inventoryId">Inventory ID:</label>
        <input type="text" name="inventoryId" required><br>
        
        <label for="name">Name:</label>
        <input type="text" name="name" required><br>

        <label for="species">Species:</label>
        <input type="text" name="species" required><br>

        <label for="subspecies">Subspecies:</label>
        <input type="text" name="subspecies" required><br>

        <!-- Pre-selected Category (Hidden) -->
        <input type="hidden" name="categories" value="<?= htmlspecialchars($selectedCategory) ?>">
        <p>Category: <strong><?= htmlspecialchars($selectedCategory) ?></strong></p>

        <label for="age">Age:</label>
        <input type="number" name="age" required><br>

        <label for="gender">Gender:</label>
        <select name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select><br>

        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" name="date_of_birth" required><br>

        <label for="avg_lifespan">Average Lifespan:</label>
        <input type="number" name="avg_lifespan" required><br>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea><br>

        <label for="height">Height:</label>
        <input type="number" step="0.01" name="height" required><br>

        <label for="weight">Weight:</label>
        <input type="number" step="0.01" name="weight" required><br>

        <label for="healthStatus">Health Status:</label>
        <select name="healthStatus" required>
            <option value="Healthy">Healthy</option>
            <option value="Normal">Normal</option>
            <option value="Treatment">Treatment</option>
            <option value="Warning">Warning</option>
        </select><br>

        <label for="habitat_id">Habitat:</label>
        <select name="habitat_id" required>
            <?php foreach ($availableHabitats as $habitat): ?>
                <option value="<?= htmlspecialchars($habitat['habitat_id']) ?>">
                    <?= htmlspecialchars($habitat['habitat_name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <button type="submit">Add Animal</button>
    </form>
      
        
</body>
</html>
