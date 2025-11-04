<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
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

    * { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; font-family: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      background: url('bg.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #f2f6fb;
      line-height: 1.35;
    }

    header {
      position: fixed; top: 0; left: 0; right: 0;
      width: 100%; z-index: 100;
      padding: 8px 18px;
      display: flex; justify-content: space-between; align-items: center;
      background-color: #1e293b; color: #fff;
      box-shadow: 0 4px 16px rgba(3,7,18,0.4);
      flex-wrap: wrap; row-gap: 8px;
    }

    .brand { display: flex; gap: 14px; align-items: center; flex-wrap: wrap; }
    .logo img { width: 46px; height: 46px; border-radius: 8px; object-fit: cover; }
    .title-wrap h1 { font-size: 16px; font-weight: 600; }

    .profile-info {
      display: flex; align-items: center; gap: 12px;
      background: rgba(255, 255, 255, 0.08);
      padding: 8px 16px;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }
    .profile-info .avatar {
      background: linear-gradient(135deg, #3b82f6, #2563eb);
      color: #fff;
      width: 36px; height: 36px;
      display: flex; align-items: center; justify-content: center;
      border-radius: 50%;
      font-weight: 700; font-size: 14px;
    }
    .profile-info .user-details { display: flex; flex-direction: column; line-height: 1.1; }
    .profile-info .user-details strong { font-size: 14px; }
    .profile-info .user-details span { font-size: 12px; color: #ccc; }
    .profile-info .credits {
      background: linear-gradient(135deg, #10b981, #059669);
      color: #fff;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 10px;
      font-size: 12px;
    }

    .container { flex: 1; padding: 90px 18px 20px; display: flex; flex-direction: column;   background: url('images/bg.jpg') no-repeat center center fixed;
  background-size: cover;
  background-attachment: fixed;
  background-blend-mode: overlay; }
    .main { display: grid; grid-template-columns: 260px 1fr; gap: 16px; flex: 1; }

    .sidebar, .content {
      border-radius: 12px; padding: 14px;
      border: 1px solid rgba(255,255,255,0.04); 
      box-shadow: 0 6px 18px rgba(2,6,23,0.45);
      display: flex; flex-direction: column;
    }
    .sidebar { min-height: 380px; background: var(--glass); }
    .content { background: var(--glass); flex: 1; }

    .hero {
      display: flex; align-items: center; justify-content: space-between;
      padding: 18px; border-radius: 12px;
      border: 1px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.06);
      flex-wrap: wrap; row-gap: 10px; margin-bottom: 20px;
    }
    .hero h2 { font-size: 20px; font-weight: 700; }
    .hero p { color: #fff; text-shadow: 0 1px 4px rgba(2,6,23,0.6); }
    .hero .back-btn {
      background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
      color: #071033; font-weight: 700; border: none; border-radius: 8px;
      padding: 10px 18px; cursor: pointer; transition: var(--transition);
      box-shadow: 0 4px 12px rgba(2,6,23,0.35);
    }
    .hero .back-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(2,6,23,0.45);
    }

    .redeem-section {
      background: rgba(255,255,255,0.08);
      border-radius: 18px;
      padding: 30px;
      backdrop-filter: blur(12px);
      box-shadow: 0 10px 28px rgba(0,0,0,0.45);
      border: 1px solid rgba(255,255,255,0.12);
      text-align: center;
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .inventory-container {
      display: flex; justify-content: center; align-items: stretch; gap: 20px;
      flex-wrap: wrap; margin-top: 20px;
    }

    .inventory-card {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 20px rgba(0,0,0,0.2);
      padding: 20px;
      width: 250px;
      text-align: center;
      color: white;
      transition: transform 0.3s, box-shadow 0.3s;
      flex: 1 1 220px;
      max-width: 260px;
      min-height: 260px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .inventory-card:hover { transform: translateY(-5px); box-shadow: 0 6px 25px rgba(255,255,255,0.2); }
    .inventory-card h3 { margin-bottom: 15px; font-weight: 600; }

    .option-list { list-style: none; padding: 0; margin: 0; }
    .inventory-card .option-item {
      background: linear-gradient(135deg, #3b82f6, #1e40af);
      color: #fff; padding: 10px 16px; border-radius: 10px;
      margin: 8px 0; cursor: pointer; font-weight: 600; border: none; transition: all 0.3s ease;
    }
    .inventory-card .option-item:hover {
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(59,130,246,0.5);
    }

    .inventory-card button {
      background: linear-gradient(135deg, #10b981, #059669);
      color: #fff; padding: 10px 18px; border-radius: 10px; border: none;
      cursor: pointer; font-weight: 600; transition: all 0.3s ease;
    }
    .inventory-card button:hover {
      background: linear-gradient(135deg, #059669, #047857);
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(16,185,129,0.5);
    }

    footer {
      width: 100%; background: #1e293b; text-align: center; padding: 20px 10px; color: #fff;
    }

    @media (max-width: 768px) {
      header { flex-direction: column; text-align: center; gap: 6px; padding-bottom: 12px; }
      .main { grid-template-columns: 1fr; }
      .sidebar { order: 2; min-height: auto; }
      .content { order: 1; }
      .hero { flex-direction: column; align-items: flex-start; }
      .hero .back-btn { align-self: flex-end; margin-top: 10px; }
      .redeem-section { padding: 20px; }
    }
  </style>
</head>

<body>
  <header>
    <div class="brand">
      <div class="logo"><img src="images/logorewards.jpg" alt="SPRS Logo"></div>
      <div class="title-wrap"><h1>Student Point-Reward System</h1></div>
    </div>
    <div class="profile-info">
      <div class="avatar">ST</div>
      <div class="user-details">
        <strong>John Student</strong>
        <span>Student</span>
      </div>
      <div class="credits">Credits: <span id="credits">120</span></div>
    </div>
  </header>

  <div class="container">
    <section class="content">
      <div class="hero">
        <div class="info">
          <h2>View Inventory</h2>
          <p>Check available vouchers, tickets, and event certificates below.</p>
        </div>
        <button class="back-btn" onclick="window.location.href='/SPRS/SPRS-Group1/student/student_index.php'">⬅ Back to Dashboard</button>
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

  <footer>
    <div style="font-weight:700; font-size:16px; margin-bottom:12px;">Contact Us:</div>
    <div style="font-size:13px; display:flex; justify-content:center; gap:12px; flex-wrap:wrap; align-items:center; margin-bottom:12px; color:#93c5fd;">
      <div>📧 sprsystem@gmail.com</div>
      <span style="color:#ccc;">|</span>
      <div>📞 09123456789</div>
      <span style="color:#ccc;">|</span>
      <div style="display:flex; align-items:center; gap:6px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#93c5fd" viewBox="0 0 24 24">
          <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987H8.078v-2.89h2.36V9.797c0-2.337 1.393-3.625 3.52-3.625.996 0 2.04.178 2.04.178v2.25h-1.151c-1.137 0-1.492.705-1.492 1.43v1.716h2.54l-.406 2.89h-2.134V21.9C18.343 21.128 22 16.991 22 12z"/>
        </svg>
        <a href="https://www.facebook.com/StudentPointRewardSystem" target="_blank" style="color:#93c5fd; text-decoration:none;">Student Point Reward System</a>
      </div>
    </div>
    <div style="font-size:13px;">© 2025 Student Point-Reward System. All rights reserved.</div>
  </footer>

  <script>
    let credits = 120;

    function redeemItem(item, cost) {
      const modal = document.createElement('div');
      modal.style.cssText = `
        position:fixed; top:0; left:0; width:100%; height:100%;
        background:rgba(0,0,0,0.7); display:flex; justify-content:center; align-items:center; z-index:999;
      `;
      modal.innerHTML = `
        <div style="background: rgba(255,255,255,0.12); backdrop-filter: blur(12px); padding:28px; border-radius:14px; box-shadow:0 8px 28px rgba(0,0,0,0.6); text-align:center; max-width:360px; color:#fff;">
          <h3 style="margin-bottom:10px;">Confirm Redemption</h3>
          <p>Redeem <strong>${item}</strong> for <strong>${cost} points</strong>?</p>
          <div style="display:flex; justify-content:center; gap:14px; margin-top:20px;">
            <button id="confirmRedeem" style="background:#10b981;color:#fff;border:none;padding:8px 18px;border-radius:8px;cursor:pointer;font-weight:600;">Redeem</button>
            <button id="cancelRedeem" style="background:#ef4444;color:#fff;border:none;padding:8px 18px;border-radius:8px;cursor:pointer;font-weight:600;">Cancel</button>
          </div>
        </div>
      `;
      document.body.appendChild(modal);

      document.getElementById('cancelRedeem').onclick = () => modal.remove();
      document.getElementById('confirmRedeem').onclick = () => {
        if (credits >= cost) {
          credits -= cost;
          document.getElementById('credits').textContent = credits;
          modal.remove();
          showMessage(`🎉 Congratulations! You successfully redeemed ${item}.`, '#10b981');
        } else {
          modal.remove();
          showMessage(`❌ Not enough points. You need ${cost - credits} more points to redeem ${item}.`, '#ef4444');
        }
      };
    }

    function showMessage(message, color) {
      const modal = document.createElement('div');
      modal.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);display:flex;justify-content:center;align-items:center;z-index:1000;';
      modal.innerHTML = `
        <div style="background:rgba(255,255,255,0.15);backdrop-filter:blur(12px);padding:30px;border-radius:16px;text-align:center;color:#fff;box-shadow:0 8px 28px rgba(0,0,0,0.6);max-width:380px;">
          <h2 style="color:${color};margin-bottom:10px;">${message.split(' ')[0]}</h2>
          <p>${message.substring(message.indexOf(' ')+1)}</p>
          <button style="background:#3b82f6;color:#fff;border:none;padding:10px 20px;border-radius:8px;margin-top:20px;font-weight:600;cursor:pointer;" onclick="document.body.removeChild(this.parentNode.parentNode)">Close</button>
        </div>
      `;
      document.body.appendChild(modal);
    }
  </script>
</body>
</html>
