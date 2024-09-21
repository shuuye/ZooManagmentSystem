<?php

include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\ObserverN\AnimalModel.php';
include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Inventory\InventoryModel.php';

class AnimalController extends InventoryModel{

    private $animalModel;
    private $inventoryModel;
    private ?AnimalInventory $AnimalInventory;
    private $AnimalList = [];

    public function __construct() {
        $this->animalModel = new AnimalModel();
        $this->inventoryModel = new InventoryModel();

    }
    
   public function route(){
        $controller = isset($_GET['controller']) ? $_GET['controller'] : "animal";
        $action = isset($_GET['action']) ? $_GET['action'] : 'home';
        if ($controller == "animal") {
         ob_start();
        switch ($action) {
            case 'home' :
                $this->home();
                break;
            case 'anilist' :
                $this->animallist();
                break;
            case 'showForm':
                $this->showForm();
                break;
            case 'processForm':
                $this->processForm();
                break;
            case 'edit':
                $this->editAnimal();
                break;
            case 'delete':
                $this->deleteAnimal();
                break;
            default:
                echo "Invalid action.";
                break;
        }
         ob_end_flush(); // Send output to browser and end buffering
    }else {
            header("Location: /ZooManagementSystem/index.php?controller=$controller&action=$action");
        }
   }
    
    public function home(){
         include 'C:\xampp\htdocs\ZooManagementSystem\View\AnimalView\animal_home.php';
         exit();
    }
    
    public function animallist(){
         include 'C:\xampp\htdocs\ZooManagementSystem\View\AnimalView\animal_list.php';
         exit();
    }
    
    // Show the form to select item names and add animal details
    public function showForm() {
        // Log or output information to verify it's being called
        error_log("showForm called");
        $itemNames = $this->inventoryModel->getAnimalItemNames();
        $availableHabitats = $this->animalModel->getAvailableHabitats();  // Fetch available habitats
        include 'C:\xampp\htdocs\ZooManagementSystem\View\AnimalView\animal_form.php';
        exit();
    }

    // Process the form submission
    public function processForm() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $itemName = $_POST['itemName'];
        $invalidCharsPattern = '/[?@#$^~+\-*\/:]/';
        
        // Validate the 'name', 'subspecies', and 'description' fields
        foreach (['name', 'subspecies', 'description'] as $field) {
            if (preg_match($invalidCharsPattern, $_POST[$field])) {
                $message = "Invalid characters found in $field. Please remove symbols like ? @ # $ ^ ~ + - * / :";
                // Re-render the form with the error message or redirect to an error page
                include 'C:\xampp\htdocs\ZooManagementSystem\View\AnimalView\animal_form.php';
                return;
            }
        }

        // Get inventory ID
        $inventoryId = $this->inventoryModel->getInventoryIdByName($itemName);

