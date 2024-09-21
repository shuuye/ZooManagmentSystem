<!--This page is for edit and delete table, which for modification table display.--> 
<?php
    session_start();
    // Include the controller
    require_once '../../Control/AnimalControllerN/HabitatControllerObserver.php';
    // Create an instance of the controller
    $habitatController = new HabitatControllerObserver();
    // Handle form submissions
    $habitatController->handleFormSubmission();
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit & Delete of Habitats</title>
    <link rel="stylesheet" type="text/css" href="../../Css/AnimalN/ani_Nav.css">
    <link rel="stylesheet" type="text/css" href="../../Css/AnimalN/habitatView.css">
</head>
<body>
    
    <nav>
        <ul>
            <li><a href="add_habitat.php">Add New Habitat</a></li>
            <li><a href="habitatViewOnly.php">View Habitat</a></li>
        </ul>
    </nav>
    
    <h1>Edit and Delete of Habitats</h1>
    
    <!-- Display error message if it exists -->
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error-message" style="color: red;">
            <?php
            echo $_SESSION['error_message'];
            unset($_SESSION['error_message']); // Clear the message after displaying
            ?>
        </div>
    <?php endif; ?>
    
    <table>
        <thead>
            <tr>
                <th>Habitat ID</th>
                <th>Habitat Name</th>
                <th>Availability</th>
                <th>Capacity</th>
                <th>Environment</th>
                <th>Description</th>
                <th>#</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch all habitats
            $habitats = $habitatController->displayHabitats();
            ?>
            <?php if (!empty($habitats)): ?>
                <?php foreach ($habitats as $habitat): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($habitat['habitat_id']); ?></td>
                        <td><?php echo htmlspecialchars($habitat['habitat_name']); ?></td>
                        <td><?php echo htmlspecialchars($habitat['availability']); ?></td>
                        <td><?php echo htmlspecialchars($habitat['capacity']); ?></td>
                        <td><?php echo htmlspecialchars($habitat['environment']); ?></td>
                        <td><?php echo htmlspecialchars($habitat['description']); ?></td>
                        <td>
                            <form action="edit_habitat.php" method="POST">
                                <input type="hidden" name="habitat_id" value="<?php echo $habitat['habitat_id']; ?>">
                                <input type="submit" value="Edit">
                            </form>
                        </td>
                        <td>
                         <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="habitat_id" value="<?php echo $habitat['habitat_id']; ?>">
                                <input type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this habitat?')">
                            </form>
                        </td>
                        
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No habitats found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
</body>
</html>
