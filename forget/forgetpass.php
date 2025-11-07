<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Point Reward System - Forgot Password</title>
  <style>
    :root {
      --muted: #b5bcc8;
      --accent-1: #93c5fd;
      --accent-2: #3b82f6;
      --footer-bg: #1e293b;
      --footer-text: #fff;
    }

    body {
      margin: 0;
      font-family: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background: url("Background.png") no-repeat center center fixed;
      background-size: cover;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      color: #fff;
    }

    main { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; }

    .login-card {
      background: rgba(0, 0, 0, 0.6);
      border-radius: 15px;
      padding: 60px;
      width: 460px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.4);
      text-align: center;
      color: #fff;
      margin-top: 50px;
    }

    .login-card img { width: 90px; height: 90px; margin-bottom: 10px; }
    .login-card h1 { margin: 5px 0 2px; font-size: 20px; font-weight: bold; }
    .login-card p.platform { font-size: 14px; color: #cbd5e1; margin-bottom: 15px; }
    .login-card p.subtitle { font-size: 14px; color: #cbd5e1; margin-bottom: 20px; }
    .login-card h2 { margin-bottom: 20px; font-size: 26px; }

    .input-group { margin-bottom: 20px; text-align: left; }
    .input-group label { font-size: 15px; color: #ddd; margin-bottom: 5px; display: block; }
    .input-group input {
      width: 100%; padding: 14px; border: 1px solid #555; border-radius: 25px;
      outline: none; font-size: 15px; background: #1e293b; color: #fff;
    }
    .input-group input:focus { border-color: #2563eb; box-shadow: 0 0 6px rgba(37,99,235,0.7); }

    .btn {
      width: 100%; padding: 14px; border-radius: 25px; font-weight: bold;
      cursor: pointer; transition: 0.3s ease; font-size: 15px;
      background: linear-gradient(to right, #3b82f6, #2563eb); color: #fff; border: none;
    }
    .btn:hover { background: linear-gradient(to right, #2563eb, #1d4ed8); }

    .extra-links { margin: 15px 0; }
    .extra-links a { font-size: 14px; color: #60a5fa; text-decoration: none; }
    .extra-links a:hover { text-decoration: underline; }

    footer {
      width: 100%; background: var(--footer-bg); text-align: center;
      padding: 25px 15px; margin-top: auto; color: var(--footer-text);
      box-shadow: 0 -2px 10px rgba(0,0,0,0.3);
    }
    footer .contact-title { font-weight: 700; font-size: 16px; margin-bottom: 12px; }
    footer .contact-info { font-size: 13px; display: flex; justify-content: center; gap: 12px; flex-wrap: wrap; margin-bottom: 12px; color: var(--accent-1); }
    footer p { font-size: 13px; color: #fff; margin: 0; }
  </style>
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $phone = trim($_POST["phone"]);
    if (!empty($phone)) {
        // Placeholder for SMS API (e.g., send actual OTP)
        // sendSMS($phone, "Your OTP code is 123456");
        header("Location: reset-confirm.php?phone=" . urlencode($phone));
        exit;
    } else {
        echo "<script>alert('Please enter your mobile number.');</script>";
    }
}
?>

<main>
  <div class="login-card">
    <img src="RewardLogo.png" alt="System Logo">
    <h1>STUDENT POINT REWARD SYSTEM</h1>
    <p class="platform">The Official Reward Platform</p>
    <p class="subtitle">Reset your password and regain access to your account.</p>
    <h2>Forgot Password</h2>

    <form method="POST" action="">
      <div class="input-group">
        <label for="phone">Enter your registered Mobile Number:</label>
        <input type="tel" id="phone" name="phone" placeholder="Enter your mobile number" required>
      </div>

      <button type="submit" class="btn">Send Reset Code via SMS</button>

      <div class="extra-links">
        <a href="/SPRS/SPRS-Group1/login.php">← Back to Login</a>
      </div>
    </form>
  </div>
</main>

<footer>
  <div class="contact-title">Contact Us:</div>
  <div class="contact-info">
    <div>📧 sprsystem@gmail.com</div>
    <span style="color:#ccc;">|</span>
    <div>📞 09123456789</div>
  </div>
  <p>© 2025 Student Point-Reward System. All rights reserved.</p>
</footer>
</body>
</html>