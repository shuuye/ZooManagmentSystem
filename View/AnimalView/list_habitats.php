<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Habitats</title>
    <link rel="stylesheet" type="text/css" href="../../Css/Habitat/habitat.css">
</head>
<body>
    <h1>List of Habitats</h1>
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
            // Include the controller
            require_once '../../Control/AnimalControllerN/HabitatControllerObserver.php';

            // Create an instance of the controller
            $habitatController = new HabitatControllerObserver();

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
                            <form action="edit_habitat.php" method="post">
                                <input type="hidden" name="habitat_id" value="<?php echo $habitat['habitat_id']; ?>">
                                <input type="submit" value="Edit">
                            </form>
                        </td>
                        <td>
                         <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="habitat_id" value="<?php echo $habitat['habitat_id']; ?>">
                                <input type="submit" value="Delete">
                            </form>
                        </td>
                        <?php
                        $habitatController->handleFormSubmission();
                        ?>
                        
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No habitats found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
     <div>
        <form action="add_habitat.php" method="get">
            <button type="submit">Add New Habitat</button>
        </form>
    </div>
    
</body>
</html>
