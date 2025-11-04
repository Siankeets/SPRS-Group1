<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Logout — Admin Dashboard</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --muted: #b5bcc8;
      --accent-blue: #2563eb;
      --accent-hover: #1d4ed8;
      --glass: rgba(0, 0, 0, 0.45);
      --glass-strong: rgba(0, 0, 0, 0.65);
    }

    * { box-sizing: border-box; }

    body {
      font-family: 'Inter', sans-serif;
      background: url('bg.jpg') center/cover no-repeat fixed;
      margin: 0;
      color: #fff;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Header */
    header {
      background: rgba(0,0,0,0.8);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 24px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.45);
    }
    header img {
      width: 42px;
      height: 42px;
      border-radius: 10px;
      margin-right: 10px;
    }
    header h1 {
      font-size: 16px;
      color: #f2f6fb;
      margin: 0;
      font-weight: 600;
    }
    .header-left {
      display: flex;
      align-items: center;
    }

    /* Main Card */
    .card {
      background: var(--glass);
      border: 1px solid rgba(255,255,255,0.1);
      padding: 40px 30px;
      border-radius: 16px;
      text-align: center;
      box-shadow: 0 10px 24px rgba(0,0,0,0.4);
      backdrop-filter: blur(10px);
      width: 100%;
      max-width: 400px;
      margin: auto;
      margin-top: 80px;
      animation: fadeIn 0.6s ease;
    }

    h2 {
      font-size: 24px;
      margin-bottom: 12px;
      font-weight: 700;
    }

    p {
      color: var(--muted);
      margin-bottom: 30px;
      font-size: 14px;
    }

    .btn {
      display: inline-block;
      background: var(--accent-blue);
      color: #fff;
      font-weight: 600;
      padding: 12px 28px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: all .25s ease;
      text-decoration: none;
      font-size: 14px;
    }

    .btn:hover {
      background: var(--accent-hover);
      transform: translateY(-2px);
    }

    /* Footer */
    footer {
      background: rgba(0,0,0,0.75);
      backdrop-filter: blur(8px);
      color: #e5e7eb;
      text-align: center;
      padding: 20px 12px;
      font-size: 14px;
      margin-top: auto;
      box-shadow: 0 -4px 10px rgba(0,0,0,0.3);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Mobile View */
    @media (max-width: 768px) {
      header {
        flex-direction: column;
        text-align: center;
        gap: 10px;
      }
      .card {
        margin: 60px 15px 30px;
        padding: 30px 20px;
      }
      .btn {
        width: 100%;
      }
    }
  </style>
</head>
<body>

  <!-- HEADER -->
  <header>
    <div class="header-left">
      <img src="logorewards.jpg" alt="SPRS Logo">
      <h1>Student Point Reward System</h1>
    </div>
  </header>

  <!-- MAIN CONTENT -->
  <div class="card">
    <h2>Logged Out Successfully</h2>
    <p>You have been logged out of the Admin Dashboard.<br>
       Click below to return to the login page.</p>
    <a href="login.html" class="btn">Go to Login</a>
  </div>

  <!-- FOOTER -->
  <footer>
    © 2025 Student Point Reward System. All rights reserved.
  </footer>

  <script>
    // Optional: automatic redirect after 5 seconds
    setTimeout(()=>{
      window.location.href = 'login.html';
    }, 5000);
  </script>

</body>
</html>