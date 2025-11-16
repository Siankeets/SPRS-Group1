window.onload = function () {
    Promise.all([ //multi fetch, theres also async na ginamit already kung saan man sa system di ko tanda.
        fetch("reportStudentCount.php").then(response => response.json()),
        fetch("reportAdminCount.php").then(response => response.json())
    ])

    .then(([studentCount, adminCount]) => {
        console.log(studentCount, adminCount); // Log to ensure the data is correct
        const studentTotal = studentCount.totalStudents;
        const adminTotal = adminCount.totalAdmins;
        
        const reportCount = {
            labels: ["Total Students", "Total Admins"],
            values: [studentTotal, adminTotal]
        };
        console.log(reportCount); // Log to check the data before passing it to the chart
        generateReportChart(reportCount);
    })
    .catch(err => console.error("Data not found",err));
};

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
                    "rgba(153, 102, 255, 0.7)",
                    // "rgba(255, 159, 64, 0.7)" uncomment these when adding more datas (e.g # of distributed points / redeemed rewards)
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