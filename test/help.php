<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Student Point-Reward System — Help Center</title>
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

    .help-section {
      margin-top: 30px;
      background: rgba(255,255,255,0.08);
      border-radius: 18px;
      padding: 30px;
      backdrop-filter: blur(12px);
      box-shadow: 0 10px 28px rgba(0,0,0,0.45);
      border: 1px solid rgba(255,255,255,0.12);
      text-align: center;
    }

    textarea {
      width: 100%;
      padding: 12px;
      border-radius: 10px;
      border: 1px solid rgba(255,255,255,0.2);
      background: rgba(255,255,255,0.1);
      color: #fff;
      resize: none;
      font-family: inherit;
      font-size: 14px;
      height: 120px;
      margin-bottom: 15px;
    }

    textarea:focus {
      outline: none;
      border-color: var(--accent-2);
      background: rgba(255,255,255,0.15);
    }

    .help-section button {
      background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
      border: none;
      border-radius: 10px;
      padding: 12px 20px;
      color: #071033;
      font-weight: 700;
      font-size: 15px;
      cursor: pointer;
      transition: var(--transition);
      box-shadow: 0 4px 16px rgba(2,6,23,0.4);
    }

    .help-section button:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 26px rgba(2,6,23,0.5);
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
            <h2>Help Center</h2>
            <p>Need assistance? You can send your question to the admin below.</p>
          </div>
          <div>
            <div style="font-size:13px;color:var(--muted)">Role:</div>
            <div style="font-weight:700">Student</div>
          </div>
        </div>

        <div class="help-section" id="helpPanel">
          <h3>Ask the Admin</h3>
          <textarea id="message" placeholder="Type your question here..."></textarea>
          <button onclick="sendMessage()">📩 Send Message</button>
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
  function sendMessage() {
    const msg = document.getElementById('message').value.trim();
    const panel = document.getElementById('helpPanel');

    if (!msg) {
      alert("Please type your message before sending.");
      return;
    }

    panel.innerHTML = `
      <h3 style="color:var(--success)">✅ Message Sent</h3>
      <p>Thank you! Your message has been sent to the admin. Please wait for a response.</p>
      <button onclick="resetForm()">↩ Send Another</button>
    `;
  }

  function resetForm() {
    const panel = document.getElementById('helpPanel');
    panel.innerHTML = `
      <h3>Ask the Admin</h3>
      <textarea id="message" placeholder="Type your question here..."></textarea>
      <button onclick="sendMessage()">📩 Send Message</button>
    `;
  }
  </script>
</body>
</html>
