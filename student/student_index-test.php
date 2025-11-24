<?php
session_start();

include('../db_connect.php');
// Redirect if not logged in or not a student
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

// Get user info from session
$username = $_SESSION['username'];
$name = $_SESSION['name'];
$role = $_SESSION['role'];
$credits = $_SESSION['points'];
$department = $_SESSION['department'];
$program = $_SESSION['program'];
$major = $_SESSION['major'];

$conn->select_db('if0_40284661_sprs_dummydb'); // need to change this when hosting (infinityfree)
$stmt = $conn->prepare("SELECT points FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($credits);
$stmt->fetch();
$stmt->close();

// Generate initials for avatar
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
:root {
  --bg-overlay: rgba(0,0,0,0.68);
  --muted: #b5bcc8;
  --accent-1: #93c5fd;
  --accent-2: #3b82f6;
  --panel-text: #071033;
  --btn-blue: #3498db;
  --btn-blue-dark: #2980b9;
  --glass: rgba(0,0,0,0.40);
  --glass-strong: rgba(0,0,0,0.55);
  --success: #10b981;
  --transition: 240ms cubic-bezier(.2,.9,.3,1);
}

* { box-sizing: border-box; }
html, body { height: 100%; margin: 0; display: flex; flex-direction: column; }
body {
  font-family: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
  background: #0f172a;
  color: #f2f6fb;
  line-height: 1.35;
}

.container {
  flex: 1;
  padding: 80px 18px 20px;
  display: flex;
  flex-direction: column;
  background: url('images/bg.jpg') no-repeat center center fixed;
  background-size: cover;
  background-attachment: fixed;
  background-blend-mode: overlay;
}

.credits {
  background-color: #10b981;
  color: #fff;             
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 13px;
  display: inline-block;
  box-shadow: 0 2px 6px rgba(0,0,0,0.3);
  transition: transform 0.2s ease;
}

.credits:hover {
  transform: scale(1.05);
}
.credits-wrapper {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  font-weight: 500;
  color: #fff;
}

.credits-label {
  color: #ccc; /* muted label */
}

.credits-value {
  background-color: #10b981; /* green pill */
  color: #fff;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 20px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.3);
  transition: transform 0.2s ease;
}

.credits-value:hover {
  transform: scale(1.05);
}



header {
  position: fixed; top: 0; left: 0; right: 0;
  width: 100%; z-index: 100;
  padding: 8px 18px;
  display: flex; justify-content: space-between; align-items: center;
  background-color: #1e293b; color: #fff;
  box-shadow: 0 4px 16px rgba(3, 7, 18, 0.4);
}

.brand { display: flex; gap: 14px; align-items: center; }
.logo img { width: 46px; height: 46px; border-radius: 8px; object-fit: cover; }
.title-wrap h1 { font-size: 16px; margin: 0; font-weight: 600; }

.profile-info {
  display: flex; align-items: center; gap: 12px;
  background: rgba(255, 255, 255, 0.08);
  padding: 8px 16px; border-radius: 12px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.3);
}

.profile-info .avatar {
  background: linear-gradient(135deg, #3b82f6, #2563eb);
  color: #fff; width: 36px; height: 36px;
  display: flex; align-items: center; justify-content: center;
  border-radius: 50%; font-weight: 700; font-size: 14px;
}

.profile-info .user-details {
  display: flex; flex-direction: column; line-height: 1.1;
}
.profile-info .user-details strong { font-size: 14px; }
.profile-info .user-details span { font-size: 12px; color: #ccc; }

.main { display: grid; grid-template-columns: 260px 1fr; gap: 16px; flex: 1; }

.sidebar {
  min-height: 380px;
  background: var(--glass);
  border-radius: 12px;
  padding: 14px;
  border: 1px solid rgba(255,255,255,0.04);
  box-shadow: 0 6px 18px rgba(2,6,23,0.45);
  display: flex; flex-direction: column; justify-content: flex-start;
}

.buttons { display: grid; gap: 10px; margin-top: 10px; }
.menu-btn {
  display: flex; gap: 12px; align-items: center;
  padding: 12px; border-radius: 10px;
  background: var(--glass); border: 1px solid rgba(255,255,255,0.03);
  color: #fff; cursor: pointer; text-align: left; transition: var(--transition);
}
.menu-btn:hover {
  transform: translateY(-3px);
  background: var(--glass-strong);
  box-shadow: 0 10px 24px rgba(2,6,23,0.35);
}
.menu-btn svg { width: 18px; height: 18px; flex: 0 0 18px; opacity: 0.95; }
.menu-btn .text { display: flex; flex-direction: column; }
.menu-btn .text .title { font-weight: 600; }
.menu-btn .text .sub { font-size: 12px; color: var(--muted); margin-top: 2px; }

.content {
  padding: 20px;
  border-radius: 12px;
  background: var(--glass);
  border: 1px solid rgba(255,255,255,0.04);
  box-shadow: 0 6px 18px rgba(3,7,18,0.45);
  position: relative;
}

.hero {
  display: flex; align-items: center; justify-content: space-between;
  padding: 20px; border-radius: 12px;
  background: linear-gradient(135deg, #3b82f6, #60a5fa);
  color: #fff;
  box-shadow: 0 6px 18px rgba(0,0,0,0.4);
  margin-bottom: 20px;
}
.hero .info h2 { margin: 0; font-size: 20px; font-weight: 700; }
.hero .info p { margin-top: 6px; font-size: 14px; opacity: 0.9; }

.user-image {
  width: 120px;
  height: 120px;
  background: rgba(255,255,255,0.1);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 32px;
  color: #fff;
  font-weight: 700;
  flex-shrink: 0;
}

.info-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 16px;
}

.card {
  background: rgba(255,255,255,0.08);
  padding: 14px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.35);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.45);
}
.card h3 {
  font-size: 14px;
  font-weight: 600;
  color: #93c5fd;
  margin-bottom: 6px;
}
.card p {
  font-size: 13px;
  color: #f2f6fb;
  opacity: 0.9;
}

