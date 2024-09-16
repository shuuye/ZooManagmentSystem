<?php

include_once '../../Model/ObserverN/AnimalModel.php';
require_once '../../Model/Inventory/InventoryModel.php';

class AnimalController extends InventoryModel{

    private $animalModel;
    private $inventoryModel;

    public function __construct() {
        $this->animalModel = new AnimalModel();
        $this->inventoryModel = new InventoryModel();

    }
    
   public function route(){
        $action = isset($_GET['action']) ? $_GET['action'] : 'home';
        
         ob_start();
        switch ($action) {
            case 'home' :
                include '../../View/AnimalView/animal_home.php';
                break;
//            case 'displayAnimals':
//                include '../../View/AnimalView/animal_list.php';
//                exit();
//                break;
            case 'showForm':
                $this->showForm();
                break;
            case 'processForm':
                $this->processForm();
                break;
            case 'edit':
                $this->editAnimal();
                break;
            case 'update':
                $this->updateAnimal();
                break;
            case 'delete':
                $this->deleteAnimal();
                break;
            default:
                echo "Invalid action.";
                break;
        }
         ob_end_flush(); // Send output to browser and end buffering
    }

    // Show the form to select item names and add animal details
    public function showForm() {
        // Log or output information to verify it's being called
        error_log("showForm called");
        $itemNames = $this->inventoryModel->getAnimalItemNames();
        $availableHabitats = $this->animalModel->getAvailableHabitats();  // Fetch available habitats
        include '../../View/AnimalView/animal_form.php'; // Pass the item names to the view
        exit();
    }

    // Process the form submission
    public function processForm() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $itemName = $_POST['itemName'];

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
                $allowedTypes = ['image/jpeg', 'image/png','image/jpg'];
                $maxFileSize = 2 * 1024 * 1024; // 2MB

                // Validate file type by MIME type
                if (!in_array($file['type'], $allowedTypes)) {
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
        } else {
            $message = "Invalid item name.";
          }
            include '../../View/AnimalView/animal_result.php'; // Show the result to the user
          exit();
        }
    }
    
    public function displayAnimals($category = null) {
        if ($category) {
            $animals = $this->animalModel->getAnimalsByCategory($category);
        } else {
            $animals = $this->animalModel->getAnimalsByCategory(); //  all if no category is provided
        }
        // Loop through each animal and get the associated image
        foreach ($animals as &$animal) {
            // Get the image for each animal
            $animal['image'] = $this->animalModel->getAnimalImage($animal['id']);
        }
        return $animals;
    }
    
    public function editAnimal() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
        $animalId = $_GET['id'];
        $animal = $this->animalModel->getAnimalById($animalId);
        $animalImage = $this->animalModel->getAnimalImage($animalId); // Get the current animal image
        $availableHabitats = $this->animalModel->getAvailableHabitats();

        if ($animal) {
            include '../../View/AnimalView/animal_edit.php';
        } else {
            echo "Animal not found.";
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $animalId = $_POST['id'];
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

        // Handle the image upload if a new image was provided
        if (isset($_FILES['animal_image']) && $_FILES['animal_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['animal_image'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            $maxFileSize = 2 * 1024 * 1024; // 2MB

            // Validate file type by MIME type
            if (!in_array($file['type'], $allowedTypes)) {
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
                    $uploadDir = dirname(__DIR__, 2) . '/assets/AnimalImages/';
                    $uploadFilePath = $uploadDir . $uniqueFileName;

                    // Ensure the upload directory exists and is writable
                    if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
                        $message = 'Upload directory is not writable or does not exist.';
                    } else {
                        // Save the file securely
                        if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                            // Save the image path in the database
                            $imagePath = '/ZooManagementSystem/assets/AnimalImages/' . $uniqueFileName;
                            $this->animalModel->updateAnimalImage($animalId, $imagePath);
                            $message .= " Image updated successfully.";
                        } else {
                            $message = 'Failed to upload image.';
                        }
                    }
                }
            }
        }

        include '../../View/AnimalView/animal_result.php';
        exit();
    }
 }

    public function deleteAnimal() {
        if (isset($_GET['id'])) {
            $animalId = $_GET['id'];
            $success = $this->animalModel->deleteAnimal($animalId);

            // Prepare the message
            $message = $success ? "Animal deleted successfully." : "Failed to delete animal.";

            // Redirect to the animal list page with a message
            header("Location: animal_list.php?message=" . urlencode($message));
            exit();
        } else {
            echo "No animal ID provided.";
        }
    }


}
// Initialize controller
$controller = new AnimalController();

?>
