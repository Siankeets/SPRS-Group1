<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Reward System</title>
  <style>
    :root {
      --accent-1: #60a5fa;
      --accent-2: #2563eb;
      --btn-hover: #3b82f6;
      --transition: 250ms ease;
    }

    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: url('bg.jpg') no-repeat center center fixed;
      background-size: cover;
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: #fff;
      overflow: hidden;
      position: relative;
    }

    .overlay {
      position: relative;
      z-index: 1;
      background: rgba(0, 0, 0, 0.65); /* semi-dark box lang */
      padding: 50px 70px;
      border-radius: 20px;
      text-align: center;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
      max-width: 500px;
    }

@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap');

h1 {
  font-family: 'Montserrat', sans-serif;
  font-size: 44px;
  font-weight: 700;
  margin-bottom: 12px;
  background: linear-gradient(90deg, #4fc3f7, #0288d1, #81d4fa, #0288d1, #4fc3f7);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  text-shadow: 2px 2px 6px rgba(0,0,0,0.3);
  letter-spacing: 1px;
  background-size: 200% auto;
  animation: shine 3s linear infinite;
}

@keyframes shine {
  0% { background-position: 0% center; }
  50% { background-position: 100% center; }
  100% { background-position: 0% center; }
}


    p {
      font-size: 18px;
      margin-bottom: 28px;
      color: #e5eaf0;
    }

    .btn {
      display: inline-block;
      background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
      color: #fff;
      font-size: 18px;
      padding: 14px 36px;
      border-radius: 50px;
      text-decoration: none;
      font-weight: 600;
      letter-spacing: 0.5px;
      position: relative;
      transition: var(--transition);
      box-shadow: 0 0 20px rgba(37, 99, 235, 0.6);
      overflow: hidden;
    }

    .btn:hover {
      background: linear-gradient(135deg, var(--btn-hover), var(--accent-2));
      transform: translateY(-3px) scale(1.05);
      box-shadow: 0 0 25px rgba(96, 165, 250, 0.9);
    }

   footer {
  position: absolute;
  bottom: 0;
  width: 100%;
  text-align: center;
  font-size: 14px;
  color: #fff;
  font-weight: 500;
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
    <a href="dashboard.html" class="btn">Get Started</a>
  </div>

  <footer>
    © 2025 Student Point-Reward System
  </footer>
</body>
</html>