footer {
  width: 100%; background: #1e293b; text-align: center; padding: 20px 10px; margin-top: auto;
}

@media (max-width:940px){.main{grid-template-columns:1fr;gap:12px}}
@media (max-width:600px){
  header{padding:6px 14px}
  .logo img{width:42px;height:42px}
  .container{padding-top:95px!important;padding-left:14px;padding-right:14px;padding-bottom:20px;}
  .hero { flex-direction: column; align-items: flex-start; }
  .user-image { margin-top: 16px; }
}
</style>
</head>

<body>
<header>
  <div class="brand">
    <div class="logo"><img src="images/logorewards.jpg" alt="SPRS Logo"></div>
    <div class="title-wrap">
      <h1>Student Point-Reward System</h1>
    </div>
  </div>

  <div class="profile-info">
    <div class="avatar"><?= htmlspecialchars($initials) ?></div>
    <div class="user-details">
      <strong><?= htmlspecialchars($name) ?></strong>
      <span><?= htmlspecialchars(ucfirst($role)) ?></span>
    </div>
    <div class="credits" id="headerCredits">
      Credits: <?= htmlspecialchars($credits) ?>
    </div>
  </div>
</header>

<div id="scanModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:9999; justify-content:center; align-items:center; flex-direction:column;">
  <div style="background:#1e293b; padding:20px; border-radius:12px; text-align:center; position:relative;">
    <button id="closeScan" style="position:absolute; top:10px; right:10px; background:#f00;color:#fff;padding:4px 8px;border:none;border-radius:6px;cursor:pointer;">X</button>
    <h3 style="color:#fff;">Scan QR Code</h3>
    <video id="qrVideo" autoplay muted playsinline style="width:300px;height:300px;border-radius:12px;border:2px solid #2563eb;margin:auto;"></video>
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
          <h2 id="dashboardTitle">Welcome, <?= htmlspecialchars($name) ?></h2>
          <p id="heroDesc">Here's your dashboard. Check your rewards, events, and more.</p>
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
  <div style="font-weight:700; font-size:16px; margin-bottom:12px;">Contact Us:</div>
  <div style="font-size:13px; display:flex; justify-content:center; gap:12px; flex-wrap:wrap; align-items:center; margin-bottom:12px; color:#93c5fd;">
    <div>📧 sprsystem@gmail.com</div>
    <span style="color:#ccc;">|</span>
    <div>📞 09123456789</div>
    <span style="color:#ccc;">|</span>
    <div style="display:flex; align-items:center; gap:6px;">
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
  </div>
  <div style="font-size:13px;color:#fff;">© 2025 Student Point-Reward System. All rights reserved.</div>
</footer>

<script>
const creditsEl = document.getElementById('headerCredits');
let credits = <?= json_encode($credits) ?>;

const studentMenu = [
  { key: 'scan', title: 'Scan Points', sub: 'Scan QR to add points', icon: 'scan' },
  { key: 'redeem', title: 'Redeem', sub: 'Redeem rewards with credits', icon: 'gift' },
  { key: 'inventory', title: 'Inventory', sub: 'Track redeemed rewards', icon: 'box' },
  { key: 'events', title: 'Events List', sub: 'View upcoming events', icon: 'calendar' },
  { key: 'help', title: 'Help Center', sub: 'Get assistance and FAQs', icon: 'help' },
  { key: 'logout', title: 'Logout', sub: 'Sign out', icon: 'logout' }
];

