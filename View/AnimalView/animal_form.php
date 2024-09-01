<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Animal</title>
</head>
<body>
    <form action="index.php?action=processForm" method="post">
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
        <input type="text" name="categories" id="categories"><br>

        <label for="age">Age:</label>
        <input type="number" name="age" id="age"><br>

        <label for="gender">Gender:</label>
        <input type="text" name="gender" id="gender"><br>

        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" name="date_of_birth" id="date_of_birth"><br>

        <label for="avg_lifespan">Average Lifespan:</label>
        <input type="number" name="avg_lifespan" id="avg_lifespan"><br>

        <label for="description">Description:</label>
        <textarea name="description" id="description"></textarea><br>

        <label for="height">Height:</label>
        <input type="number" step="0.01" name="height" id="height"><br>

        <label for="weight">Weight:</label>
        <input type="number" step="0.01" name="weight" id="weight"><br>

        <label for="habitat_id">Habitat ID:</label>
        <input type="number" name="habitat_id" id="habitat_id"><br>
       


        <button type="submit">Submit</button>
    </form>
</body>
</html>
