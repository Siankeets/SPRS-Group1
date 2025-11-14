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
    SELECT si.dateRedeemed, r.rewardName, r.rewardDescription, r.rewardType, r.rewardPointsRequired
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
<title>Student Inventory — SPRS</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --accent-1: #93c5fd;
    --accent-2: #3b82f6;
    --glass: rgba(0,0,0,0.40);
    --success: #10b981;
    --transition: 240ms cubic-bezier(.2,.9,.3,1);
}

* { box-sizing: border-box; margin:0; padding:0; }
body {
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: url('images/bg.jpg') no-repeat center center fixed;
    background-size: cover;
    color: #f2f6fb;
}

header {
    position: fixed; top:0; left:0; right:0;
    padding: 10px 20px;
    display: flex; justify-content: space-between; align-items: center;
    background: #1e293b;
    box-shadow: 0 4px 16px rgba(0,0,0,0.5);
    z-index: 100;
}

.brand { display: flex; align-items: center; gap:12px; }
.brand img { width:46px; height:46px; border-radius:8px; object-fit:cover; }
.brand h1 { font-size:16px; font-weight:600; }

.profile-info {
    display:flex; align-items:center; gap:12px;
    background: rgba(255,255,255,0.08);
    padding:6px 14px; border-radius:12px;
}
.profile-info .avatar {
    width:36px; height:36px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-weight:700; font-size:14px;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color:#fff;
}
.profile-info .user-details { display:flex; flex-direction:column; line-height:1.1; }
.profile-info .user-details strong { font-size:14px; }
.profile-info .user-details span { font-size:12px; color:#ccc; }
.profile-info .credits {
    background: linear-gradient(135deg, #10b981, #059669);
    padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600;
    color:#fff;
}

.container { flex:1; padding:100px 20px 20px; display:flex; flex-direction:column; }

.hero {
    display:flex; justify-content:space-between; align-items:center;
    flex-wrap:wrap;
    background: rgba(255,255,255,0.06);
    padding:18px;
    border-radius:12px;
    margin-bottom:20px;
}
.hero h2 { font-size:20px; font-weight:700; }
.hero p { color:#fff; text-shadow: 0 1px 4px rgba(0,0,0,0.6); }
.back-btn {
    background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
    color:#071033; font-weight:700; border:none; padding:10px 18px;
    border-radius:8px; cursor:pointer; transition: var(--transition);
}
.back-btn:hover { transform: translateY(-2px); box-shadow:0 6px 15px rgba(0,0,0,0.4); }

.redeem-section {
    background: rgba(0, 0, 0, 0.35); padding:30px; border-radius:18px;
    backdrop-filter: blur(12px); box-shadow:0 10px 28px rgba(0,0,0,0.45);
    border:1px solid rgba(255,255,255,0.12);
    display:flex; flex-direction:column;
}

.inventory-controls {
    display:flex; flex-wrap:wrap; gap:10px; justify-content:center; margin-bottom:20px;
}
.inventory-controls input, .inventory-controls select {
    padding:8px 12px; border-radius:8px; border:none; font-size:14px;
}

.inventory-container {
    display:flex; flex-wrap:wrap; gap:20px; justify-content:center;
    max-height:500px; /* scrollable area */
    overflow-y:auto;
    padding-right:4px; /* prevent layout shift from scrollbar */
}

.inventory-card {
    background: rgba(0, 0, 0, 0.55); /* darker glass background like redeem page */
    border-radius:16px;
    backdrop-filter: blur(12px); /* same as redeem page */
    box-shadow: 0 6px 18px rgba(0,0,0,0.45); /* slightly stronger shadow */
    width:250px; 
    padding:20px; 
    text-align:center;
    color:white; 
    display:flex; 
    flex-direction:column; 
    justify-content:space-between;
    transition: transform 0.3s, box-shadow 0.3s;
}
.inventory-card:hover { 
    transform: translateY(-5px); 
    box-shadow: 0 10px 28px rgba(0,0,0,0.45); /* match redeem hover effect */
}

.inventory-card img { width:100px; height:100px; object-fit:cover; border-radius:12px; margin:0 auto 10px; }
.inventory-card h3 { margin-bottom:10px; font-weight:600; }
.inventory-card p { font-size:13px; margin-bottom:6px; color:#ccc; }

footer {
    width:100%; background: #1e293b; text-align: center; padding:20px 10px; margin-top:auto;
    display:flex; flex-direction:column; align-items:center; gap:8px;
}
footer .contact { display:flex; gap:12px; flex-wrap:wrap; color:#93c5fd; font-size:13px; justify-content:center; align-items:center; }
footer .contact svg { vertical-align:middle; }

@media(max-width:768px){
    .hero { flex-direction:column; align-items:flex-start; }
    .back-btn { align-self:flex-end; margin-top:10px; }
    .inventory-controls { flex-direction:column; }
}
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
    <section class="redeem-section">
        <div class="hero">
            <div class="info">
                <h2>Your Inventory</h2>
                <p>Check all the rewards you've redeemed below.</p>
            </div>
            <button class="back-btn" onclick="window.location.href='student_index.php'">⬅ Back</button>
        </div>

        <div class="inventory-controls">
            <input type="text" id="searchInventory" placeholder="Search rewards...">
            <select id="sortInventory">
                <option value="date_desc">Sort by Date (Newest)</option>
                <option value="date_asc">Sort by Date (Oldest)</option>
                <option value="points_desc">Sort by Points (High → Low)</option>
                <option value="points_asc">Sort by Points (Low → High)</option>
            </select>
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
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
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
const inventoryCards = Array.from(document.querySelectorAll('.inventory-card'));
const container = document.querySelector('.inventory-container');

document.getElementById('searchInventory').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    inventoryCards.forEach(card => {
        const name = card.querySelector('h3').textContent.toLowerCase();
        const description = card.querySelector('p').textContent.toLowerCase();
        card.style.display = (name.includes(query) || description.includes(query)) ? 'flex' : 'none';
    });
});

document.getElementById('sortInventory').addEventListener('change', function() {
    const value = this.value;
    const sorted = [...inventoryCards].sort((a,b) => {
        const pointsA = parseInt(a.querySelector('p:nth-of-type(2)').textContent.replace('Points: ','')) || 0;
        const pointsB = parseInt(b.querySelector('p:nth-of-type(2)').textContent.replace('Points: ','')) || 0;
        const dateA = new Date(a.querySelector('p:nth-of-type(3)').textContent.replace('Redeemed on: ','')) || 0;
        const dateB = new Date(b.querySelector('p:nth-of-type(3)').textContent.replace('Redeemed on: ','')) || 0;

        switch(value){
            case 'points_asc': return pointsA - pointsB;
            case 'points_desc': return pointsB - pointsA;
            case 'date_asc': return dateA - dateB;
            case 'date_desc': return dateB - dateA;
            default: return 0;
        }
    });
    sorted.forEach(card => container.appendChild(card));
});
</script>

</body>
</html>
