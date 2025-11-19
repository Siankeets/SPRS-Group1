<?php
session_start();
include('../db_connect.php'); // DB connection

// --- Ensure student is logged in ---
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$studentID = $_SESSION['userID'];

// --- Handle AJAX redemption ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    $rewardID = intval($data['rewardID'] ?? 0);
    $pointsRequired = intval($data['pointsRequired'] ?? 0);

    if (!$rewardID || !$pointsRequired) {
        echo json_encode(['success'=>false,'message'=>'Invalid request']);
        exit;
    }

    // Get student's current points
    $conn->select_db('sprs_dummydb');
    $stmt = $conn->prepare("SELECT points FROM users WHERE id=?");
    $stmt->bind_param("i", $studentID);
    $stmt->execute();
    $stmt->bind_result($currentPoints);
    $stmt->fetch();
    $stmt->close();

    if ($currentPoints < $pointsRequired) {
        echo json_encode(['success'=>false,'message'=>'Not enough points']);
        exit;
    }

    // Deduct points
    $newPoints = $currentPoints - $pointsRequired;
    $stmt = $conn->prepare("UPDATE users SET points=? WHERE id=?");
    $stmt->bind_param("ii", $newPoints, $studentID);
    $stmt->execute();
    $stmt->close();

    // Get reward info
    $conn->select_db('sprs_mainredo');
    $stmt = $conn->prepare("SELECT rewardName, rewardType FROM rewards WHERE rewardID=?");
    $stmt->bind_param("i", $rewardID);
    $stmt->execute();
    $stmt->bind_result($rewardName, $rewardType);
    if (!$stmt->fetch()) {
        echo json_encode(['success'=>false,'message'=>'Reward not found']);
        exit;
    }
    $stmt->close();

    // Insert into student_inventory
    $stmt = $conn->prepare("INSERT INTO student_inventory (studentID, rewardID, dateRedeemed) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $studentID, $rewardID);
    $stmt->execute();
    $stmt->close();

    echo json_encode([
        'success'=>true,
        'newPoints'=>$newPoints,
        'rewardName'=>$rewardName,
        'rewardType'=>$rewardType,
        'date'=>date("M d, Y"),
        'message'=>"You have successfully redeemed '$rewardName'."
    ]);
    exit;
}

// --- Fetch student's points and name ---
$conn->select_db('sprs_dummydb');
$stmt = $conn->prepare("SELECT points, name FROM users WHERE id=?");
$stmt->bind_param("i", $studentID);
$stmt->execute();
$stmt->bind_result($credits, $studentName);
$stmt->fetch();
$stmt->close();

// --- Generate initials ---
$names = explode(' ', $studentName);
$initials = '';
foreach ($names as $n) {
    $initials .= strtoupper($n[0]);
    if (strlen($initials) >= 2) break;
}

// --- Fetch rewards ---
$conn->select_db('sprs_mainredo');
$rewardList = [];
$result = $conn->query("SELECT * FROM rewards ORDER BY rewardName ASC");
while ($row = $result->fetch_assoc()) {
    $rewardList[] = $row;
}
?>




