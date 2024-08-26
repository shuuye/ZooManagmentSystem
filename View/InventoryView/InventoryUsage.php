<!DOCTYPE html>
<html>
    <head>
        <title>Usage Logging Interface</title>
        <link rel="stylesheet" href="../Css/Inventory/InventoryUsage.css">
    </head>
    <body>
        <h1>Usage Logging Interface</h1>
        <form>
            <label for="date-time">Date and Time:</label>
            <input type="datetime-local" id="date-time" name="date-time" value="<?php echo date('Y-m-d H:i:s'); ?>">
            <br><br>
            <label for="animal-assignment">Select Inventory:</label>
            <select id="animal-assignment" name="animal-assignment">
                <option value="">Type or select an Inventory</option>
                <option value="Monkeys">Monkeys</option>
                <option value="Giraffes">Giraffes</option>
                <option value="Lions">Lions</option>
                <!-- Add more options as needed -->
            </select>
            <br><br>
            <label for="quantity-used">Quantity Used:</label>
            <input type="number" id="quantity-used" name="quantity-used" placeholder="Enter quantity">
            <br><br>
            <label for="reason-for-use">Reason for Use:</label>
            <textarea id="reason-for-use" name="reason-for-use" placeholder="Optional: specify the purpose"></textarea>
            <br><br>
            <button type="submit">Submit</button>
        </form>
    </body>
</html>