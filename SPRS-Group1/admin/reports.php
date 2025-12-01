<?php 
header('Content-Type: text/html; charset=utf-8');
session_start();
include ('../db_connect.php');

// --- Ensure staff is logged in ---
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch events for table
$eventsQuery = "
    SELECT e.eventID, e.eventName, e.eventDescription, e.eventRewards, e.rewardType,
           COUNT(DISTINCT r.id) AS registeredCount,
           COUNT(DISTINCT p.id) AS attendedCount
    FROM schoolevents e
    LEFT JOIN event_registrations r ON e.eventID = r.eventID
    LEFT JOIN eventparticipants p ON e.eventID = p.eventID AND p.attended = 1
    GROUP BY e.eventID
    ORDER BY e.eventID DESC
";
$eventsResult = mysqli_query($conn, $eventsQuery);
$events = mysqli_fetch_all($eventsResult, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>System Reports — Staff</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

<style>
:root {
    --accent-blue: #2563eb;
    --accent-hover: #1d4ed8;
}

body {
    font-family: 'Inter', sans-serif;
    background: url('images/bg.jpg') center/cover no-repeat fixed;
    margin: 0;
    color: #fff;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* HEADER MATCH EVENTS.PHP */
header {
    position: fixed;
    top: 0; left: 0; right: 0;
    background: #0f172a;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 6px 20px rgba(3,7,18,0.45);
    z-index: 10;
}
header .left { display: flex; align-items: center; gap: 12px; }
header img { width: 48px; height: 48px; border-radius: 12px; object-fit: cover; }
header h1 { font-size: 18px; font-weight: 700; margin: 0; color: #f2f6fb; }
.back-btn { background: var(--accent-blue); color: #fff; padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 14px; text-decoration: none; transition: 0.2s; }
.back-btn:hover { background: var(--accent-hover); }

/* CONTAINER */
.container {
    max-width: 1100px;
    width: 92%;
    margin: 140px auto 40px;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(10px);
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.4);
    flex: 1;
}

h2 { text-align: center; font-weight: 700; margin-bottom: 25px; }

/* Charts Side-by-Side */
.chart-row {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}
.chart-box {
    flex: 1;
    min-width: 320px;
    max-width: 480px;
    text-align: center;
}

/* Events Table */
.events-scroll {
    max-height: 350px;
    overflow-y: auto;
    margin-top: 20px;
    border-radius: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed; /* fixed layout ensures alignment */
}
th, td {
    padding: 12px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
    word-wrap: break-word;
    vertical-align: middle;
}
th { background: rgba(255,255,255,0.1); font-weight: 600; text-align: left; }
tr:hover { background: rgba(255,255,255,0.06); }

/* Column widths */
th:nth-child(1), td:nth-child(1) { width: 30%; }   /* Event Name */
th:nth-child(2), td:nth-child(2) { width: 50%; }   /* Description */
th:nth-child(3), td:nth-child(3) { width: 20%; }   /* Action */

/* Button */
button.report-btn {
    background: var(--accent-blue);
    padding: 8px 14px;
    border-radius: 8px;
    font-weight: 600;
    color: #fff;
    border: none;
    cursor: pointer;
    width: 100%;
    max-width: 120px;
}
button.report-btn:hover { background: var(--accent-hover); }

/* Modal */
#eventChartModal {
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.85);
    backdrop-filter:blur(6px);
    justify-content:center;
    align-items:center;
    z-index:9999;
}

/* FOOTER MATCH EVENTS.PHP */
footer {
    width: 100%;
    background: #0f172a;
    text-align: center;
    padding: 25px 10px;
    margin-top: auto;
    color: #cbd5e1;
}
footer .contact-row {
    margin-top: 10px;
    color: #93c5fd;
    display: flex;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
}

/* Responsive */
@media (max-width: 768px) {
    header { flex-direction: column; gap: 10px; text-align: center; }
    .container { margin: 160px 15px 40px; }
}
</style>
</head>
<body>

<header>
  <div class="left">
    <img src="images/logorewards.jpg" alt="Logo">
    <h1>System Reports</h1>
  </div>
  <a href="staff_index.php" class="back-btn">⬅ Back to Dashboard</a>
</header>

<div class="container">

  <h2>System Reports Overview</h2>

  <!-- Side-by-side charts -->
  <div class="chart-row">
      <div id="reportChart" class="chart-box"></div>
      <div id="pointsDistribution" class="chart-box"></div>
  </div>

  <!-- Modal -->
  <div id="eventChartModal">
      <div style="background:#111; padding:20px; border-radius:12px; text-align:center; width:90%; max-width:480px;">
          <h2 id="modalTitle"></h2>
          <img id="eventChartImage" src="" style="width:100%; border-radius:10px;">
          <br><br>
          <button class="back-btn" onclick="closeEventChart()">Close</button>      </div>
  </div>

<!-- Points Report Modal -->
<div id="eventPointsModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(0,0,0,0.85); backdrop-filter:blur(6px); justify-content:center; align-items:center; z-index:9999;">
    <div style="background:#111; padding:20px; border-radius:12px; text-align:left; width:90%; max-width:480px; color:white;">
        <h2 id="pointsModalTitle"></h2>
        <div id="eventPointsContent" style="margin-top:10px;"></div>
        <br>
        <button class="back-btn" onclick="closePointsModal()">Close</button>
    </div>
</div>

<!-- Registered Students Modal -->
<div id="registeredModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(0,0,0,0.85); backdrop-filter:blur(6px); justify-content:center; align-items:center; z-index:9999;">
    
    <div style="background:#111; padding:20px; border-radius:12px; width:90%; max-width:600px; color:white;">
        <h2>Registered Students</h2>

        <div id="registeredModalBody" style="margin-top:10px;"></div>

        <br>
        <button class="back-btn" onclick="document.getElementById('registeredModal').style.display='none'">
            Close
        </button>
    </div>
</div>


  <h2>Events Overview</h2>

  <div class="events-scroll">
      <table>
          <thead>
              <tr>
                  <th>Event Name</th>
                  <th>Description</th>
                  <th>Action</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach($events as $e): ?>
              <tr>
                  <td><?= htmlspecialchars($e['eventName']) ?></td>
                  <td><?= htmlspecialchars($e['eventDescription']) ?></td>
                  <td>
                      <button class="report-btn"
                        onclick="showEventChart(
                            '<?= $e['eventName'] ?>',
                            <?= $e['registeredCount'] ?>,
                            <?= $e['attendedCount'] ?>
                        )">
                          📊 View Chart
                      </button>
						
				      <button class="report-btn" 
        				onclick="showEventPoints( 
            				<?= $e['eventID'] ?>,
            				'<?= htmlspecialchars($e['eventName']) ?>'
        				)">
        					📊 View Details
    				  </button>
					
					  <button class="report-btn"
    					onclick="showRegisteredStudents(<?= $e['eventID'] ?>)">
    						📝 View Registered
					  </button>
                  </td>
              </tr>
              <?php endforeach; ?>
          </tbody>
      </table>
  </div>

