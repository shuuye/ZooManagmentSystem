<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Habitat</title>
    <link rel="stylesheet" type="text/css" href="../../Css/Animal/habitat.css">
    
    
</head>
<body>
    
     <div class="sidebar">
        <h1>Pam</h1>
        <ul>
          <li><a href="animal_home.php">Animal Management</a></li> 
          <li><a href="habitat_home.php">Habitat Management</a></li>
        </ul>
     </div>
    
    <div class="container">
        <h1>Add New Habitat</h1>
        
        <form action="add_habitat.php" method="POST">
            <div class="form-group">
                <label for="name">Habitat Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="availability">Availability</label>
                <select class="form-control" id="availability" name="availability" required>
                    <option value="Available">Available</option>
                    <option value="Unavailable">Unavailable</option>
                </select>
            </div>
            <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="number" class="form-control" id="capacity" name="capacity" required>
            </div>
            <div class="form-group">
                <label for="environment">Environment</label>
                <select class="form-control" id="environment" name="environment" required>
                <option value="hot">Hot</option>
                <option value="cold">Cold</option>
                <option value="water">Water</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Habitat</button>
        </form>
        
    </div>
</body>
</html>