        if ($inventoryId) {
            // Collect additional animal details from the form
            $animalDetails = [
                'name' => $_POST['name'],
                'species' => $_POST['species'],
                'subspecies' => $_POST['subspecies'],
                'categories' => $_POST['categories'],
                'age' => $_POST['age'],
                'gender' => $_POST['gender'],
                'date_of_birth' => $_POST['date_of_birth'],
                'avg_lifespan' => $_POST['avg_lifespan'],
                'description' => $_POST['description'],
                'height' => $_POST['height'],
                'weight' => $_POST['weight'],
                'habitat_id' => $_POST['habitat_id']
            ];

            // Add animal details and get the new animal ID
            $success = $this->animalModel->addAnimal($inventoryId, $animalDetails);

            if ($success) {
                $message = "Animal added successfully.";
                $animalId = $this->animalModel->getLastInsertedAnimalId();

            } else {
                $message = "Failed to add animal.";
                $success = null; // Ensure $animalId is set to null if animal addition fails
            }

            // Handle file upload
            if (isset($_FILES['animal_image']) && $_FILES['animal_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['animal_image'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                $maxFileSize = 2 * 1024 * 1024; // 2MB

                // Validate file type by MIME type
                if (!in_array(mime_content_type($file['tmp_name']), $allowedTypes)) {
                    $message = 'Invalid file type.';
                } elseif ($file['size'] > $maxFileSize) {
                    $message = 'File size exceeds limit.';
                } else {
                    // Validate file extension
                    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    if (!in_array($fileExtension, ['jpg', 'jpeg', 'png'])) {
                        $message = 'Invalid file extension.';
                    } else {
                        // Generate a unique file name to prevent overwriting
                        $uniqueFileName = uniqid('animal_', true) . '.' . $fileExtension;
                        $uploadDir = dirname(__DIR__, 2) . '/assests/AnimalImages/';
                        $uploadFilePath = $uploadDir . $uniqueFileName;

                        // Ensure the upload directory exists and is writable
                        if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
                            $message = 'Upload directory is not writable or does not exist.';
                        } else {
                            // Save the file securely
                            if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                                // Save file information in the database
                                $imagePath = '/ZooManagementSystem/assests/AnimalImages/' . $uniqueFileName;
                                if ($animalId) {
                                    $this->animalModel->addAnimalImage($animalId, $imagePath);
                                    $message = "Animal and image added successfully.";
                                } else {
                                    $message = "Image uploaded, but animal could not be added.";
                                }
                            } else {
                                $message = 'Failed to upload image.';
                            }
                        }
                    }
                }
            } elseif ($_FILES['animal_image']['error'] !== UPLOAD_ERR_NO_FILE) {
                $message = 'No file uploaded or upload error.';
            }
        }  else {
            $message = "Invalid item name.";
          }
          include 'C:\xampp\htdocs\ZooManagementSystem\View\AnimalView\animal_list.php';
          exit();
        }
    }
    
      // Display animals with pagination
    public function displayAnimals($category = null, $limit = 10, $offset = 0) {
        if ($category) {
            $animals = $this->animalModel->getAnimalsByCategory($category, $limit, $offset);
        } else {
            $animals = $this->animalModel->getAnimalsByCategory();
        }
        foreach ($animals as &$animal) {
            $animal['image'] = $this->animalModel->getAnimalImage($animal['id']);
        }
        return $animals;
    }
    // Count total animals for pagination
    public function countAnimals($category = null) { 
        return $category ? $this->animalModel->countAnimalsByCategory($category) : $this->animalModel->countAllAnimals();
    }
    
    public function editAnimal() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
        $animalId = $_GET['id'];
        $animal = $this->animalModel->getAnimalById($animalId);
        $animalImage = $this->animalModel->getAnimalImage($animalId); // Get the current animal image
        $availableHabitats = $this->animalModel->getAvailableHabitats();

        if ($animal) {
            include 'C:\xampp\htdocs\ZooManagementSystem\View\AnimalView\animal_edit.php';
        } else {
            echo "Animal not found.";
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $animalId = $_POST['id'];
        
        // Input validation
        $errors = [];
        if (empty($_POST['name']) || !preg_match('/^[a-zA-Z0-9\s]+$/', $_POST['name'])) {
            $errors[] = 'Invalid name. Only alphanumeric characters and spaces are allowed.';
        }
        if (!is_numeric($_POST['age']) || $_POST['age'] < 0) {
            $errors[] = 'Invalid age. Please enter a positive number.';
        }
        if (empty($_POST['species']) || !preg_match('/^[a-zA-Z0-9\s]+$/', $_POST['species'])) {
            $errors[] = 'Invalid species. Only alphanumeric characters and spaces are allowed.';
        }
        if (!empty($_POST['subspecies']) && !preg_match('/^[a-zA-Z0-9\s]+$/', $_POST['subspecies'])) {
            $errors[] = 'Invalid subspecies. Only alphanumeric characters and spaces are allowed.';
        }
        if (!is_numeric($_POST['avg_lifespan']) || $_POST['avg_lifespan'] < 0) {
            $errors[] = 'Invalid average lifespan. Please enter a positive number.';
        }
        if (empty($_POST['height']) || !is_numeric($_POST['height'])) {
            $errors[] = 'Invalid height. Please enter a valid number.';
        }
        if (empty($_POST['weight']) || !is_numeric($_POST['weight'])) {
            $errors[] = 'Invalid weight. Please enter a valid number.';
        }
        if (empty($_POST['categories']) || !in_array($_POST['categories'], ['Mammals', 'Birds', 'Amphibians'])) {
            $errors[] = 'Invalid category selection.';
        }
        if (empty($_POST['date_of_birth']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['date_of_birth'])) {
            $errors[] = 'Invalid date of birth. Please enter a valid date in YYYY-MM-DD format.';
        }

        // If there are errors, show them to the user
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p style='color:red;'>$error</p>";
            }
            // Re-include the form with existing data
            $animal = $this->animalModel->getAnimalById($animalId);
            $availableHabitats = $this->animalModel->getAvailableHabitats();
            include 'C:\xampp\htdocs\ZooManagementSystem\View\AnimalView\animal_edit.php';
            return;
        }

        // If validation passes, update the animal details
        $animalDetails = [
            'name' => $_POST['name'],
            'species' => $_POST['species'],
            'subspecies' => $_POST['subspecies'],
            'categories' => $_POST['categories'],
            'age' => $_POST['age'],
            'gender' => $_POST['gender'],
            'date_of_birth' => $_POST['date_of_birth'],
            'avg_lifespan' => $_POST['avg_lifespan'],
            'description' => $_POST['description'],
            'height' => $_POST['height'],
            'weight' => $_POST['weight'],
            'habitat_id' => $_POST['habitat_id']
        ];

        // Update the animal details in the model
        $success = $this->animalModel->updateAnimal($animalId, $animalDetails);
        $message = $success ? "Animal updated successfully." : "Failed to update animal.";
        include 'C:\xampp\htdocs\ZooManagementSystem\View\AnimalView\animal_list.php';
        exit();
    }
}

    public function deleteAnimal() {
        if (isset($_GET['id'])) {
            $animalId = $_GET['id'];
            $success = $this->animalModel->deleteAnimal($animalId);
            // Prepare the message
            $message = $success ? "Animal deleted successfully." : "Failed to delete animal.";
            header("Location: index.php?controller=animal&action=anilist&message=" . urlencode($message));

            exit();
        } else {
            echo "No animal ID provided.";
        }
    }
}
// Initialize controller
$controller = new AnimalController();

?>
