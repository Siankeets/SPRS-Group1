<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Distribute Points — Admin</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --accent-blue: #2563eb;
      --accent-hover: #1d4ed8;
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: 'Inter', system-ui;
      background: url('images/bg.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      line-height: 1.4;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      position: fixed;
      top: 0; left: 0; right: 0;
      background: #1e293b;
      padding: 12px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 6px 20px rgba(3, 7, 18, 0.45);
      z-index: 10;
    }

    header .left {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    header img { width: 42px; height: 42px; border-radius: 10px; }
    header h1 { font-size: 16px; margin: 0; color: #f2f6fb; }

    .back-btn {
      background: var(--accent-blue);
      color: white;
      text-decoration: none;
      padding: 8px 16px;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 600;
      transition: 0.2s;
    }

    .back-btn:hover {
      background: var(--accent-hover);
    }

    .container {
      max-width: 460px;
      margin: 130px auto 50px;
      padding: 30px 25px;
      background: rgba(0, 0, 0, 0.6);
      border-radius: 16px;
      color: #fff;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
    }

    h2 {
      text-align: center;
      margin-top: 0;
      color: #fff;
      font-weight: 700;
    }

    p { text-align: center; color: #e5e7eb; }

    label {
      display: block;
      margin: 12px 0 6px;
      color: #fff;
      font-weight: 600;
    }

    input, textarea {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.9);
      font-size: 15px;
      color: #000;
    }

    input:focus, textarea:focus {
      outline: none;
      box-shadow: 0 0 0 3px var(--accent-blue);
    }

    button {
      margin-top: 20px;
      background: var(--accent-blue);
      border: none;
      padding: 12px 25px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      color: #fff;
      width: 100%;
      transition: 0.2s;
    }

    button:hover {
      background: var(--accent-hover);
    }

    .qr-section {
      display: none;
      text-align: center;
      background: rgba(0, 0, 0, 0.6);
      border-radius: 16px;
      padding: 25px;
      margin-top: 25px;
      color: #fff;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
    }

    .qr-section img {
      background: #fff;
      padding: 10px;
      border-radius: 8px;
      margin-top: 10px;
      width: 200px;
      height: 200px;
    }

    .delete-btn {
      background: #dc2626;
      color: white;
      border: none;
      padding: 12px 25px;
      margin-top: 18px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      width: 100%;
      transition: 0.2s;
    }

    .delete-btn:hover {
      background: #b91c1c;
    }

    footer {
      width: 100%;
      background: #1e293b;
      text-align: center;
      padding: 20px 10px;
      margin-top: auto;
    }

    footer div { color: #93c5fd; }

    @media (max-width: 600px) {
      header { flex-direction: column; gap: 10px; }
      header img { width: 36px; height: 36px; }
      header h1 { font-size: 15px; }
      .container { margin: 100px 15px; padding: 25px 20px; }
      .back-btn { font-size: 13px; padding: 7px 14px; }
    }
  </style>
</head>
<body>
  <header>
    <div class="left">
      <img src="images/logorewards.jpg" alt="SPRS Logo">
      <h1>Distribute Points</h1>
    </div>
    <a href="staff_index.php" class="back-btn">⬅ Back to Dashboard</a>
  </header>

  <div class="container">
    <h2>Give Points to Students</h2>
    <p>Fill out the details below to generate a QR code students can redeem.</p>

    <form id="givePointsForm">
      <label for="points">Points Amount:</label>
      <input type="number" id="points" name="points" min="10" max="50" placeholder="Enter points to give (Minimum of 10, maximum of 50 points)" required>

      <label for="reason">Reason / Description:</label>
      <textarea id="reason" name="reason" rows="3" placeholder="e.g. Perfect attendance" required></textarea>

      <button type="submit">Generate QR Code</button>
    </form>

    <div id="qrSection" class="qr-section">
      <h3>Generated QR Code</h3>
      <img id="qrImage" src="" alt="Generated QR Code">
      <p><strong>Points:</strong> <span id="qrPoints"></span></p>
      <p>Scan this QR to redeem points.</p>
      <button class="delete-btn" id="deleteBtn">Delete QR</button>
    </div>
  </div>

 <footer>
  <div style="font-weight:700; font-size:16px; margin-bottom:12px;">Contact Us:</div>
  <div style="font-size:13px; display:flex; justify-content:center; gap:12px; flex-wrap:wrap; align-items:center; margin-bottom:12px; color:#93c5fd;">
    <div>📧 sprsystem@gmail.com</div>
    <span style="color:#ccc;">|</span>
    <div>📞 09123456789</div>
    <span style="color:#ccc;">|</span>
    <!-- Facebook -->
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
  <div style="font-size:13px;color:#fff;">
    © 2025 Student Point-Reward System. All rights reserved.
  </div>
</footer>

  <script>
    const form = document.getElementById('givePointsForm');
    const qrSection = document.getElementById('qrSection');
    const qrImage = document.getElementById('qrImage');
    const qrPoints = document.getElementById('qrPoints');
    const deleteBtn = document.getElementById('deleteBtn');

    let currentCode = null;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const points = form.points.value.trim();
      const reason = form.reason.value.trim();

      if (!points || !reason) {
        alert('Please fill out all fields.');
        return;
      }

      try {
        const response = await fetch('/SPRS-Group1/admin/generate_qr.php', {

          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `points=${encodeURIComponent(points)}&reason=${encodeURIComponent(reason)}`
        });

        const result = await response.json();

        if (result.success) {
          currentCode = result.code;
          qrImage.src = result.qrPath;
          qrPoints.textContent = result.points;
          qrSection.style.display = 'block';
          form.style.display = 'none';
        } else {
          alert('Error: ' + result.message);
        }
      } catch (err) {
        console.error(err);
        alert('Unexpected server response.');
      }
    });

    deleteBtn.addEventListener('click', async () => {
      if (!currentCode) return alert('No QR code to delete.');

      if (!confirm('Are you sure you want to delete this QR code?')) return;

      try {
        const response = await fetch('/SPRS-Group1/admin/generate_qr.php',
 {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `delete=${encodeURIComponent(currentCode)}`
        });

        const result = await response.json();
        if (result.success) {
          alert('QR code deleted.');
          qrSection.style.display = 'none';
          form.style.display = 'block';
          form.reset();
        } else {
          alert('Delete failed: ' + result.message);
        }
      } catch (err) {
        console.error(err);
        alert('Server error while deleting QR.');
      }
    });
  </script>
</body>
</html>