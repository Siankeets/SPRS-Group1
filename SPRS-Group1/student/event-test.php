<?php
session_start();
date_default_timezone_set('Asia/Manila'); // INFINITY FREE IS USING UTC, MAKES TESTING HARDER.
include('../db_connect.php'); // DB connection

// Helper function: get today's date in Manila
function todayDate() {
    return date('Y-m-d');
}

// Helper function: get current datetime in Manila (for registration timestamps)
function nowDateTime() {
    return date('Y-m-d H:i:s');
}

// --- Ensure student is logged in ---
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

$studentID = $_SESSION['userID'];
$fullName   = $_SESSION['name'] ?? 'Student';
$points     = $_SESSION['points'] ?? 0;

// --- Generate initials for avatar ---
$names = explode(' ', $fullName);
$initials = '';
foreach ($names as $n) {
    if ($n === '') continue;
    $initials .= strtoupper($n[0]);
    if (strlen($initials) >= 2) break;
}

// --- Fetch events with registration and attendance info ---
$query = "
    SELECT e.eventID, e.eventName, e.eventDescription, e.eventRewards, e.rewardType, e.eventDate,
           COUNT(DISTINCT r.id) AS totalRegistered,
           COUNT(DISTINCT p.id) AS totalAttended,
           IF(er.id IS NULL, 0, 1) AS eventRegistered,
           IF(ep.attended IS NULL, 0, ep.attended) AS attended
    
	FROM schoolevents e
    LEFT JOIN event_registrations r 
	ON e.eventID = r.eventID
	LEFT JOIN eventparticipants p 
	ON e.eventID = p.eventID AND p.attended = 1
	LEFT JOIN event_registrations er 
	ON e.eventID = er.eventID AND er.studentID = ?
	LEFT JOIN eventparticipants ep 
	ON e.eventID = ep.eventID AND ep.id = ? 
    
	GROUP BY e.eventID
    ORDER BY e.eventID ASC
";

$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "ii", $studentID, $studentID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$events = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['totalRegistered'] = (int)$row['totalRegistered'];
    $row['totalAttended'] = (int)$row['totalAttended'];
    $row['eventRegistered'] = (int)$row['eventRegistered'];
    $row['attended'] = (int)$row['attended'];
    $events[] = $row;
}

