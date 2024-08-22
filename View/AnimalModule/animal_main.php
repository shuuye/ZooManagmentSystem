<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Animals</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: sans-serif;
        }
        .table-container {
            margin-top: 20px;
        }
        .table-container .table th {
            text-align: center;
        }
        .table-container .table td {
            text-align: left;
        }
        .table-container .table td img {
            max-width: 50px;
            height: auto;
        }
        .pagination {
            margin-top: 20px;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>List of Animals</h1>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
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
                    require_once '../../Config/AnimalDB/dbConnection.php';
                    require_once '../../Model/Command/AnimalInventory.php';
                    require_once '../..//Model/Observer/AnimalManagementSystem.php';
                    
                    // Initialize the Animal Management System
                    $animalManagementSystem = new AnimalManagementSystem();

                    // Load all animals from the database
                    $animalManagementSystem->loadAnimalsFromDatabase();

                    // Get the list of animals
                    $animals = $animalManagementSystem->getAnimals();


                    // Check if animals exist in the array
                    if ($animals) {
                        // Loop through the animals and display them
                        foreach ($animals as $animal) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($animal->getId()) . '</td>';
                            echo '<td>' . htmlspecialchars($animal->getName()) . '</td>';
                            echo '<td>' . htmlspecialchars($animal->getCategory()) . '</td>';
                            echo '<td>' . htmlspecialchars($animal->getSpecies()) . '</td>';
                            echo '<td>' . htmlspecialchars($animal->getAge()) . '</td>';
                            echo '<td>' . htmlspecialchars($animal->getGender()) . '</td>';
                            echo '<td>' . htmlspecialchars($animal->getDescription()) . '</td>';
                            echo '<td><button class="btn btn-primary">View Details</button></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5">No animals found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <!-- Pagination links can be added here -->
            </ul>
        </nav>
    </div>
</body>
</html>
