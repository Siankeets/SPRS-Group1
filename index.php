<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Reward System</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Montserrat:wght@700&display=swap" rel="stylesheet" />

  <style>
    /* ===== Root Variables ===== */
    :root {
      --accent-1: #60a5fa;
      --accent-2: #2563eb;
      --btn-hover: #3b82f6;
      --transition: 250ms ease;
    }

    /* ===== Global Styles ===== */
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      color: #fff;
      background: url('bg.jpg') no-repeat center center fixed;
      background-size: cover;
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      position: relative;
    }

    /* ===== Overlay Box ===== */
    .overlay {
      position: relative;
      z-index: 1;
      background: rgba(0, 0, 0, 0.65);
      padding: 50px 70px;
      border-radius: 20px;
      text-align: center;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
      max-width: 500px;
    }

    /* ===== Heading Animation ===== */
    h1 {
      font-family: 'Montserrat', sans-serif;
      font-size: 44px;
      font-weight: 700;
      margin-bottom: 12px;
      background: linear-gradient(90deg, #4fc3f7, #0288d1, #81d4fa, #0288d1, #4fc3f7);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.3);
      letter-spacing: 1px;
      background-size: 200% auto;
      animation: shine 3s linear infinite;
    }

    @keyframes shine {
      0% { background-position: 0% center; }
      50% { background-position: 100% center; }
      100% { background-position: 0% center; }
    }

    /* ===== Paragraph ===== */
    p {
      font-size: 18px;
      margin-bottom: 28px;
      color: #e5eaf0;
    }

    /* ===== Button ===== */
    .btn {
      display: inline-block;
      padding: 14px 36px;
      font-size: 18px;
      font-weight: 600;
      color: #fff;
      text-decoration: none;
      letter-spacing: 0.5px;
      border-radius: 50px;
      background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
      box-shadow: 0 0 20px rgba(37, 99, 235, 0.6);
      transition: var(--transition);
    }

    .btn:hover {
      background: linear-gradient(135deg, var(--btn-hover), var(--accent-2));
      transform: translateY(-3px) scale(1.05);
      box-shadow: 0 0 25px rgba(96, 165, 250, 0.9);
    }

    /* ===== Footer ===== */
    footer {
      position: absolute;
      bottom: 0;
      width: 100%;
      text-align: center;
      font-size: 14px;
      font-weight: 500;
      color: #fff;
      background: #1e293b;
      padding: 10px 0;
      z-index: 2;
    }
  </style>
</head>

<body>
  <div class="overlay">
    <h1>Welcome to Student Reward System</h1>
    <p>Track your credits, redeem rewards, and join exciting events easily.</p>
    <a href="login.php" class="btn">Get Started</a> <!--switched href to login-test -->
  </div>

  <footer>
    © 2025 Student Point-Reward System
  </footer>
</body>
</html>