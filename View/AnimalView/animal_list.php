<?php
include_once '../../Control/AnimalControllerN/AnimalController.php';

$animalController = new AnimalController();
$category = isset($_GET['$categories']) ? $_GET['$categories'] : null;
$animalController->displayAnimals($category);

$categories = ['Mammals', 'Birds', 'Amphibians'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Animal List</h1>

    <form method="GET" action="">
        <label for="category">Select Category:</label>
        <select name="category" id="category" onchange="this.form.submit()">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat) : ?>
                <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo isset($_GET['category']) && $_GET['category'] == $cat ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <table>
        <thead>
            <tr>
                <th>Animal ID</th>
                <th>Name</th>
                <th>Species</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Health Status</th>
                <th>Habitat</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($animals)) : ?>
                <?php foreach ($animals as $animal) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($animal['animalID']); ?></td>
                        <td><?php echo htmlspecialchars($animal['animalName']); ?></td>
                        <td><?php echo htmlspecialchars($animal['species']); ?></td>
                        <td><?php echo htmlspecialchars($animal['age']); ?></td>
                        <td><?php echo htmlspecialchars($animal['gender']); ?></td>
                        <td><?php echo htmlspecialchars($animal['healthStatus']); ?></td>
                        <td><?php echo htmlspecialchars($animal['habitat']); ?></td>
                        <td><?php echo htmlspecialchars($animal['category']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="8">No animals found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

