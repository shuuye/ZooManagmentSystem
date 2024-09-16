<?php
// Include the FoodManagementController
require_once '../../Control/AnimalControllerN/FoodManagementController.php';

// Create an instance of FoodManagementController
$controller = new FoodManagementController();

// Get all feeding records
$feedingRecords = $controller->getAllFeedingRecords();

// Get animal IDs and food items for the form
$animalIds = $controller->getAnimalIds();
$foods = $controller->getFoodItem();

// Handle form submission
$controller->handleAddfeedingRecordForm();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feeding Reports</title>
    <link rel="stylesheet" type="text/css" href="../../Css/AnimalN/ani_Nav.css">
    <style>
        /* Basic styles for the modal */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px; 
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <nav>
       <ul>
           <li><a href="../../View/AnimalView/animal_home.php">Animal Home</a></li>

       </ul>
   </nav>
<h1>Feeding Reports</h1>
<table>
    <thead>
        <tr>
            <th>Animal ID</th>
            <th>Food ID</th>
            <th>Feeding Time</th>
            <th>Quantity Fed</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($feedingRecords as $record) { ?>
            <tr>
                <td><?php echo htmlspecialchars($record['animal_id']); ?></td>
                <td><?php echo htmlspecialchars($record['food_id']); ?></td>
                <td><?php echo htmlspecialchars($record['feeding_time']); ?></td>
                <td><?php echo htmlspecialchars($record['quantity_fed']); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<!-- Add New Animal Button -->
<button id="addAnimalBtn">Add New Animal</button>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    
    <h1>Animal Feeding Form</h1>
    
    <form action="feeding_report.php"  method="POST">
        
        <input type="hidden" name="form_type" value="feeding_report">
        
        <label for="animal_id">Select an animal ID:</label>
        <select name="animal_id" id="animal_id">
            <?php foreach ($animalIds as $id) { ?>
                <option value="<?php echo htmlspecialchars($id); ?>"><?php echo htmlspecialchars($id); ?></option>
            <?php } ?>
        </select>

        <label for="food_id">Food:</label>
        <select name="food_id" id="food_id">
            <?php foreach ($foods as $food): ?>
                <option value="<?php echo htmlspecialchars($food['id']); ?>"><?php echo htmlspecialchars($food['foodName']); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="feeding_time">Feeding Time:</label>
        <input type="datetime-local" name="feeding_time" id="feeding_time" required>

        <label for="quantity_fed">Quantity Fed:</label>
        <input type="number" step="1" name="quantity_fed" id="quantity_fed" required>

        <button type="submit">Add Feeding Record</button>
    </form>
  </div>

</div>



<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("addAnimalBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>

</body>
</html>
