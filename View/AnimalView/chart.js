document.addEventListener('DOMContentLoaded', function() {
    fetch('category_statistics.php') // URL to the PHP script
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => item.category);
            const counts = data.map(item => item.count);

            const ctx = document.getElementById('myPieChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Animal Categories',
                        data: counts,
                        backgroundColor: ['#366665', '#4c6636', '#6269cc', '#FF5733', '#C70039'],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const label = tooltipItem.label || '';
                                    const value = tooltipItem.raw || 0;
                                    return `${label}: ${value}`;
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching data:', error));
});
