window.onload = function () {
    
    // Example static data (replace with DB-driven later)
    const reportData = {
        labels: ["Active Students", "Events", "Rewards Redeemed"],
        values: [120, 14, 89]
    };

    generateReportChart(reportData);
};

function generateReportChart(data) {

    const chartConfig = {
        type: "pie",
        data: {
            labels: data.labels,
            datasets: [{
                label: "System Overview",
                data: data.values,
                backgroundColor: [
                    "rgba(75, 192, 192, 0.7)",
                    "rgba(153, 102, 255, 0.7)",
                    "rgba(255, 159, 64, 0.7)"
                ]
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: "System Report Summary"
                },
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    };

    const chartUrl = "https://quickchart.io/chart?c="+ encodeURIComponent(JSON.stringify(chartConfig));

    document.getElementById("reportChart").innerHTML = `
        <img src="${chartUrl}" alt="Report Chart" style="width:700px;border-radius:10px;">
    `;
}
