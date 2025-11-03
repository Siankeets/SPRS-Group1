<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Student Point-Reward System — Events</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/html5-qrcode"></script>

  <style>
    :root {
      --bg-overlay: rgba(0,0,0,0.68);
      --muted: #b5bcc8;
      --accent-1: #93c5fd;
      --accent-2: #3b82f6;
      --panel-text: #071033;
      --glass: rgba(0,0,0,0.40);
      --glass-strong: rgba(0,0,0,0.55);
      --success: #10b981;
      --transition: 240ms cubic-bezier(.2,.9,.3,1);
    }

    * { box-sizing: border-box; }
    html, body { height: 100%; margin: 0; display: flex; flex-direction: column; }
    body {
      font-family: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background: url('bg.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #f2f6fb;
      line-height: 1.35;
      padding-top: 70px;
    }

    header {
      position: fixed; top: 0; left: 0; right: 0; width: 100%;
      z-index: 100; padding: 8px 18px;
      display: flex; justify-content: space-between; align-items: center;
      background-color: #1e293b; color: #fff;
      box-shadow: 0 4px 16px rgba(3,7,18,0.4);
      flex-wrap: wrap; row-gap: 8px;
    }

    .brand { display: flex; gap: 14px; align-items: center; flex-wrap: wrap; }
    .logo img { width: 46px; height: 46px; border-radius: 8px; object-fit: cover; display: block; }
    .title-wrap h1 { font-size: 16px; margin: 0; font-weight: 600; }
    .title-wrap p { margin: 2px 0 0; color: var(--muted); font-size: 12px; }

    .logged {
      background: var(--glass); padding: 8px 12px; border-radius: 8px; color: #fff; font-weight: 600;
      display: flex; align-items: center; gap: 8px;
      border: 1px solid rgba(255,255,255,0.06); font-size: 13px;
    }

    .container { flex: 1; padding: 20px 18px 20px; display: flex; flex-direction: column; }
    .main { display: grid; grid-template-columns: 260px 1fr; gap: 16px; flex: 1; }

    .sidebar {
      min-height: 380px; background: var(--glass); border-radius: 12px; padding: 14px;
      border: 1px solid rgba(255,255,255,0.04); box-shadow: 0 6px 18px rgba(2,6,23,0.45);
      display: flex; flex-direction: column;
    }

    .profile { display: flex; gap: 12px; align-items: center; margin-bottom: 12px; }
    .avatar {
      width: 56px; height: 56px; border-radius: 10px; background: rgba(255,255,255,0.06);
      display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 16px;
      border: 1px solid rgba(255,255,255,0.06);
    }

    .meta .name { font-weight: 700; }
    .meta .role { font-size: 13px; color: var(--muted); }

    .credits-card {
      margin-bottom: 14px; padding: 12px; border-radius: 10px;
      background: linear-gradient(135deg,#3b82f6,#1d4ed8); color: #fff; text-align: center;
      font-weight: 700; box-shadow: 0 8px 18px rgba(3,7,18,0.45);
      display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 15px;
    }

    .buttons { display: grid; gap: 10px; margin-top: 8px; }
    .menu-btn {
      display: flex; gap: 12px; align-items: center; padding: 12px; border-radius: 10px;
      background: var(--glass); border: 1px solid rgba(255,255,255,0.03); color: #fff;
      cursor: pointer; text-align: left; transition: var(--transition);
    }
    .menu-btn:hover {
      transform: translateY(-3px);
      background: var(--glass-strong);
      box-shadow: 0 10px 24px rgba(2,6,23,0.35);
    }

    .content {
      padding: 14px; border-radius: 12px; background: var(--glass);
      border: 1px solid rgba(255,255,255,0.04);
      box-shadow: 0 6px 18px rgba(3,7,18,0.45);
      position: relative;
    }

    .hero {
      display: flex; align-items: center; justify-content: space-between;
      padding: 18px; border-radius: 12px;
      border: 1px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.06);
      flex-wrap: wrap; row-gap: 10px;
    }

    .hero h2 { margin: 0; font-size: 20px; font-weight: 700; }
    .hero p { margin: 6px 0 0; color: #fff; text-shadow: 0 1px 4px rgba(2,6,23,0.6); }

    .redeem-section {
      margin-top: 30px;
      background: rgba(255,255,255,0.08);
      border-radius: 18px;
      padding: 30px;
      backdrop-filter: blur(12px);
      box-shadow: 0 10px 28px rgba(0,0,0,0.45);
      border: 1px solid rgba(255,255,255,0.12);
      text-align: center;
    }

    .redeem-section button {
      background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
      border: none; border-radius: 10px;
      padding: 12px 20px;
      color: #071033; font-weight: 700; font-size: 15px;
      cursor: pointer; transition: var(--transition);
      margin: 10px;
      box-shadow: 0 4px 16px rgba(2,6,23,0.4);
    }
    .redeem-section button:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 26px rgba(2,6,23,0.5);
    }

    .option-list {
      list-style: none;
      padding: 0;
      margin: 20px auto;
      max-width: 400px;
      text-align: left;
    }

    .option-item {
      background: rgba(255,255,255,0.12);
      border-radius: 10px;
      padding: 10px 15px;
      margin-bottom: 10px;
      cursor: pointer;
      transition: var(--transition);
      color: #fff;
    }

    .option-item:hover {
      background: rgba(255,255,255,0.25);
      transform: translateY(-3px);
    }

    .history-log {
      margin-top: 40px;
      padding: 20px;
      background: rgba(255,255,255,0.07);
      border-radius: 12px;
    }

    #reader { width: 100%; max-width: 400px; margin: 10px auto; }
    #scanStatus { margin-top: 10px; font-weight: 600; }

    footer {
      width: 100%;
      background: #1e293b;
      text-align: center;
      padding: 20px 10px;
      margin-top: auto;
    }

    @media (max-width: 768px) {
      header { flex-direction: column; text-align: center; gap: 6px; padding-bottom: 12px; }
      body { padding-top: 120px; }
      .main { grid-template-columns: 1fr; }
      .sidebar { order: 2; min-height: auto; }
      .content { order: 1; }
    }
  </style>
</head>

<body>
  <header>
    <div class="brand">
      <div class="logo"><img src="logorewards.jpg" alt="SPRS Logo"></div>
      <div class="title-wrap">
        <h1>Student Point-Reward System</h1>
        <p class="lead">QR Attendance • SMS Auth • Email Notifications</p>
      </div>
    </div>
    <div class="logged">
      <span>Logged in as: <strong>Student</strong></span>
    </div>
  </header>

  <div class="container">
    <div class="main">
      <aside class="sidebar">
        <div class="profile">
          <div class="avatar">ST</div>
          <div class="meta">
            <div class="name">John Student</div>
            <div class="role">Student</div>
          </div>
        </div>

        <div class="credits-card">
          <div>Points: <span id="credits">120</span></div>
        </div>

        <nav class="buttons">
          <button class="menu-btn" onclick="window.location.href='student_index.php'">⬅ Back to Dashboard</button>
        </nav>
      </aside>

      <section class="content">
        <div class="hero">
          <div class="info">
            <h2>Event Attendance</h2>
            <p>Follow the steps below to join and validate your event attendance.</p>
          </div>
          <div>
            <div style="font-size:13px;color:var(--muted)">Role:</div>
            <div style="font-weight:700">Student</div>
          </div>
        </div>

        <div class="redeem-section" id="redeemFlow">
          <h3>Choose an Event</h3>
          <ul class="option-list">
            <li class="option-item" onclick="chooseEvent('Leadership Seminar')">Leadership Seminar: <strong>+10 Points</strong></li>
            <li class="option-item" onclick="chooseEvent('Tech Innovation Fair')">Tech Innovation Fair: <strong>+15 Points</strong></li>
            <li class="option-item" onclick="chooseEvent('Community Outreach')">Community Outreach: <strong>+20 Points</strong></li>
          </ul>
        </div>

        <div class="history-log">
          <h3>📜 Attendance History</h3>
          <ul id="historyList" style="list-style:none; padding:0; margin-top:10px; color:#e0e7ff;"></ul>
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
const flow = document.getElementById('redeemFlow');
const historyList = document.getElementById('historyList');
let credits = 120;

const eventPoints = {
  "Leadership Seminar": 10,
  "Tech Innovation Fair": 15,
  "Community Outreach": 20
};

function startEvent() {
  flow.innerHTML = `
    <h3>Step 1: Choose an Event</h3>
    <p>Select an event to attend:</p>
    <ul class="option-list">
      <li class="option-item" onclick="chooseEvent('Leadership Seminar')">Leadership Seminar: <strong>+10 Points</strong></li>
      <li class="option-item" onclick="chooseEvent('Tech Innovation Fair')">Tech Innovation Fair: <strong>+15 Points</strong></li>
      <li class="option-item" onclick="chooseEvent('Community Outreach')">Community Outreach: <strong>+20 Points</strong></li>
    </ul>
  `;
}

function chooseEvent(eventName) {
  flow.innerHTML = `
    <h3>Event Selected:</h3>
    <p><strong>${eventName}</strong></p>
    <button onclick="attendEvent('${eventName}')">📝 Attend</button>
    <button onclick="cancelEvent()">❌ Cancel</button>
  `;
}

function attendEvent(eventName) {
  flow.innerHTML = `
    <h3>Attending: ${eventName}</h3>
    <p>Your attendance has been noted. Generating receipt...</p>
    <button onclick="generateReceipt('${eventName}')">🧾 Generate Receipt</button>
  `;
}

function generateReceipt(eventName) {
  flow.innerHTML = `
    <h3>Receipt Generated</h3>
    <p>Receipt for <strong>${eventName}</strong> has been successfully generated.</p>
    <button onclick="startScanner('${eventName}')">📷 Scan Event QR</button>
  `;
}

function startScanner(eventName) {
  flow.innerHTML = `
    <h3>Step: Scan Event QR Code</h3>
    <p>Use your camera to scan the event's QR code for validation.</p>
    <div id="reader"></div>
    <div id="scanStatus">Waiting for scan...</div>
    <button onclick="cancelEvent()">❌ Cancel</button>
  `;

  const html5QrCode = new Html5Qrcode("reader");
  html5QrCode.start(
    { facingMode: "environment" },
    { fps: 10, qrbox: 250 },
    (decodedText) => {
      html5QrCode.stop();
      document.getElementById("scanStatus").innerHTML = "✅ QR Code Scanned: " + decodedText;
      setTimeout(() => validateAttendance(eventName, decodedText), 1000);
    }
  );
}

function validateAttendance(eventName, qrText) {
  flow.innerHTML = `
    <h3>Validate Attendance</h3>
    <p>Validating scanned event code...</p>
  `;

  setTimeout(() => {
    const isValid = qrText.includes("VALID"); // simulate check
    if (isValid) {
      flow.innerHTML = `
        <h3>Validation Successful</h3>
        <p>QR verified successfully for <strong>${eventName}</strong>.</p>
        <button onclick="generateCode('${eventName}')">🔐 Generate Code</button>
      `;
    } else {
      flow.innerHTML = `
        <h3>Invalid QR Code</h3>
        <p>The scanned code is invalid or expired.</p>
        <button onclick="startScanner('${eventName}')">🔁 Scan Again</button>
      `;
    }
  }, 1500);
}

function generateCode(eventName) {
  const code = Math.floor(1000 + Math.random() * 9000);
  flow.innerHTML = `
    <h3>Code Generated</h3>
    <p>Your event code is: <strong>${code}</strong></p>
    <p>Status: <span style="color:var(--success); font-weight:700;">VALID ✅</span></p>
    <button onclick="generateHistory('${eventName}')">📜 Generate Attendance Log</button>
  `;
}

function generateHistory(eventName) {
  const points = eventPoints[eventName] || 0;
  credits += points;
  document.getElementById('credits').textContent = credits;

  const date = new Date().toLocaleString();
  const li = document.createElement('li');
  li.textContent = `${date} — ${eventName} (+${points} pts)`;
  historyList.prepend(li);

  flow.innerHTML = `
    <h3>Attendance Logged</h3>
    <p>Your attendance for <strong>${eventName}</strong> has been successfully saved to history.</p>
    <button onclick="startEvent()">↩ Attend Another Event</button>
  `;
}

function cancelEvent() {
  flow.innerHTML = `
    <h3>Event Cancelled</h3>
    <p>You cancelled the process.</p>
    <button onclick="startEvent()">🔄 Start Again</button>
  `;
}
</script>
</body>
</html>
