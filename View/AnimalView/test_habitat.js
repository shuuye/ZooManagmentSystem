import fetch from 'node-fetch';

// URL of the API endpoint
const url = 'http://localhost/your_project/public/api/habitats.php';

// Fetch all habitats
fetch(url)
    .then(response => response.json())
    .then(data => {
        console.log("All Habitats:");
        console.log(data);
        
        // Fetch a specific habitat by ID
        const habitatId = 1;
        return fetch(`${url}?habitat_id=${habitatId}`);
    })
    .then(response => response.json())
    .then(data => {
        console.log(`\nHabitat with ID 1:`);
        console.log(data);
    })
    .catch(error => console.error('Error:', error));
    
    // Web Service 2 