</div>

<footer>
    <div style="font-weight:700; font-size:16px;">Contact Us:</div>
    <div class="contact-row">
        <div>📧 sprsystem@gmail.com</div>
        <span>|</span>
        <div>📞 09123456789</div>
    </div>
    <div style="margin-top:10px;">© 2025 Student Point-Reward System. All rights reserved.</div>
</footer>

<script src="reportsVisual.js"></script> 

<script>
// Event Modal Chart
function showEventChart(eventName, registered, attended) {
    const chartConfig = {
        type: "pie",
        data: {
            labels: [
                "Registered (" + registered + ")", 
                "Attended (" + attended + ")"
            ],
            datasets: [{
                data: [registered, attended],
                backgroundColor: [
                    "rgba(54,162,235,0.7)",
                    "rgba(75,192,192,0.7)"
                ]
            }]
        },
        options: {
            plugins: {
                title: { display: true, text: eventName }
            }
        }
    };
    const chartUrl = "https://quickchart.io/chart?c=" + encodeURIComponent(JSON.stringify(chartConfig));
    document.getElementById("modalTitle").innerText = eventName;
    document.getElementById("eventChartImage").src = chartUrl;
    document.getElementById("eventChartModal").style.display = "flex";
}

function closeEventChart() {
    document.getElementById("eventChartModal").style.display = "none";
}

async function showEventPoints(eventID, eventName) { //It works, list attended/attending students
    try {
        const res = await fetch(`detailedEventReport.php?eventID=${eventID}`);
        const data = await res.json();

        if (!data.success) {
            alert("Error: " + data.message);
            return;
        }

        // Build a simple table for participants + points
        let participantsHtml = "<table style='width:100%; border-collapse:collapse;'>";
        participantsHtml += "<tr><th>Student Name</th><th>Points Gained</th></tr>";
        data.participants.forEach(p => {
            participantsHtml += `<tr>
                <td>${p.studentName}</td>
                <td>${p.pointsGained}</td>
            </tr>`;
        });
        participantsHtml += "</table>";

        // Fill modal
        document.getElementById("pointsModalTitle").innerText = 
            `${data.eventName} — Total Points Distributed: ${data.totalDistributedPoints}`;
        document.getElementById("eventPointsContent").innerHTML = participantsHtml;

        // Show modal
        document.getElementById("eventPointsModal").style.display = "flex";

    } catch (err) {
        console.error("Error fetching event report:", err);
    }
}

function closePointsModal() {
    document.getElementById("eventPointsModal").style.display = "none";
}

async function showRegisteredStudents(eventID) { //list registered students.
    try {
        const res = await fetch(`getRegisteredStudents.php?eventID=${eventID}`);
        const data = await res.json();

        if (!data.success) {
            alert("Failed: " + data.message);
            return;
        }

        const list = data.registered
            .map(s => `<tr>
                <td>${s.id}</td>
                <td>${s.name}</td>
                <td>${s.program}</td>
                <td>${s.department}</td>
            </tr>`)
            .join("");

        document.getElementById("registeredModalBody").innerHTML = `
            <h3>Registered Students (${data.registeredCount})</h3>

            <table style="width:100%; border-collapse:collapse; table-layout:fixed;">
                <thead>
                    <tr>
                        <th style="width:15%;">ID</th>
                        <th style="width:30%;">Name</th>
                        <th style="width:25%;">Program</th>
                        <th style="width:30%;">Department</th>
                    </tr>
                </thead>
                <tbody>${list}</tbody>
            </table>
        `;

        document.getElementById("registeredModal").style.display = "block";
    }
    catch(e) {
        console.error(e);
        alert("Error fetching data");
    }
}





// Auto-load charts
renderPointsChart();
renderStudentAdminChart();
</script>
</body>
</html>
