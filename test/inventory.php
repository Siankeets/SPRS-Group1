<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Student Point-Reward System — Inventory</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

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

    .inventory-container {
      display: flex;
      justify-content: center;
      align-items: stretch;
      gap: 20px;
      flex-wrap: wrap;
      margin-top: 30px;
    }

    .inventory-card {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
      padding: 20px;
      width: 250px;
      text-align: center;
      color: white;
      transition: transform 0.3s, box-shadow 0.3s;
      flex: 1 1 220px;
      max-width: 260px;
      min-height: 260px;
    }

    .inventory-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 25px rgba(255, 255, 255, 0.2);
    }

    .inventory-card h3 {
      margin-bottom: 15px;
      font-weight: 600;
    }

    /* Buttons style for option items */
    .option-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .inventory-card .option-item {
      background: linear-gradient(135deg, #3b82f6, #1e40af);
      color: #fff;
      padding: 10px 16px;
      border-radius: 10px;
      margin: 8px 0;
      cursor: pointer;
      font-weight: 600;
      border: none;
      transition: all 0.3s ease;
    }

    .inventory-card .option-item:hover {
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(59,130,246,0.5);
    }

    .inventory-card button {
      background: linear-gradient(135deg, #10b981, #059669);
      color: #fff;
      padding: 10px 18px;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .inventory-card button:hover {
      background: linear-gradient(135deg, #059669, #047857);
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(16,185,129,0.5);
    }

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
          <div>Credits: <span id="credits">120</span></div>
        </div>

        <nav class="buttons">
          <button class="menu-btn" onclick="window.location.href='index.php'">⬅ Back to Dashboard</button>
        </nav>
      </aside>

      <section class="content">
        <div class="hero">
          <div class="info">
            <h2>View Inventory</h2>
            <p>Check available vouchers, tickets, and event certificates below.</p>
          </div>
          <div>
            <div style="font-size:13px;color:var(--muted)">Role:</div>
            <div style="font-weight:700">Student</div>
          </div>
        </div>

        <div class="redeem-section" id="inventoryFlow">
          <h3>Inventory Options:</h3>
          <div class="inventory-container">
            <div class="inventory-card">
              <h3>🎟️ Point Vouchers</h3>
              <ul class="option-list">
                <li class="option-item" onclick="redeemItem('50 Points Voucher', 50)">Redeem 50 Points Voucher (50 pts)</li>
                <li class="option-item" onclick="redeemItem('100 Points Voucher', 100)">Redeem 100 Points Voucher (100 pts)</li>
              </ul>
            </div>

            <div class="inventory-card">
              <h3>🎫 Tickets</h3>
              <ul class="option-list">
                <li class="option-item" onclick="redeemItem('Movie Ticket', 75)">Redeem Movie Ticket (75 pts)</li>
                <li class="option-item" onclick="redeemItem('Raffle Entry', 50)">Redeem Raffle Entry (50 pts)</li>
              </ul>
            </div>

            <div class="inventory-card">
              <h3>📜 Event Certificates</h3>
              <ul class="option-list">
                <li class="option-item" onclick="redeemItem('Seminar Certificate', 60)">Redeem Seminar Certificate (60 pts)</li>
                <li class="option-item" onclick="redeemItem('Volunteer Certificate', 70)">Redeem Volunteer Certificate (70 pts)</li>
              </ul>
            </div>

            <div class="inventory-card">
              <h3>🕓 History</h3>
              <p>Displaying your previous redemptions and inventory updates here.</p>
              <button onclick="alert('History viewed!')">View History</button>
            </div>
          </div>
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
let credits = 120;
let selectedItem = null;
let selectedCost = 0;

function redeemItem(item, cost) {
  selectedItem = item;
  selectedCost = cost;

  const modal = document.createElement('div');
  modal.id = 'redeemModal';
  modal.style.position = 'fixed';
  modal.style.top = '0';
  modal.style.left = '0';
  modal.style.width = '100%';
  modal.style.height = '100%';
  modal.style.background = 'rgba(0, 0, 0, 0.7)';
  modal.style.display = 'flex';
  modal.style.justifyContent = 'center';
  modal.style.alignItems = 'center';
  modal.style.zIndex = '999';

  modal.innerHTML = `
    <div style="
      background: rgba(255,255,255,0.12);
      backdrop-filter: blur(12px);
      padding: 28px;
      border-radius: 14px;
      box-shadow: 0 8px 28px rgba(0,0,0,0.6);
      text-align: center;
      max-width: 360px;
      color: #fff;
    ">
      <h3 style="margin-bottom: 10px;">Confirm Redemption</h3>
      <p>Redeem <strong>${item}</strong> for <strong>${cost} points</strong>?</p>
      <div style="display:flex; justify-content:center; gap:14px; margin-top:20px;">
        <button id="confirmRedeem" style="
          background:#10b981; color:#fff; border:none;
          padding:8px 18px; border-radius:8px; cursor:pointer;
          font-weight:600;
        ">Redeem</button>
        <button id="cancelRedeem" style="
          background:#ef4444; color:#fff; border:none;
          padding:8px 18px; border-radius:8px; cursor:pointer;
          font-weight:600;
        ">Cancel</button>
      </div>
    </div>
  `;

  document.body.appendChild(modal);

  document.getElementById('cancelRedeem').onclick = () => modal.remove();
  document.getElementById('confirmRedeem').onclick = () => {
    if (credits >= selectedCost) {
      credits -= selectedCost;
      document.getElementById('credits').textContent = credits;
      modal.remove();
      showCongrats(selectedItem);
    } else {
      modal.remove();
      showError(selectedItem, selectedCost - credits);
    }
  };
}

function showCongrats(item) {
  const congrats = document.createElement('div');
  congrats.style.position = 'fixed';
  congrats.style.top = '0';
  congrats.style.left = '0';
  congrats.style.width = '100%';
  congrats.style.height = '100%';
  congrats.style.background = 'rgba(0,0,0,0.7)';
  congrats.style.display = 'flex';
  congrats.style.justifyContent = 'center';
  congrats.style.alignItems = 'center';
  congrats.style.zIndex = '1000';

  congrats.innerHTML = `
    <div style="
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(12px);
      padding: 30px;
      border-radius: 16px;
      text-align: center;
      color: #fff;
      box-shadow: 0 8px 28px rgba(0,0,0,0.6);
      max-width: 380px;
    ">
      <h2 style="color:#10b981; margin-bottom:10px;">🎉 Congratulations!</h2>
      <p>You successfully redeemed <strong>${item}</strong>.</p>
      <button style="
        background:#3b82f6; color:#fff; border:none;
        padding:10px 20px; border-radius:8px; margin-top:20px;
        font-weight:600; cursor:pointer;
      " onclick="document.body.removeChild(this.parentNode.parentNode)">Close</button>
    </div>
  `;

  document.body.appendChild(congrats);
}

function showError(item, deficit) {
  const errorModal = document.createElement('div');
  errorModal.style.position = 'fixed';
  errorModal.style.top = '0';
  errorModal.style.left = '0';
  errorModal.style.width = '100%';
  errorModal.style.height = '100%';
  errorModal.style.background = 'rgba(0,0,0,0.7)';
  errorModal.style.display = 'flex';
  errorModal.style.justifyContent = 'center';
  errorModal.style.alignItems = 'center';
  errorModal.style.zIndex = '1000';

  errorModal.innerHTML = `
    <div style="
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(12px);
      padding: 30px;
      border-radius: 16px;
      text-align: center;
      color: #fff;
      box-shadow: 0 8px 28px rgba(0,0,0,0.6);
      max-width: 380px;
    ">
      <h2 style="color:#ef4444; margin-bottom:10px;">❌ Not Enough Points</h2>
      <p>You need <strong>${deficit} more points</strong> to redeem <strong>${item}</strong>.</p>
      <button style="
        background:#3b82f6; color:#fff; border:none;
        padding:10px 20px; border-radius:8px; margin-top:20px;
        font-weight:600; cursor:pointer;
      " onclick="document.body.removeChild(this.parentNode.parentNode)">Close</button>
    </div>
  `;

  document.body.appendChild(errorModal);
}
</script>
</body>
</html>
