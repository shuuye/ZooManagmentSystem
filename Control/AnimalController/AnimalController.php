<?php
require_once '../../Config/databaseConfig.php';

class AnimalController {
    private $conn;

    public function __construct() {
        $databaseConfig = new databaseConfig();
        $this->conn = $databaseConfig->getConnection();
    }

    public function addAnimal($animalData) {
        try {
            $sql = "INSERT INTO animalinventory (inventoryId, name, species, subspecies, categories, age, gender, date_of_birth, avg_lifespan, description, height, weight, healthStatus, habitat_id) 
                    VALUES (:inventoryId, :name, :species, :subspecies, :categories, :age, :gender, :date_of_birth, :avg_lifespan, :description, :height, :weight, :healthStatus, :habitat_id)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':inventoryId', $animalData['inventoryId']);
            $stmt->bindParam(':name', $animalData['name']);
            $stmt->bindParam(':species', $animalData['species']);
            $stmt->bindParam(':subspecies', $animalData['subspecies']);
            $stmt->bindParam(':categories', $animalData['categories']);
            $stmt->bindParam(':age', $animalData['age']);
            $stmt->bindParam(':gender', $animalData['gender']);
            $stmt->bindParam(':date_of_birth', $animalData['date_of_birth']);
            $stmt->bindParam(':avg_lifespan', $animalData['avg_lifespan']);
            $stmt->bindParam(':description', $animalData['description']);
            $stmt->bindParam(':height', $animalData['height']);
            $stmt->bindParam(':weight', $animalData['weight']);
            $stmt->bindParam(':healthStatus', $animalData['healthStatus']);
            $stmt->bindParam(':habitat_id', $animalData['habitat_id']);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
            return false;
        }
    }

    public function updateAnimal($id, $animalData) {
        try {
            $sql = "UPDATE animalinventory 
                    SET inventoryId = :inventoryId, name = :name, species = :species, subspecies = :subspecies, categories = :categories, age = :age, gender = :gender, 
                        date_of_birth = :date_of_birth, avg_lifespan = :avg_lifespan, description = :description, height = :height, weight = :weight, 
                        healthStatus = :healthStatus, habitat_id = :habitat_id 
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':inventoryId', $animalData['inventoryId']);
            $stmt->bindParam(':name', $animalData['name']);
            $stmt->bindParam(':species', $animalData['species']);
            $stmt->bindParam(':subspecies', $animalData['subspecies']);
            $stmt->bindParam(':categories', $animalData['categories']);
            $stmt->bindParam(':age', $animalData['age']);
            $stmt->bindParam(':gender', $animalData['gender']);
            $stmt->bindParam(':date_of_birth', $animalData['date_of_birth']);
            $stmt->bindParam(':avg_lifespan', $animalData['avg_lifespan']);
            $stmt->bindParam(':description', $animalData['description']);
            $stmt->bindParam(':height', $animalData['height']);
            $stmt->bindParam(':weight', $animalData['weight']);
            $stmt->bindParam(':healthStatus', $animalData['healthStatus']);
            $stmt->bindParam(':habitat_id', $animalData['habitat_id']);
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getAnimalById($id) {
        try {
            $sql = "SELECT * FROM animalinventory WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>

<?php
//my security practices code

//
//require_once 'C:\xampp\htdocs\ZooManagementSystem\Config\AnimalDB\databaseConfig.php';
//
//class AnimalController {
//    private $db;
//
//    public function __construct() {
//        // Initialize the database connection
//        $this->db = new databaseConfig();
//    }
//
//    public function addAnimal() {
//        // Authentication check
//        if (!$this->isAuthenticated()) {
//            header("Location: login.php"); // Redirect to login page if not authenticated
//            exit();
//        }
//
//        // Handle form submission and file upload
//        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//            // Validate form data (e.g., animal name, category, etc.)
//            $animal_name = trim($_POST['animal_name']);
//            $category = trim($_POST['category']);
//            // Add additional form fields validation here...
//
//            if (!empty($animal_name) && !empty($category)) {
//                // File upload handling
//                if (isset($_FILES['animal_image']) && $_FILES['animal_image']['error'] === 0) {
//                    $file = $_FILES['animal_image'];
//
//                    // File type validation (only allow specific image formats)
//                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
//                    if (!in_array($file['type'], $allowedTypes)) {
//                        echo "Invalid file type. Only JPEG, PNG, and GIF files are allowed.";
//                        return;
//                    }
//
//                    // Virus scanning (use a third-party service or external library)
//                    if (!$this->scanForViruses($file['tmp_name'])) {
//                        echo "File upload failed due to detected malware.";
//                        return;
//                    }
//
//                    // Secure file storage (store outside web root)
//                    $uploadDir = '/var/www/uploads/animals/';
//                    $newFileName = uniqid('', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
//                    $uploadPath = $uploadDir . $newFileName;
//
//                    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
//                        // Save file path to the database
//                        $this->saveAnimalImage($newFileName);
//
//                        // Save animal information to the database
//                        $this->saveAnimal($animal_name, $category);
//
//                        echo "Animal added successfully!";
//                    } else {
//                        echo "Failed to move uploaded file.";
//                    }
//                } else {
//                    echo "Error in file upload.";
//                }
//            } else {
//                echo "Please fill in all required fields.";
//            }
//        }
//
//        // Render the add animal view
//        require 'views/add_animal.php';
//    }
//
//    private function isAuthenticated() {
//        // Implement authentication check (e.g., check user session)
//        return isset($_SESSION['user_id']);
//    }
//
//    private function scanForViruses($filePath) {
//        // Implement virus scanning logic here
//        // Return true for demonstration; integrate with a third-party service for actual scanning
//        return true;
//    }
//
//    private function saveAnimalImage($fileName) {
//        // Save the image path to the database
//        $query = "INSERT INTO animal_images (image_path) VALUES (:image_path)";
//        $stmt = $this->db->getConnection()->prepare($query);
//        $stmt->execute([':image_path' => $fileName]);
//    }
//
//    private function saveAnimal($animal_name, $category) {
//        // Save the animal information to the database
//        $query = "INSERT INTO animals (name, category) VALUES (:name, :category)";
//        $stmt = $this->db->getConnection()->prepare($query);
//        $stmt->execute([':name' => $animal_name, ':category' => $category]);
//    }
//}
//
//?>