<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Student Point-Reward System — Redeem</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {
  --muted: #b5bcc8;
  --accent-1: #93c5fd;
  --accent-2: #3b82f6;
  --glass: rgba(0,0,0,0.40);
  --glass-strong: rgba(0,0,0,0.55);
  --success: #10b981;
  --transition: 240ms cubic-bezier(.2,.9,.3,1);
}
* { box-sizing: border-box; }
html, body { height: 100%; margin: 0; display: flex; flex-direction: column; }
body {
  font-family: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
  background: url('images/bg.jpg') no-repeat center center fixed;
  background-size: cover;
  color: #f2f6fb;
  line-height: 1.35;
  padding-top: 80px;
}
header {
  position: fixed; top: 0; left: 0; right: 0;
  width: 100%; z-index: 100;
  padding: 8px 18px; display: flex; justify-content: space-between; align-items: center;
  background-color: #1e293b; color: #fff;
  box-shadow: 0 4px 16px rgba(3,7,18,0.4); flex-wrap: wrap; row-gap: 8px;
}
.brand { display: flex; gap: 14px; align-items: center; flex-wrap: wrap; }
.logo img { width: 46px; height: 46px; border-radius: 8px; object-fit: cover; }
.title-wrap h1 { font-size: 16px; margin: 0; font-weight: 600; }
.profile-info { display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.08); padding: 8px 16px; border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.3); }
.avatar { background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: 700; font-size: 14px; }
.user-details { display: flex; flex-direction: column; line-height: 1.1; }
.user-details strong { font-size: 14px; }
.user-details span { font-size: 12px; color: #ccc; }
.credits { background: linear-gradient(135deg, #10b981, #059669); color: #fff; font-weight: 600; padding: 4px 10px; border-radius: 10px; font-size: 12px; }
.container { flex: 1; padding: 20px 18px; display: flex; flex-direction: column; }
.content { padding: 14px; border-radius: 12px; background: var(--glass); border: 1px solid rgba(255,255,255,0.04); box-shadow: 0 6px 18px rgba(3,7,18,0.45); position: relative; }
.hero { display: flex; align-items: center; justify-content: space-between; padding: 18px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.06); flex-wrap: wrap; row-gap: 10px; }
.hero h2 { margin: 0; font-size: 20px; font-weight: 700; }
.hero p { margin: 6px 0 0; color: #fff; text-shadow: 0 1px 4px rgba(2,6,23,0.6); }
.back-btn { background: linear-gradient(135deg, var(--accent-1), var(--accent-2)); color: #071033; font-weight: 700; border: none; border-radius: 8px; padding: 10px 18px; cursor: pointer; transition: var(--transition); box-shadow: 0 4px 12px rgba(2,6,23,0.35); }
.back-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(2,6,23,0.45); }

.redeem-section { margin-top: 30px; background: rgba(255,255,255,0.08); border-radius: 18px; padding: 30px; backdrop-filter: blur(12px); box-shadow: 0 10px 28px rgba(0,0,0,0.45); border: 1px solid rgba(255,255,255,0.12); text-align: center; }
#searchRewards {
    padding: 8px 12px;
    border-radius: 8px;
    border: none;
    font-size: 14px;
    margin-bottom: 20px;
    width: 100%;
    max-width: 300px;
}
.option-list { 
    list-style: none; 
    padding: 0; 
    margin: 0 auto; 
    display: flex; 
    flex-wrap: wrap; 
    justify-content: center; 
    gap: 25px; 
    max-height: 400px; 
    overflow-y: auto; 
}
.option-item { 
    background: rgba(255,255,255,0.15); 
    border-radius: 16px; 
    padding: 25px 20px; 
    cursor: pointer; 
    transition: var(--transition); 
    color: #fff; 
    display: flex; 
    flex-direction: column; 
    align-items: center; 
    width: 220px; 
    text-align: center; 
    font-size: 16px; 
    font-weight: 600; 
    box-shadow: 0 6px 18px rgba(0,0,0,0.35); 
}
.option-item img { width: 110px; height: 110px; border-radius: 12px; object-fit: cover; border: 2px solid rgba(255,255,255,0.3); margin-bottom: 15px; }
.option-item:hover { background: rgba(255,255,255,0.25); transform: translateY(-5px); box-shadow: 0 10px 28px rgba(0,0,0,0.45); }
footer { width: 100%; background: #1e293b; text-align: center; padding: 20px 10px; margin-top: auto; color:#fff; }
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
      <strong><?= htmlspecialchars($studentName) ?></strong>
      <span>Student</span>
    </div>
    <div class="credits">Credits: <span id="headerCredits"><?= htmlspecialchars($credits) ?></span></div>
  </div>
</header>

<div class="container">
<section class="content">
  <div class="hero">
    <div class="info">
      <h2>Redeem Points</h2>
      <p>Select a reward below to redeem it.</p>
    </div>
    <button class="back-btn" onclick="window.location.href='student_index.php'">⬅ Back to Dashboard</button>
  </div>

  <div class="redeem-section" id="redeemFlow">
    <h3>Available Rewards:</h3>
    <input type="text" id="searchRewards" placeholder="Search rewards...">
    <ul class="option-list" id="rewardList">
      <?php foreach($rewardList as $reward): 
        $imgFile = match($reward['rewardType']) {
          'Ticket'=>'pass.png',
          'Supplies'=>'ntbk.png',
          'Tshirts'=>'tshirt.png',
          'IDs'=>'id.png',
          'Points'=>'points.png',
          default=>'default.png'
        };
      ?>
      <li class="option-item">
        <img src="images/<?= $imgFile ?>" alt="<?= htmlspecialchars($reward['rewardName']) ?>">
        <div><?= htmlspecialchars($reward['rewardName']) ?> (<?= $reward['rewardPointsRequired'] ?> pts)</div>
        <button style="margin-top:10px;padding:8px 12px;font-weight:600;"
                onclick="redeemReward(<?= $reward['rewardID'] ?>, <?= $reward['rewardPointsRequired'] ?>, this)">
          Redeem
        </button>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
</div>

<footer>
    <div style="font-weight:700; font-size:16px; margin-bottom:12px;">Contact Us:</div>
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
async function redeemReward(rewardID, pointsRequired, btn){
    const credits=parseInt(document.getElementById('headerCredits').innerText);
    if(credits<pointsRequired){alert("You don't have enough points to redeem this reward.");return;}

    if(!confirm(`Are you sure you want to redeem this reward for ${pointsRequired} points?`)) return;

    btn.disabled=true; const originalText=btn.innerText; btn.innerText="Redeeming...";
    try{
        const res=await fetch('redeem.php',{
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body:JSON.stringify({rewardID, pointsRequired})
        });
        const data=await res.json();
        if(data.success){
            document.getElementById('headerCredits').innerText=data.newPoints;
            alert(data.message);
            btn.disabled=false; btn.innerText=originalText;
        } else {
            alert(data.message); btn.disabled=false; btn.innerText=originalText;
        }
    } catch(e){
        alert("An error occurred. Try again."); btn.disabled=false; btn.innerText=originalText;
    }
}

// --- Search filter ---
const rewardItems=Array.from(document.querySelectorAll('.option-item'));
document.getElementById('searchRewards').addEventListener('input',function(){
    const query=this.value.toLowerCase();
    rewardItems.forEach(item=>{
        const name=item.querySelector('div').textContent.toLowerCase();
        item.style.display=name.includes(query)?'flex':'none';
    });
});
</script>
</body>
</html>