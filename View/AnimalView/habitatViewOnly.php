 <?php
// Include the controller
require_once '../../Control/AnimalControllerN/HabitatControllerObserver.php';

// Create an instance of the controller
$habitatController = new HabitatControllerObserver();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Habitats</title>
    <link rel="stylesheet" type="text/css" href="../../Css/Habitat/habitat.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="add_habitat.php">Add New Habitat</a></li>
            <li><a href="list_habitats.php">Edit and Delete Habitat</a></li>
        </ul>
    </nav>
    <h1> Habitats for View</h1>
    <table>
        <thead>
            <tr>
                <th>Habitat ID</th>
                <th>Habitat Name</th>
                <th>Availability</th>
                <th>Capacity</th>
                <th>Environment</th>
                <th>Description</th>
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