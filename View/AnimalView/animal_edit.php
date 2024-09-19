<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Animal</title>
    <link rel="stylesheet" type="text/css" href="/ZooManagementSystem/Css/AnimalN/ani_Nav.css">
    <link rel="stylesheet" type="text/css" href="/ZooManagementSystem/Css/AnimalN/animal_form.css">
</head>
<body>
    
    <nav>
        <ul>
            <li><a href="index.php?controller=animal&action=anilist">Back</a></li>
        </ul>
    </nav>
    
    <h1>Edit Animal Details</h1>
    <div id="formedit">
        <?php if ($animal): ?>
       <form method="POST"action="index.php?controller=animal&action=edit&id=<?php echo htmlspecialchars($animal['id']); ?>"  enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($animal['id']); ?>">

                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($animal['name']); ?>" required><br><br>

                <label for="species">Species:</label>
                <input type="text" id="species" name="species" value="<?php echo htmlspecialchars($animal['species']); ?>" readonly required><br><br>

                <label for="subspecies">Subspecies:</label>
                <input type="text" id="subspecies" name="subspecies" value="<?php echo htmlspecialchars($animal['subspecies']); ?>"><br><br>

              <label for="categories">Categories:</label>
              <select name="categories" id="categories" required>
                <option value="Mammals" <?php if ($animal['categories'] == 'Mammals') echo 'selected'; ?>>Mammals</option>
                <option value="Birds" <?php if ($animal['categories'] == 'Birds') echo 'selected'; ?>>Birds</option>
                <option value="Amphibians" <?php if ($animal['categories'] == 'Amphibians') echo 'selected'; ?>>Amphibians</option>
              </select><br><br>


                <label for="age">Age:</label>
                <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($animal['age']); ?>" required><br><br>

                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="Male" <?php echo $animal['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo $animal['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                </select><br><br>

                <label for="date_of_birth">Date of Birth:</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($animal['date_of_birth']); ?>" max="<?php echo date('Y-m-d'); ?>" required><br><br>

                <label for="avg_lifespan">Average Lifespan:</label>
                <input type="number" id="avg_lifespan" name="avg_lifespan" value="<?php echo htmlspecialchars($animal['avg_lifespan']); ?>" required><br><br>

                <label for="description">Description:</label><br>
                <textarea id="description" name="description" rows="4" cols="50"><?php echo htmlspecialchars($animal['description']); ?></textarea><br><br>

                <label for="height">Height:</label>
                <input type="text" id="height" name="height" value="<?php echo htmlspecialchars($animal['height']); ?>" required><br><br>

                <label for="weight">Weight:</label>
                <input type="text" id="weight" name="weight" value="<?php echo htmlspecialchars($animal['weight']); ?>" required><br><br>

               <label for="habitat_id">Select Habitat:</label>
                <select name="habitat_id" id="habitat_id" required>
                    <?php foreach ($availableHabitats as $habitat): ?>
                        <option value="<?php echo htmlspecialchars($habitat['habitat_id']); ?>"
                            <?php if ($animal['habitat_id'] == $habitat['habitat_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($habitat['habitat_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br><br>

                <input type="submit" value="Update Animal Details">
            </form>
        <?php else: ?>
            <p>Animal not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