function iconSVG(name) {
  const map = {
    scan:`<svg viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="7" height="7" stroke="#fff" stroke-width="1.5"/><rect x="14" y="3" width="7" height="7" stroke="#fff" stroke-width="1.5"/><rect x="3" y="14" width="7" height="7" stroke="#fff" stroke-width="1.5"/><rect x="14" y="14" width="7" height="7" stroke="#fff" stroke-width="1.5"/></svg>`,
    gift:`<svg viewBox="0 0 24 24" fill="none"><path d="M20 12v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8" stroke="#fff" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 7v-1a2 2 0 0 0-2-2h-3.5c.6 0 1.1.3 1.5.8.6.8.4 1.9-.5 2.5L12 11" stroke="#fff" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
    box:`<svg viewBox="0 0 24 24" fill="none"><path d="M21 16V8a1 1 0 0 0-.5-.86L12.5 2.5a1 1 0 0 0-.99 0L3.5 7.14A1 1 0 0 0 3 8v8a1 1 0 0 0 .5.86L11.5 21.5a1 1 0 0 0 .99 0L20.5 16.86A1 1 0 0 0 21 16z" stroke="#fff" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
    calendar:`<svg viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="16" rx="2" stroke="#fff" stroke-width="1.2"/><path d="M16 3v4M8 3v4" stroke="#fff" stroke-width="1.2" stroke-linecap="round"/></svg>`,
    help:`<svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="#fff" stroke-width="1.2"/><path d="M12 17h.01M12 13a2 2 0 0 1 2-2c0-1.1-.9-2-2-2s-2 .9-2 2" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
    logout:`<svg viewBox="0 0 24 24" fill="none"><path d="M9 21H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>`
  };
  return map[name] || '';
}

const menu = document.getElementById('menu');
studentMenu.forEach(item => {
  const btn = document.createElement('button');
  btn.className = 'menu-btn';
  btn.setAttribute('data-key', item.key);
  btn.innerHTML = `<span class="icon">${iconSVG(item.icon)}</span>
                   <span class="text"><span class="title">${item.title}</span><span class="sub">${item.sub}</span></span>`;
  btn.addEventListener('click', () => handleMenu(item.key));
  menu.appendChild(btn);
});

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

function handleMenu(key){
  switch(key){
    case 'scan':
      scanModal.style.display = 'flex';
      scanResult.textContent = '';

        // GPT ver. - uses get_points.php
       qrScanner = new QrScanner(qrVideo, result => {
    qrScanner.stop();

    // extract code: if QR is "someurl?qr=qr_xxx" or "qr_xxx"
    function extractQrCode(raw) {
        if (!raw) return "";
        if (raw.includes("qr=")) {
            return raw.split("qr=").pop().trim();
        }
        // If QR contains slashes (a URL), maybe last path segment is the code
        try {
            const possible = raw.trim();
            const u = new URL(possible);
            const lastSeg = u.pathname.split('/').filter(Boolean).pop();
            if (lastSeg && lastSeg.startsWith("qr_")) return lastSeg;
        } catch (e) {
            // not a URL; ignore
        }
        return raw.trim();
    }

    const extracted = extractQrCode(result);
    if (!extracted) {
        scanResult.textContent = "Invalid QR data.";
        return;
    }

    scanResult.textContent = "Processing…";

    const formData = new URLSearchParams();
    formData.append("qr", extracted);

    fetch("../admin/verify_qr-test.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: formData.toString()
    })
    .then(res => res.json())
    .then(json => {
        if (json.success) {
            scanResult.textContent = `+${json.pointsAdded} pts — ${json.reason || ''}`;

            // update header credits immediately
            const headerCredits = document.getElementById("headerCredits");
            if (headerCredits) headerCredits.textContent = "Credits: " + json.newTotal;

            credits = json.newTotal; // update JS variable
        } else {
            scanResult.textContent = json.message || "Scan failed.";
        }
    })
    .catch(err => {
        console.error(err);
        scanResult.textContent = "Server error while redeeming QR.";
    });
});


      qrScanner.start();
      break;
    case 'redeem': window.location.href = 'redeem-test.php'; break; //changed to test ver. 
    case 'inventory': window.location.href = 'inventory.php'; break;
    case 'events': window.location.href = 'event-test.php'; break;
    case 'help': window.location.href = 'help.php'; break;
    case 'logout': if(confirm('Logout?')) window.location.href = '../logout.php'; break; //now using logout php with session destruction
  }
}
</script>
</body>
</html>