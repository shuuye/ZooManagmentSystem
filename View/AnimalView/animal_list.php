<?php

include_once '../../Control/AnimalControllerN/AnimalController.php';

// Get the current page and set the number of animals per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$animalsPerPage = 10;
$offset = ($page - 1) * $animalsPerPage;

$animalController = new AnimalController();

// Get the category if it's set
$category = isset($_GET['category']) ? $_GET['category'] : null;
$animals = $animalController->displayAnimals($category, $animalsPerPage, $offset); // Pass limit and offset

// Get total number of animals for pagination
$totalAnimals = $animalController->countAnimals($category); // You need a method to count total animals
$totalPages = ceil($totalAnimals / $animalsPerPage);
$categories = ['Mammals', 'Birds', 'Amphibians'];

?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/AnimalN/ani_Nav.css">
    <link rel="stylesheet" type="text/css" href="../../Css/AnimalN/ani_list.css">
    <title>Animal List</title>
</head>
<body>
    
     <nav>
        <ul>
            <li><a href="animal_home.php?action=home">Animal Home</a></li>
            <li><a href="index.php?action=showForm">Add New Animal</a></li>
        </ul>
    </nav>
    
    <h1>Animal List</h1>

    <div id="animalList">
        
    <form method="GET" action="">
        
        <label for="category"> Search By Category: </label>
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
                <th>Age</th>
                <th>Gender</th>
                <th>Date of Born</th>
                <th>Average Life Span </th>
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
                        <td><?php echo htmlspecialchars($animal['species']); ?></td>
                        <td><?php echo htmlspecialchars($animal['age']); ?></td>
                        <td><?php echo htmlspecialchars($animal['gender']); ?></td>
                        <td><?php echo htmlspecialchars($animal['date_of_birth']); ?></td>
                        <td><?php echo htmlspecialchars($animal['avg_lifespan']); ?></td>
                        <td><?php echo htmlspecialchars($animal['weight']); ?></td>
                        <td><?php echo htmlspecialchars($animal['habitat_id']); ?></td>
                        <td><?php echo htmlspecialchars($animal['categories']); ?></td>
                        <td>
                            <a href="index.php?action=edit&id=<?php echo urlencode($animal['id']); ?>">Edit</a> |
                            <a href="index.php?action=delete&id=<?php echo urlencode($animal['id']); ?>" onclick="return confirm('Are you sure you want to delete this animal?');">Delete</a>
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
         <!-- Pagination Links -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
            <a href="?page=<?php echo $i; ?>&category=<?php echo urlencode($category); ?>" <?php echo $i == $page ? 'class="active"' : ''; ?>>
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>
         
    </div>
</body>
</html>