
function initCharts(data) {
    new Chart(document.getElementById('categoryChart'), {
        type: 'bar',
        data: {
            labels: data.categories,
            datasets: [{
                label: 'Messages',
                data: data.categoryCounts,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } }
        }
    });

    new Chart(document.getElementById('urgentChart'), {
        type: 'doughnut',
        data: {
            labels: ['Urgent', 'Normal'],
            datasets: [{
                data: [data.urgent, data.normal],
                backgroundColor: ['#e74c3c', '#2ecc71'],
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    new Chart(document.getElementById('assessmentChart'), {
        type: 'line',
        data: {
            labels: data.assessmentTitles,
            datasets: [{
                label: 'Attempts',
                data: data.assessmentCounts,
                borderColor: '#9b59b6',
                backgroundColor: 'rgba(155, 89, 182, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}