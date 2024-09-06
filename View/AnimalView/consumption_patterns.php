<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consumption Patterns</title>
</head>
<body>
    <h1>Consumption Patterns</h1>
    <table>
        <thead>
            <tr>
                <th>Consumption ID</th>
                <th>Total Quantity Fed</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($patterns as $pattern): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pattern['consumption_id']); ?></td>
                    <td><?php echo htmlspecialchars($pattern['total_quantity_fed']); ?></td>
                    <td><?php echo htmlspecialchars($pattern['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($pattern['end_date']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
