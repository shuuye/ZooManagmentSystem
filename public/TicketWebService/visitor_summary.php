<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Visitor Summary</title>
        <script>
            async function fetchVisitorSummary() {
                const visitDate = document.getElementById('visit_date').value;
                if (!visitDate) {
                    alert('Please enter a visit date.');
                    return;
                }

                try {
                    const response = await fetch(`http://127.0.0.1:5000/visitor_summary?visit_date=${visitDate}`);
                    if (!response.ok) {
                        throw new Error('Failed to fetch visitor summary');
                    }
                    const data = await response.json();

                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    displayVisitorSummary(data);
                } catch (error) {
                    alert('Error fetching data: ' + error.message);
                }
            }

            function displayVisitorSummary(data) {
                const resultDiv = document.getElementById('result');
                resultDiv.innerHTML = `
            <h3>Visitor Summary for ${data.visit_date}</h3>
            <p>Total Visitors: ${data.total_visitors}</p>
            <table border="1">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Visit Date</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.visitors.map(visitor => {
                    const visitDate = new Date(visitor.visit_date).toLocaleDateString('en-GB', {
                        weekday: 'short', // 'Sun'
                        year: 'numeric', // '2024'
                        month: 'short', // 'Sep'
                        day: '2-digit'    // '22'
                    });
                    return `
                            <tr>
                                <td>${visitor.ticket_id}</td>
                                <td>${visitor.type}</td>
                                <td>${visitor.total_quantity}</td>
                                <td>${visitDate}</td>
                            </tr>
                        `;
                }).join('')}
                </tbody>
            </table>
        `;
            }

        </script>
    </head>
    <body>
        <h1>Enter Visit Date to Fetch Visitor Summary</h1>
        <label for="visit_date">Visit Date (YYYY-MM-DD):</label>
        <input type="date" id="visit_date" required>
        <button onclick="fetchVisitorSummary()">Fetch Summary</button>

        <div id="result"></div>
    </body>
</html>
