<?php
include_once '../../Control/AnimalControllerN/AnimalController.php';

$animalController = new AnimalController();
$animalController->route();
$category = isset($_GET['category']) ? $_GET['category'] : null;
$animals = $animalController->displayAnimals($category);

$categories = ['Mammals', 'Birds', 'Amphibians']; // You can also fetch these categories dynamically from the database if needed
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
    <nav>
        <ul>
            <li><a href="?action=showForm">Add New Animal</a></li>
        </ul>
    </nav>
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
                <th>Image</th>
                <th>Animal ID</th>
                <th>Name</th>
                <th>Species</th>
                <th>Height</th>
                <th>Weight</th>
                <th>Habitat ID</th>
                <th>Category</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($animals)) : ?>
                <?php foreach ($animals as $animal) : ?>
                    <tr>
                        <td>
                            <?php if ($animal['image']) : ?>
                                <img src="<?php echo htmlspecialchars($animal['image']); ?>" alt="Animal Image" width="80" height="75">
                            <?php else : ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($animal['id']); ?></td>
                        <td><?php echo htmlspecialchars($animal['name']); ?></td>
                        <td><?php echo htmlspecialchars($animal['species']); ?></td>
                        <td><?php echo htmlspecialchars($animal['height']); ?></td>
                        <td><?php echo htmlspecialchars($animal['weight']); ?></td>
                        <td><?php echo htmlspecialchars($animal['habitat_id']); ?></td>
                        <td><?php echo htmlspecialchars($animal['categories']); ?></td>
                        <td>
                            <a href="?action=edit&id=<?php echo urlencode($animal['id']); ?>">Edit</a> |
                            <a href="?action=delete&id=<?php echo urlencode($animal['id']); ?>" onclick="return confirm('Are you sure you want to delete this animal?');">Delete</a>
                        </td> <!-- Added Actions links -->
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