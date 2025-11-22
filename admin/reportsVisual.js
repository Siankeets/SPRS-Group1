// Generate Pie Chart for Users per Role
function generateReportChart(data) {
    const chartConfig = {
        type: "pie",
        data: {
            labels: data.labels,
            datasets: [{
                label: "Users per Roles",
                data: data.values,
                backgroundColor: [
                    "rgba(75, 192, 192, 0.7)",
                    "rgba(153, 102, 255, 0.7)"
                ]
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: "Total Registered Users by Role"
                },
                legend: { display: false }
            }
        }
    };

    const chartUrl = "https://quickchart.io/chart?c=" + encodeURIComponent(JSON.stringify(chartConfig));

    document.getElementById("reportChart").innerHTML = `
        <img src="${chartUrl}" alt="User Roles Chart" style="width:450px; border-radius:10px;">
    `;
}

// Generate Bar Chart for Points Distribution
function generatePointsDistributionChart(data) {
    const chartConfig = {
        type: "bar",
        data: {
            labels: data.labels,
            datasets: [{
                label: "Points",
                data: data.values,
                backgroundColor: "rgba(54, 162, 235, 0.7)"
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: "Student Points Distribution"
                },
                legend: { display: false }
            },
            scales: {
                x: { title: { display: true, text: "Students" } },
                y: { beginAtZero: true, title: { display: true, text: "Points" } }
            }
        }
    };

    const url = "https://quickchart.io/chart?c=" + encodeURIComponent(JSON.stringify(chartConfig));

    document.getElementById("pointsDistribution").innerHTML = `
        <h2>Points Distribution</h2>
        <img src="${url}" style="width:550px; border-radius:10px;">
    `;
}

// -------------------------------
// INITIAL LOAD
// -------------------------------
document.addEventListener("DOMContentLoaded", () => {
    // Fetch student/admin counts
    Promise.all([
        fetch("reportStudentCount.php").then(res => res.json()),
        fetch("reportAdminCount.php").then(res => res.json())
    ])
    .then(([studentData, adminData]) => {
        const data = {
            labels: ["Students","Admins"],
            values: [studentData.totalStudents, adminData.totalAdmins]
        };
        generateReportChart(data);
    })
    .catch(err => console.error("Error fetching user counts:", err));

    // Fetch student points distribution
    fetch("reportPointsDistribution.php")
        .then(res => res.json())
        .then(data => generatePointsDistributionChart(data))
        .catch(err => console.error("Error fetching points distribution:", err));
});


