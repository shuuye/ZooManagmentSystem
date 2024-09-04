<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Animal</title>
</head>
<body>
    <form action="index.php?action=processForm" method="post" enctype="multipart/form-data">
        <label for="itemName">Select Item Name:</label>
        
        <select name="itemName" id="itemName">
            <?php foreach ($itemNames as $itemName): ?>
                <option value="<?php echo htmlspecialchars($itemName); ?>"><?php echo htmlspecialchars($itemName); ?></option>
            <?php endforeach; ?>
        </select><br>

        <!-- Add other fields for animal details here -->
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br>

        <label for="species">Species:</label>
        <input type="text" name="species" id="species"><br>

        <label for="subspecies">Subspecies:</label>
        <input type="text" name="subspecies" id="subspecies"><br>

        <label for="categories">Categories:</label>
        <select name="categories" id="categories" required>
            <option value="Mammals">Mammals</option>
            <option value="Birds">Birds</option>
            <option value="Amphibians">Amphibians</option>
        </select><br>

        <label for="age">Age:</label>
        <input type="number" step="1" min="0.5" name="age" id="age"><br>

        <label for="gender">Gender:</label>
        <select name="gender" id="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select><br>

        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" name="date_of_birth" id="date_of_birth" max="<?php echo date('Y-m-d'); ?>" required><br>

        <label for="avg_lifespan">Average Lifespan:</label>
        <input type="number" step="1" min="0.5" name="avg_lifespan" id="avg_lifespan"><br>

        <label for="description">Description:</label>
        <textarea name="description" id="description"></textarea><br>

        <label for="height">Height:</label>
        <input type="number" step="0.1" name="height" min="0.01" id="height"><br>

        <label for="weight">Weight:</label>
        <input type="number" step="0.1" name="weight" min="0.01" id="weight"><br>

        <label for="habitat_id">Select Habitat:</label>
        <select name="habitat_id" id="habitat_id">
            <?php foreach ($availableHabitats as $habitat): ?>
                <option value="<?php echo htmlspecialchars($habitat['habitat_id']); ?>">
                    <?php echo htmlspecialchars($habitat['habitat_name']); ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        
        <label for="animal_image">Upload Animal Image:</label>
        <input type="file" name="animal_image" id="animal_image" accept=".jpg, .jpeg, .png" required><br>
       


        <button type="submit">Submit</button>
    </form>
</body>
</html>