// --- Format date ---
function formatEventDate($dateStr) {
    if (!$dateStr) return '';
    return date('M d, Y', strtotime($dateStr)); // e.g., Nov 17, 2025
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Student Point-Reward System — Events</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --accent-ticket: #3b82f6;
    --accent-supplies: #22c55e;
    --accent-tshirts: #f59e0b;
    --accent-ids: #a855f7;
    --accent-points: #ef4444;
}
* { box-sizing: border-box; }
body { font-family:'Inter', system-ui; background:url('images/bg.jpg') no-repeat center center fixed; background-size:cover; color:#f2f6fb; margin:0; padding-top:80px; display:flex; flex-direction:column; }
header { position:fixed; top:0; left:0; right:0; display:flex; justify-content:space-between; align-items:center; background:#1e293b; padding:8px 18px; box-shadow:0 4px 16px rgba(3,7,18,0.4); z-index:100; flex-wrap:wrap; row-gap:8px;}
.brand { display:flex; gap:14px; align-items:center; flex-wrap:wrap;}
.logo img { width:46px; height:46px; border-radius:8px; object-fit:cover; }
.title-wrap h1 { margin:0; font-weight:600; font-size:16px; }
.profile-info { display:flex; align-items:center; gap:12px; background:rgba(255,255,255,0.08); padding:8px 16px; border-radius:12px; box-shadow:0 2px 6px rgba(0,0,0,0.3);}
.avatar { background: linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; width:36px;height:36px; display:flex; align-items:center; justify-content:center; border-radius:50%; font-weight:700; font-size:14px; }
.user-details { display:flex; flex-direction:column; line-height:1.1; }
.user-details strong { font-size:14px; }
.user-details span { font-size:12px; color:#ccc; }
.credits { background: linear-gradient(135deg,#10b981,#059669); color:#fff; font-weight:600; padding:4px 10px; border-radius:10px; font-size:12px; }

.container { flex:1; padding:20px 18px 20px; display:flex; flex-direction:column; }
.content { padding:14px; border-radius:12px; background:rgba(0,0,0,0.4); border:1px solid rgba(255,255,255,0.04); box-shadow:0 6px 18px rgba(3,7,18,0.45); position:relative;}
.hero { display:flex; align-items:center; justify-content:space-between; padding:18px; border-radius:12px; border:1px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.06); flex-wrap:wrap; row-gap:10px;}
.hero h2 { margin:0; font-size:20px; font-weight:700; }
.hero p { margin:6px 0 0; color:#fff; text-shadow:0 1px 4px rgba(2,6,23,0.6);}
.hero .back-btn { background: linear-gradient(135deg,#93c5fd,#3b82f6); color:#071033; font-weight:700; border:none; border-radius:8px; padding:10px 18px; cursor:pointer; transition:0.25s; box-shadow:0 4px 12px rgba(2,6,23,0.35);}
.hero .back-btn:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(2,6,23,0.45);}

.redeem-section { margin-top:30px; background: rgba(255,255,255,0.08); border-radius:18px; padding:30px; backdrop-filter: blur(12px); box-shadow:0 10px 28px rgba(0,0,0,0.45); border:1px solid rgba(255,255,255,0.12); text-align:center;}
.option-list {
    list-style:none;
    padding:0;
    margin:20px auto;
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    gap:25px;
    max-height:500px;
    overflow-y:auto;
    padding-right:10px;
}
.option-list::-webkit-scrollbar { width: 8px; }
.option-list::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.3); border-radius: 6px; }
.option-list::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); border-radius: 6px; }
.option-item { background: rgba(255,255,255,0.15); border-radius:16px; padding:25px 20px; cursor:pointer; transition:0.25s; color:#fff; display:flex; flex-direction:column; align-items:center; width:220px; text-align:center; font-size:16px; font-weight:600; box-shadow:0 6px 18px rgba(0,0,0,0.35);}
.option-item:hover { background: rgba(255,255,255,0.25); transform:translateY(-5px); box-shadow:0 10px 28px rgba(0,0,0,0.45); }
.reward-type { display:inline-block; padding:4px 10px; border-radius:12px; color:#fff; font-size:13px; font-weight:600; margin:6px 0; }

.btn-register, .btn-back { font-weight:700; border:none; border-radius:12px; padding:12px 24px; font-size:16px; cursor:pointer; transition:all 0.3s ease; margin-top:12px; }
.btn-register { background: linear-gradient(135deg, #3b82f6, #60a5fa); color:#fff; box-shadow:0 4px 12px rgba(59,130,246,0.4); }
.btn-register:hover { transform:translateY(-3px); box-shadow:0 8px 20px rgba(59,130,246,0.45); }
.btn-back { background: linear-gradient(135deg, #f97316, #fb923c); color:#fff; box-shadow:0 4px 12px rgba(249,115,22,0.4); }
.btn-back:hover { transform:translateY(-3px); box-shadow:0 8px 20px rgba(249,115,22,0.45); }

footer { width:100%; background:#1e293b; text-align:center; padding:20px 10px; margin-top:auto; }

@media(max-width:768px){ header{flex-direction:column;text-align:center;gap:6px;padding-bottom:12px;} body{padding-top:130px;} .hero{flex-direction:column;align-items:flex-start;} .hero .back-btn{align-self:flex-end;margin-top:10px;} }
</style>
</head>
<body>
<header>
  <div class="brand">
    <div class="logo"><img src="images/logorewards.jpg" alt="SPRS Logo"></div>
    <div class="title-wrap"><h1>Student Point-Reward System</h1></div>
  </div>
  <div class="profile-info">
    <div class="avatar"><?= htmlspecialchars($initials) ?></div>
    <div class="user-details">
      <strong><?= htmlspecialchars($fullName) ?></strong>
      <span>Student</span>
    </div>
    <div class="credits">Points: <span id="headerCredits"><?= htmlspecialchars($points) ?></span></div>
  </div>
</header>

<div class="container">
  <section class="content">
    <div class="hero">
      <div class="info">
        <h2>Event Attendance</h2>
        <p>Select an event to register or mark attendance.</p>
      </div>
      <button class="back-btn" onclick="window.location.href='student_index-test.php'">⬅ Back to Dashboard</button>
    </div>

    <div class="redeem-section">
        <h3>Choose an Event</h3>
        <ul class="option-list">
        <?php if(empty($events)): ?>
            <p>No events available at this time.</p>
        <?php else: ?>
            <?php foreach($events as $e):
                $typeColors = [
                    'Ticket' => '#3b82f6',
                    'Supplies' => '#22c55e',
                    'Tshirts' => '#f59e0b',
                    'IDs' => '#a855f7',
                    'Points' => '#ef4444'
                ];
                $color = $typeColors[$e['rewardType']] ?? '#64748b';
                $formattedDate = formatEventDate($e['eventDate']);
            ?>
                <li class="option-item" onclick="chooseEvent(
                    <?= (int)$e['eventID'] ?>,
                    '<?= addslashes($e['eventName']) ?>',
                    <?= (int)$e['eventRegistered'] ?>,
                    <?= (int)$e['attended'] ?>,
                    '<?= addslashes($e['eventDescription']) ?>',
                    <?= (int)$e['totalRegistered'] ?>,
                    <?= (int)$e['totalAttended'] ?>,
                    '<?= addslashes($e['rewardType']) ?>',
                    '<?= $formattedDate ?>'
                )">
                    <strong><?= htmlspecialchars($e['eventName']) ?></strong>
                    <div class="reward-type" style="background: <?= $color ?>;"><?= htmlspecialchars($e['rewardType']) ?></div>
                    <p style="font-size:14px; margin:6px 0; color:#e0e0e0;"><?= htmlspecialchars($e['eventDescription']) ?></p>
                    <p style="font-size:13px; margin:2px 0; color:#fbbf24;"><strong>Date:</strong> <?= $formattedDate ?></p>
                    <?= htmlspecialchars($e['eventRewards']) ?: '0 Points' ?><br>
                    Registered: <?= (int)$e['totalRegistered'] ?> | Attended: <?= (int)$e['totalAttended'] ?>
                    <?php
$today = todayDate();
$eventDay = date('Y-m-d', strtotime($e['eventDate']));
?>

<?php if ($e['eventRegistered'] && !$e['attended']): ?>
    
    <?php if ($today < $eventDay): ?>
        <p style="color:#f87171; font-weight:bold; margin-top:10px;">
            🚫 Event hasn’t started yet. Come back on <?= formatEventDate($e['eventDate']) ?>.
        </p>

    <?php else: ?>
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode('https://'.($_SERVER['HTTP_HOST'] ?? 'yourdomain.com').dirname($_SERVER['PHP_SELF']).'/mark_attendance-test.php?eventID='.$e['eventID']) ?>" 
             alt="Scan to mark attendance" style="margin-top:10px;">
        <p>Scan this QR to mark your attendance ✅</p>
    <?php endif; ?>

<!-- DOWNLOAD QR BUTTON -->
<?php if ($e['eventRegistered']): ?>
    <button 
        onclick="event.stopPropagation(); downloadQR(<?= $e['eventID'] ?>);" 
        style="margin-top:10px; padding:8px 12px; border:none; background:#3b82f6; color:white; border-radius:8px; cursor:pointer;">
        ⬇ Download QR
    </button>
<?php endif; ?>

<?php endif; ?>

                </li>
            <?php endforeach; ?>
        <?php endif; ?>
        </ul>
    </div>
  </section>
</div>

<footer>
  <div style="font-weight:700; font-size:16px; margin-bottom:12px;">Contact Us:</div>
  <div style="font-size:13px; display:flex; justify-content:center; gap:12px; flex-wrap:wrap; align-items:center; margin-bottom:12px; color:#93c5fd;">
    <div>📧 sprsystem@gmail.com</div>
    <span style="color:#ccc;">|</span>
    <div>📞 09123456789</div>
  </div>
  <div style="font-size:13px;color:#fff;">© 2025 Student Point-Reward System. All rights reserved.</div>
</footer>

<script>
function chooseEvent(eventID, eventName, eventRegistered, attended, eventDescription, totalRegistered, totalAttended, rewardType, eventDate) {
    const flow = document.querySelector('.redeem-section');
    const typeColors = {
        'Ticket':'#3b82f6',
        'Supplies':'#22c55e',
        'Tshirts':'#f59e0b',
        'IDs':'#a855f7',
        'Points':'#ef4444'
    };
    let color = typeColors[rewardType] || '#64748b';
    let html = `<h3>${eventName}</h3>
                <div class="reward-type" style="background:${color};">${rewardType}</div>
                <p>${eventDescription}</p>
                <p><strong>Date:</strong> ${eventDate}</p>
                <p><strong>Registered:</strong> ${totalRegistered}</p>
                <p><strong>Attended:</strong> ${totalAttended}</p>`;

    const today = new Date().toISOString().split('T')[0];
const eventDay = new Date(eventDate).toISOString().split('T')[0];

if (eventRegistered == 0) {
    html += `<button class="btn-register" onclick="registerEvent(${eventID}, '${eventName}')">📝 Register</button>`;
} else {
    html += `<p>You are already registered ✅</p>`;

    if (attended == 0) {

        if (today < eventDay) {
            html += `<p style="color:#f87171; font-weight:bold;">🚫 Event hasn't started yet.</p>`;
        } else {
            html += `<p>Scan the QR below to mark attendance</p>`;
            html += `<div style="margin-top:10px;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=${
                            encodeURIComponent(location.origin + '${location.pathname}'.replace(/\/[^/]*$/,'') + '/mark_attendance-test.php?eventID=' + eventID)
                        }">
                     </div>`;
        }

    } else {
        html += `<p>You have attended this event ✔️</p>`;
    }
}


    html += `<button class="btn-back" onclick="window.location.reload()">❌ Back</button>`;
    flow.innerHTML = html;
}

async function registerEvent(eventID, eventName){
    try {
        const fd = new FormData();
        fd.append('eventID', eventID);
        const res = await fetch('register_events.php', {method:'POST', body: fd});
        const data = await res.json();
        alert(data.message || 'Registered successfully!');
        if(data.success) window.location.reload();
    } catch(err){
        console.error(err);
        alert('Failed to register.');
    }
}

// gpt ver dlQR
function downloadQR(eventID){
    const qrURL = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${
        encodeURIComponent(location.origin + location.pathname.replace(/\/[^/]*$/, '') + '/mark_attendance-test.php?eventID=' + eventID)
    }`;

    const link = document.createElement("a");
    link.href = qrURL;
    link.download = `event_${eventID}_QR.png`;
    document.body.appendChild(link);
    link.click();
    link.remove();
}

</script>
</body>
</html>
