<?php
session_start();
include('../db_connect.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'];
$name = $_SESSION['name'];
$role = $_SESSION['role'];
$credits = $_SESSION['points'];
$department = $_SESSION['department'];
$program = $_SESSION['program'];
$major = $_SESSION['major'];

$conn->select_db('sprs_dummydb');
$stmt = $conn->prepare("SELECT points FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($credits);
$stmt->fetch();
$stmt->close();

$names = explode(' ', $name);
$initials = '';
foreach ($names as $n) {
    $initials .= strtoupper($n[0]);
    if (strlen($initials) >= 2) break;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Student Point-Reward System — Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<!-- QR-Scanner library -->
<script src="https://unpkg.com/qr-scanner/qr-scanner.umd.min.js"></script>
<script>
QrScanner.WORKER_PATH = 'https://unpkg.com/qr-scanner/qr-scanner-worker.min.js';
</script>

<style>
:root { --glass: rgba(0,0,0,0.4); --glass-strong: rgba(0,0,0,0.55); --transition: 240ms cubic-bezier(.2,.9,.3,1); --muted:#b5bcc8; --accent-1:#93c5fd; --accent-2:#3b82f6; --success:#10b981;}
* { box-sizing: border-box; }
html, body { height: 100%; margin: 0; font-family:'Inter',system-ui,sans-serif; background:#0f172a; color:#f2f6fb; }
.container { flex:1; padding:80px 18px 20px; display:flex; flex-direction:column; background:url('images/bg.jpg') no-repeat center center fixed; background-size:cover; background-blend-mode: overlay; }
header { position:fixed; top:0; left:0; right:0; display:flex; justify-content:space-between; align-items:center; padding:8px 18px; background:#1e293b; z-index:100; box-shadow:0 4px 16px rgba(3,7,18,0.4);}
.brand { display:flex; gap:14px; align-items:center; }
.logo img { width:46px; height:46px; border-radius:8px; object-fit:cover; }
.profile-info { display:flex; align-items:center; gap:12px; background: rgba(255,255,255,0.08); padding:8px 16px; border-radius:12px; box-shadow:0 2px 6px rgba(0,0,0,0.3); }
.avatar { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; width:36px;height:36px; display:flex; align-items:center; justify-content:center; border-radius:50%; font-weight:700; font-size:14px; }
.user-details { display:flex; flex-direction:column; line-height:1.1; }
.user-details strong { font-size:14px; }
.user-details span { font-size:12px; color:#ccc; }
.credits { background-color:#10b981; color:#fff; padding:4px 10px; border-radius:20px; font-weight:600; font-size:13px; display:inline-block; box-shadow:0 2px 6px rgba(0,0,0,0.3); transition:transform 0.2s ease; }
.credits:hover { transform: scale(1.05); }

.main { display:grid; grid-template-columns:260px 1fr; gap:16px; flex:1; margin-top:20px; }
.sidebar { min-height:380px; background:var(--glass); border-radius:12px; padding:14px; border:1px solid rgba(255,255,255,0.04); box-shadow:0 6px 18px rgba(2,6,23,0.45); display:flex; flex-direction:column; }
.buttons { display:grid; gap:10px; margin-top:10px; }
.menu-btn { display:flex; gap:12px; align-items:center; padding:12px; border-radius:10px; background:var(--glass); border:1px solid rgba(255,255,255,0.03); color:#fff; cursor:pointer; text-align:left; transition:var(--transition); }
.menu-btn:hover { transform:translateY(-3px); background:var(--glass-strong); box-shadow:0 10px 24px rgba(2,6,23,0.35);}
.menu-btn svg { width:18px; height:18px; flex:0 0 18px; opacity:0.95; }
.menu-btn .text { display:flex; flex-direction:column; }
.menu-btn .title { font-weight:600; }
.menu-btn .sub { font-size:12px; color:var(--muted); margin-top:2px; }

.content { padding:20px; border-radius:12px; background:var(--glass); border:1px solid rgba(255,255,255,0.04); box-shadow:0 6px 18px rgba(3,7,18,0.45); position:relative; }
.hero { display:flex; align-items:center; justify-content:space-between; padding:20px; border-radius:12px; background:linear-gradient(135deg,#3b82f6,#60a5fa); color:#fff; box-shadow:0 6px 18px rgba(0,0,0,0.4); margin-bottom:20px; }
.user-image { width:120px;height:120px;background:rgba(255,255,255,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:700;color:#fff; flex-shrink:0;}
.info-cards { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:16px; }
.card { background:rgba(255,255,255,0.08); padding:14px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.35); transition:transform 0.2s ease, box-shadow 0.2s ease; }
.card:hover { transform:translateY(-4px); box-shadow:0 8px 20px rgba(0,0,0,0.45); }
.card h3 { font-size:14px; font-weight:600; color:#93c5fd; margin-bottom:6px; }
.card p { font-size:13px; color:#f2f6fb; opacity:0.9; }

footer { width:100%; background:#1e293b; text-align:center; padding:20px 10px; margin-top:auto; }

#scanModal { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:9999; justify-content:center; align-items:center; flex-direction:column;}
#scanModalContent { background:#1e293b; padding:20px; border-radius:12px; text-align:center; position:relative; }
#closeScan { position:absolute; top:10px; right:10px; background:#f00;color:#fff;padding:4px 8px;border:none;border-radius:6px;cursor:pointer; }
#qrVideo { width:300px;height:300px;border-radius:12px;border:2px solid #2563eb; margin:auto; }
@media (max-width:940px){.main{grid-template-columns:1fr;gap:12px}}
@media (max-width:600px){header{padding:6px 14px}.logo img{width:42px;height:42px}.container{padding-top:95px!important;padding-left:14px;padding-right:14px;padding-bottom:20px;}.hero{flex-direction:column;align-items:flex-start;}.user-image{margin-top:16px;}}
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
      <strong><?= htmlspecialchars($name) ?></strong>
      <span><?= htmlspecialchars(ucfirst($role)) ?></span>
    </div>
    <div class="credits" id="headerCredits"><?= htmlspecialchars($credits) ?></div>
  </div>
</header>

<div id="scanModal">
  <div id="scanModalContent">
    <button id="closeScan">X</button>
    <h3 style="color:#fff;">Scan QR Code</h3>
    <video id="qrVideo" autoplay muted playsinline></video>
    <p id="scanResult" style="margin-top:10px; color:#10b981;"></p>
  </div>
</div>

<div class="container">
  <div class="main">
    <aside class="sidebar">
      <nav class="buttons" id="menu"></nav>
    </aside>
    <section class="content">
      <div class="hero">
        <div class="info">
          <h2>Welcome, <?= htmlspecialchars($name) ?></h2>
          <p>Here's your dashboard. Check your rewards, events, and more.</p>
        </div>
        <div class="user-image"><?= htmlspecialchars($initials) ?></div>
      </div>
      <div class="info-cards">
        <div class="card"><h3>Department</h3><p><?= htmlspecialchars($department) ?></p></div>
        <div class="card"><h3>Program</h3><p><?= htmlspecialchars($program) ?></p></div>
        <div class="card"><h3>Major</h3><p><?= htmlspecialchars($major) ?></p></div>
      </div>
    </section>
  </div>
</div>

<footer>
  <!-- Your footer content -->
</footer>

<script>
let credits = <?= json_encode($credits) ?>;
let qrScanner = null;
const scanModal = document.getElementById('scanModal');
const scanResult = document.getElementById('scanResult');
const qrVideo = document.getElementById('qrVideo');

document.getElementById('closeScan').addEventListener('click', () => {
    scanModal.style.display = 'none';
    if(qrScanner){
        qrScanner.stop();
        qrScanner.destroy();
        qrScanner = null;
    }
});

const studentMenu = [
  { key: 'scan', title: 'Scan Points', sub: 'Scan QR to add points', icon: 'scan' },
  { key: 'redeem', title: 'Redeem', sub: 'Redeem rewards with credits', icon: 'gift' },
  { key: 'inventory', title: 'Inventory', sub: 'Track redeemed rewards', icon: 'box' },
  { key: 'events', title: 'Events List', sub: 'View upcoming events', icon: 'calendar' },
  { key: 'help', title: 'Help Center', sub: 'Get assistance and FAQs', icon: 'help' },
  { key: 'logout', title: 'Logout', sub: 'Sign out', icon: 'logout' }
];

function iconSVG(name){ return ''; } // keep your SVG icons
const menu = document.getElementById('menu');
studentMenu.forEach(item => {
  const btn = document.createElement('button');
  btn.className = 'menu-btn';
  btn.setAttribute('data-key', item.key);
  btn.innerHTML = `<span class="icon">${iconSVG(item.icon)}</span><span class="text"><span class="title">${item.title}</span><span class="sub">${item.sub}</span></span>`;
  btn.addEventListener('click', () => handleMenu(item.key));
  menu.appendChild(btn);
});

function handleMenu(key){
  switch(key){
    case 'scan': openScan(); break;
    case 'redeem': window.location.href='redeem.php'; break;
    case 'inventory': window.location.href='inventory.php'; break;
    case 'events': window.location.href='event.php'; break;
    case 'help': window.location.href='help.php'; break;
    case 'logout': if(confirm('Logout?')) window.location.href='../login.php'; break;
  }
}

// function openScan(){
//     scanModal.style.display = 'flex';
//     scanResult.textContent = '';

//     // Wait for video element to be rendered
//     setTimeout(() => {
//        qrScanner = new QrScanner(
//     qrVideo,
//     result => {
//         console.log('QR detected:', result);
//         scanResult.textContent = `✅ Points Added: ${result}`;
//     },
//     {
//         onDecodeError: err => console.log('Decode error:', err),
//         highlightScanRegion: true,   // shows the scanning area
//         highlightCodeOutline: true,   // draws a box around detected QR
// preferredCamera: null

//     }
// );

// qrScanner.start().catch(err => console.error('QR Scanner start error:', err));

//     }, 200);
// }
</script>
</body>
</html>