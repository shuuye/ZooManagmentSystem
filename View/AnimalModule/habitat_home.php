<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Habitats</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>List of Habitats</h1>
        <a href="habitat_manage.php" class="btn btn-success mb-3">Add New Habitat</a>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Availability</th>
                        <th>Capacity</th>
                        <th>Environment</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once '../../Control/AnimalController/HabitatController.php';

                    // Initialize the controller
                    $controller = new HabitatController();

                    // Get the list of habitats
                    $habitats = $controller->getHabitats();

                    // Loop through the habitats and display them in the table
                    if ($habitats) {
                        foreach ($habitats as $habitat) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($habitat->getId()) . '</td>';
                            echo '<td>' . htmlspecialchars($habitat->getName()) . '</td>';
                            echo '<td>' . htmlspecialchars($habitat->getAvailability()) . '</td>';
                            echo '<td>' . htmlspecialchars($habitat->getCapacity()) . '</td>';
                            echo '<td>' . htmlspecialchars($habitat->getEnvironment()) . '</td>';
                            echo '<td>' . htmlspecialchars($habitat->getDescription()) . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7">No habitats found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
