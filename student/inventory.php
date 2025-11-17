<?php
session_start();
include('../db_connect.php');

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$studentID = $_SESSION['userID'];

// Get student info
$conn->select_db('sprs_dummydb');
$stmt = $conn->prepare("SELECT name, points FROM users WHERE id = ?");
$stmt->bind_param("i", $studentID);
$stmt->execute();
$stmt->bind_result($studentName, $credits);
$stmt->fetch();
$stmt->close();

// Get student inventory
$conn->select_db('sprs_mainredo');
$stmt = $conn->prepare("
    SELECT si.dateRedeemed, r.rewardID, r.rewardName, r.rewardDescription, r.rewardType, r.rewardPointsRequired
    FROM student_inventory si
    JOIN rewards r ON si.rewardID = r.rewardID
    WHERE si.studentID = ?
    ORDER BY si.dateRedeemed DESC
");
$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();
$inventory = [];
while ($row = $result->fetch_assoc()) {
    $inventory[] = $row;
}
$stmt->close();

// Get events registered/attended
$conn->select_db('sprs_mainredo');
$stmt = $conn->prepare("
    SELECT se.eventName, se.eventDate,
           (er.registered_at IS NOT NULL) AS registered,
           (ep.attended IS NOT NULL) AS attended
    FROM schoolevents se
    LEFT JOIN event_registrations er 
        ON se.eventID = er.eventID AND er.studentID = ?
    LEFT JOIN eventparticipants ep
        ON se.eventID = ep.eventID AND ep.studentID = ?
    ORDER BY se.eventDate DESC
");
$stmt->bind_param("ii", $studentID, $studentID);
$stmt->execute();
$result = $stmt->get_result();
$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}
$stmt->close();

// Build activity log: redeemed/used rewards + events
$activityLog = [];
foreach ($inventory as $item) {
    $activityLog[] = [
        'type' => 'Reward Redeemed',
        'name' => $item['rewardName'],
        'date' => $item['dateRedeemed']
    ];
}
foreach ($events as $ev) {
    if ($ev['registered']) $activityLog[] = ['type'=>'Event Registered','name'=>$ev['eventName'],'date'=>$ev['eventDate']];
    if ($ev['attended']) $activityLog[] = ['type'=>'Event Attended','name'=>$ev['eventName'],'date'=>$ev['eventDate']];
}

// Sort activity log by date descending
usort($activityLog, function($a,$b){ return strtotime($b['date']) - strtotime($a['date']); });

function getInitials($fullName) {
    $parts = explode(' ', $fullName);
    $firstInitial = strtoupper($parts[0][0]);
    $lastInitial = isset($parts[1]) ? strtoupper($parts[1][0]) : '';
    return $firstInitial . $lastInitial;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Inventory & Events — SPRS</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --accent-1: #93c5fd;
    --accent-2: #3b82f6;
    --success: #10b981;
    --transition: 240ms cubic-bezier(.2,.9,.3,1);
}
* { box-sizing:border-box; margin:0; padding:0; }
body { font-family:'Inter',sans-serif; min-height:100vh; display:flex; flex-direction:column; background:url('images/bg.jpg') no-repeat center center fixed; background-size:cover; color:#f2f6fb; }
header { position:fixed; top:0; left:0; right:0; padding:10px 20px; display:flex; justify-content:space-between; align-items:center; background:#1e293b; box-shadow:0 4px 16px rgba(0,0,0,0.5); z-index:100; }
.brand { display:flex; align-items:center; gap:12px; }
.brand img { width:46px; height:46px; border-radius:8px; object-fit:cover; }
.brand h1 { font-size:16px; font-weight:600; }
.profile-info { display:flex; align-items:center; gap:12px; background: rgba(255,255,255,0.08); padding:6px 14px; border-radius:12px; }
.profile-info .avatar { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; background: linear-gradient(135deg, #3b82f6, #2563eb); color:#fff; }
.profile-info .user-details { display:flex; flex-direction:column; line-height:1.1; }
.profile-info .user-details strong { font-size:14px; }
.profile-info .user-details span { font-size:12px; color:#ccc; }
.profile-info .credits { background: linear-gradient(135deg, #10b981, #059669); padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; color:#fff; }
.container { flex:1; padding:100px 20px 20px; display:flex; gap:20px; }
.main-section { flex:3; display:flex; flex-direction:column; }
.sidebar { flex:1; background: rgba(0,0,0,0.35); border-radius:12px; padding:20px; max-height:600px; overflow-y:auto; }
.sidebar h3 { font-size:16px; font-weight:700; margin-bottom:10px; }
.sidebar ul { list-style:none; }
.sidebar li { padding:6px 0; border-bottom:1px solid rgba(255,255,255,0.15); }

/* Hero and Buttons */
.hero { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; background: rgba(255,255,255,0.06); padding:18px; border-radius:12px; margin-bottom:20px; }
.hero h2 { font-size:20px; font-weight:700; }
.hero p { color:#fff; text-shadow:0 1px 4px rgba(0,0,0,0.6); }
.back-btn { background: linear-gradient(135deg, var(--accent-1), var(--accent-2)); color:#071033; font-weight:700; border:none; padding:10px 18px; border-radius:8px; cursor:pointer; transition: var(--transition); }
.back-btn:hover { transform: translateY(-2px); box-shadow:0 6px 15px rgba(0,0,0,0.4); }

/* Inventory / Rewards */
.redeem-section { background: rgba(0,0,0,0.35); padding:20px; border-radius:12px; backdrop-filter: blur(12px); box-shadow:0 10px 28px rgba(0,0,0,0.45); border:1px solid rgba(255,255,255,0.12); margin-bottom:20px; }
.inventory-container { display:flex; flex-wrap:wrap; gap:20px; justify-content:center; max-height:400px; overflow-y:auto; padding-right:4px; }
.inventory-card { background: rgba(0,0,0,0.55); border-radius:16px; padding:15px; text-align:center; color:#fff; width:220px; display:flex; flex-direction:column; justify-content:space-between; transition: transform 0.3s, box-shadow 0.3s; }
.inventory-card:hover { transform: translateY(-5px); box-shadow: 0 10px 28px rgba(0,0,0,0.45); }
.inventory-card img { width:100px; height:100px; object-fit:cover; border-radius:12px; margin:0 auto 10px; }
.inventory-card h3 { margin-bottom:8px; font-weight:600; }
.inventory-card p { font-size:13px; margin-bottom:6px; color:#ccc; }
.redeem-btn { background: linear-gradient(135deg, #10b981, #059669); color:#fff; border:none; padding:6px 10px; border-radius:8px; font-weight:600; cursor:pointer; transition: var(--transition); }
.redeem-btn:hover { transform: translateY(-2px); box-shadow:0 6px 15px rgba(0,0,0,0.4); }

/* Activity Log */
.log-box { background: rgba(0,0,0,0.25); border-radius:12px; padding:15px; max-height:250px; overflow-y:auto; margin-top:10px; }
.log-box h3 { font-size:16px; font-weight:700; margin-bottom:10px; }
.log-box ul { list-style:none; }
.log-box li { padding:4px 0; border-bottom:1px solid rgba(255,255,255,0.12); font-size:13px; }

/* Custom scrollbar for dark theme */
.inventory-container::-webkit-scrollbar,
.log-box::-webkit-scrollbar,
.sidebar::-webkit-scrollbar {
    width: 8px;
}
.inventory-container::-webkit-scrollbar-track,
.log-box::-webkit-scrollbar-track,
.sidebar::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.05);
    border-radius: 8px;
}
.inventory-container::-webkit-scrollbar-thumb,
.log-box::-webkit-scrollbar-thumb,
.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.25);
    border-radius: 8px;
}
.inventory-container::-webkit-scrollbar-thumb:hover,
.log-box::-webkit-scrollbar-thumb:hover,
.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.35);
}

.use-btn {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color:#fff;
    border:none;
    padding:6px 10px;
    border-radius:8px;
    font-weight:600;
    cursor:pointer;
    transition: var(--transition);
}
.use-btn:hover {
    transform: translateY(-2px);
    box-shadow:0 6px 15px rgba(0,0,0,0.4);
}


/* Footer */
footer { width:100%; background:#1e293b; text-align:center; padding:20px 10px; margin-top:auto; display:flex; flex-direction:column; align-items:center; gap:8px; }
footer .contact { display:flex; gap:12px; flex-wrap:wrap; color:#93c5fd; font-size:13px; justify-content:center; align-items:center; }
footer .contact svg { vertical-align:middle; }

@media(max-width:768px){ .container { flex-direction:column; } .sidebar { max-height:none; } }
</style>
</head>
<body>

<header>
    <div class="brand">
        <img src="images/logorewards.jpg" alt="Logo">
        <h1>Student Point-Reward System</h1>
    </div>
    <div class="profile-info">
        <div class="avatar"><?= getInitials($studentName) ?></div>
        <div class="user-details">
            <strong><?= htmlspecialchars($studentName) ?></strong>
            <span>Student</span>
        </div>
        <div class="credits">Credits: <span id="credits"><?= $credits ?></span></div>
    </div>
</header>

<div class="container">
    <div class="main-section">
        <section class="redeem-section">
            <div class="hero">
                <div class="info">
                    <h2>Your Inventory</h2>
                    <p>Check all the rewards you've redeemed below.</p>
                </div>
                <button class="back-btn" onclick="window.location.href='student_index.php'">⬅ Back</button>
            </div>

            <div class="inventory-container">
                <?php if(empty($inventory)): ?>
                    <p style="color:#ccc; text-align:center; width:100%;">You have not redeemed any rewards yet.</p>
                <?php else: ?>
                    <?php foreach($inventory as $item): 
                        $imgFile = match($item['rewardType']) {
                            'Ticket' => 'pass.png',
                            'Supplies' => 'ntbk.png',
                            'Tshirts' => 'tshirt.png',
                            'IDs' => 'id.png',
                            'Points' => 'points.png',
                            default => 'default.png'
                        };
                    ?>
                    <div class="inventory-card">
                        <h3><?= htmlspecialchars($item['rewardName']) ?></h3>
                        <img src="images/<?= $imgFile ?>" alt="<?= htmlspecialchars($item['rewardName']) ?>">
                        <p><?= htmlspecialchars($item['rewardDescription']) ?></p>
                        <p>Points: <?= $item['rewardPointsRequired'] ?></p>
                        <p>Redeemed on: <?= date("M d, Y", strtotime($item['dateRedeemed'])) ?></p>
                        <button class="use-btn" 
        data-rewardid="<?= $item['rewardID'] ?>" 
        data-name="<?= htmlspecialchars($item['rewardName']) ?>">
    Use
</button>


                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="log-box">
                <h3>Activity Log</h3>
                <ul>
                    <?php foreach($activityLog as $log): ?>
                        <li>[<?= date("M d, Y", strtotime($log['date'])) ?>] <?= $log['type'] ?>: <?= htmlspecialchars($log['name']) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    </div>

    <aside class="sidebar">
        <h3>Your Events</h3>
        <ul>
            <?php if(empty($events)): ?>
                <li>No events found</li>
            <?php else: ?>
                <?php foreach($events as $ev): ?>
                    <li>
                        <?= htmlspecialchars($ev['eventName']) ?> — <?= date("M d, Y", strtotime($ev['eventDate'])) ?>
                        <?php if($ev['attended']): ?> ✅ Attended<?php elseif($ev['registered']): ?> 📝 Registered<?php endif; ?>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </aside>
</div>

<footer>
  <div class="contact">
    📧 sprsystem@gmail.com | 📞 09123456789 |
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#93c5fd" viewBox="0 0 24 24">
      <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 
               3.657 9.128 8.438 9.878v-6.987H8.078v-2.89h2.36V9.797
               c0-2.337 1.393-3.625 3.52-3.625.996 0 2.04.178 2.04.178v2.25
               h-1.151c-1.137 0-1.492.705-1.492 1.43v1.716h2.54l-.406 2.89
               h-2.134V21.9C18.343 21.128 22 16.991 22 12z"/>
    </svg>
    <a href="https://www.facebook.com/StudentPointRewardSystem" target="_blank" style="color:#93c5fd; text-decoration:none;">
      Student Point Reward System
    </a>
  </div>
  <div style="font-size:13px;color:#fff;">© 2025 Student Point-Reward System. All rights reserved.</div>
</footer>
<script>
const useButtons = document.querySelectorAll('.use-btn');

useButtons.forEach(btn => {
    btn.addEventListener('click', async function(){
        const rewardID = this.dataset.rewardid;
        const rewardName = this.dataset.name;

        if(!confirm(`Are you sure you want to use "${rewardName}"?`)) return;

        const originalText = this.innerText;
        this.disabled = true;
        this.innerText = "Using...";

        try {
            const res = await fetch('redeem_reward.php', {
                method: 'POST',
                headers: {'Content-Type':'application/json'},
                body: JSON.stringify({rewardID, action: 'use'})
            });

            const data = await res.json();
            if(data.success){
                // Remove the card from UI
                this.closest('.inventory-card').remove();

                // Update activity log
                const logBox = document.querySelector('.log-box ul');
                const li = document.createElement('li');
                li.textContent = `[${data.date}] Reward Used: ${rewardName}`;
                logBox.prepend(li);

                alert(data.message);
            } else {
                alert(data.message);
                this.disabled = false;
                this.innerText = originalText;
            }
        } catch(e){
            alert("Error using reward.");
            this.disabled = false;
            this.innerText = originalText;
        }
    });
});

</script>

</body>
</html>
