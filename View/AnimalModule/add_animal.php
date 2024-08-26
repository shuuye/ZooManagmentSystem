<?php
// The data store still have probelm the date health status and habitat cannot store successfully need debug.
// still have edit function
// structure need to change forget to use animal controller


session_start(); // Ensure the session is started for authentication check

require_once '../../Config/databaseConfig.php';
require_once '../../Model/Command/AnimalInventory.php';

// 1. Authentication Check
//if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
//    echo 'You need to be logged in to upload files.';
//    exit;
//}


$db = new databaseConfig();
$pdo = $db->getConnection();

// Fetch habitats for the dropdown
$habitats = $pdo->query("SELECT habitat_id, habitat_name FROM habitats")->fetchAll(PDO::FETCH_ASSOC);

// Gender and Health Status options
$genders = ['Male', 'Female'];

// Get category from the URL
$categories = isset($_GET['$categories']) ? $_GET['$categories'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $categories = $_POST['categories'];
    $species = $_POST['species'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $date_of_birth = $_POST['date_of_birth'];
    $avg_lifespan = $_POST['avg_lifespan'];
    $description = $_POST['description'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $healthStatus = $_POST['healthStatus'];
    $habitat = $_POST['habitat'];
    
    // File upload handling
    if (isset($_FILES['animal_image']) && $_FILES['animal_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['animal_image']['tmp_name'];
        $fileName = $_FILES['animal_image']['name'];
        $fileSize = $_FILES['animal_image']['size'];
        $fileType = $_FILES['animal_image']['type'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // 2. Limit the types of files allowed (only images)
        $allowedFileTypes = ['jpg', 'jpeg', 'png'];
        if (!in_array($fileExtension, $allowedFileTypes)) {
            echo 'Invalid file type. Only JPG, JPEG, and PNG files are allowed.';
            exit;
        }

        // 3. Validate the file type by checking the file headers (MIME type)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fileTmpPath);
        finfo_close($finfo);

        $allowedMimeTypes = ['image/jpg','image/jpeg', 'image/png'];
        if (!in_array($mimeType, $allowedMimeTypes)) {
            echo 'Invalid file content. The uploaded file is not a valid image.';
            exit;
        }

        // 4. Save files securely (outside web root)
        $uploadDir = '../../assests/AnimalImages/'; // Directory outside the web root
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Create directory if it doesn't exist
        }
        $destinationPath = $uploadDir . $fileName;

        if (!move_uploaded_file($fileTmpPath, $destinationPath)) {
            echo 'There was an error moving the uploaded file.';
            exit;
        }

        // 5. Virus scanning (Placeholder for virus scan logic)
        // Here you would call an external service like ClamAV for virus scanning
        // scanFileForViruses($destinationPath);
        
        /// Add the new animal to the database
        $animalInventory = new AnimalInventory(
            null, $name, $categories, $species, $age, $gender, $date_of_birth, 
            $avg_lifespan, $description, $height, $weight, $healthStatus, $habitat
        );
        $animal_id = $animalInventory->addAnimal();

        if ($animal_id) {
            // Insert image data into the database
            $db = new databaseConfig();
            $pdo = $db->getConnection();
            
            $stmt = $pdo->prepare("INSERT INTO animal_images (animal_id, image_path) VALUES (?, ?)");
            $stmt->execute([$animal_id, $destinationPath, $description]);

            if ($stmt->rowCount()) {
                echo "Animal added and image uploaded successfully!";
            } else {
                echo "Database insertion failed.";
            }
        } else {
            echo "Failed to retrieve the animal ID.";
        }

    } else {
        echo 'No file uploaded or there was an upload error.';
        exit;
    }

    // Redirect to the animal list page
    header('Location: animal_list.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/Animal/anima_add.css">
    <link rel="stylesheet" type="text/css" href="../../Css/Animal/animal_main.css">
    <title>Add New Animal</title>
</head>

<body>
    
    <div class="sidebar">
        <h1>Pam</h1>
        <ul>
          <li><a href="animal_home.php">Animal Management</a></li> 
          <li><a href="#">Habitat Management</a></li>
        </ul>
    </div>
    
    <div class="containeradd">
        <h2>Add New Animal</h2>
        <form action="" id="form-group" method="post" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>
            
            <label for="categories">Category:</label>
            <input type="text" id="category" name="category" value="<?= htmlspecialchars($categories) ?>" readonly><br><br>
            
            <label for="species">Species:</label>
            <input type="text" id="species" name="species" required><br><br>
            
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required><br><br>
            
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <?php foreach ($genders as $g): ?>
                    <option value="<?= htmlspecialchars($g) ?>"><?= htmlspecialchars($g) ?></option>
                <?php endforeach; ?>
            </select><br><br>
            
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" required><br><br>
            
            <label for="avg_lifespan">Average Lifespan:</label>
            <input type="text" id="avg_lifespan" name="avg_lifespan" required><br><br>
            
            <label for="height">Height:</label>
            <input type="text" id="height" name="height" required><br><br>
            
            <label for="weight">Weight:</label>
            <input type="text" id="weight" name="weight" required><br><br>
            
            <label for="healthStatus">Health Status:</label>
            <select id="healthStatus" name="healthStatus" required>
                <option value="Healthy">Healthy</option>
                <option value="Normal">Normal</option>
                <option value="Treatment">Treatment</option>
                <option value="Warning">Warning</option>
            </select><br><br>
            
            <label for="habitat">Habitat:</label>
            <select id="habitat" name="habitat" required>
                <?php foreach ($habitats as $habitat): ?>
                    <option value="<?= htmlspecialchars($habitat['habitat_id']) ?>"><?= htmlspecialchars($habitat['habitat_name']) ?></option>
                <?php endforeach; ?>
            </select><br><br>
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea><br><br>
            
            <label for="animal_image">Upload Animal Image:</label>
            <input type="file" id="animal_image" name="animal_image" accept="image/*" required><br><br>
            
            <input type="submit" value="Add Animal">
        </form>
    </div>
</body>
</html>
