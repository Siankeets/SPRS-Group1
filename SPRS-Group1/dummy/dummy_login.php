<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // change if needed
$password = "";     // change if needed
$dbname = "sprs_dummydb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $user = trim($_POST["username"]);
  $pass = trim($_POST["password"]);

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $user, $pass);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // Store user info in session
    $_SESSION["user_id"] = $row["id"];
    $_SESSION["username"] = $row["username"];
    $_SESSION["role"] = $row["role"];
    $_SESSION["name"] = $row["name"];

    // Redirect to the dummy index page
    header("Location: dummy_index.php");
    exit();
  } else {
    $error = "Invalid username or password!";
  }

  $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login | SPRS Dummy</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<style>
  * {
    box-sizing: border-box;
    transition: all 0.3s ease;
  }

  body {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #007bff, #00c6ff);
    height: 100vh;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .login-container {
    background: white;
    padding: 40px 35px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    width: 360px;
    animation: fadeIn 0.8s ease-in-out;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
    font-weight: 600;
  }

  input {
    width: 100%;
    padding: 12px 14px;
    margin: 10px 0 18px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
  }

  input:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.2);
  }

  button {
    width: 100%;
    padding: 12px;
    background: linear-gradient(90deg, #007bff, #00b3ff);
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: 600;
    cursor: pointer;
    font-size: 15px;
    letter-spacing: 0.3px;
  }

  button:hover {
    background: linear-gradient(90deg, #0062cc, #0095e0);
    transform: scale(1.02);
  }

  .error {
    background: #ffe5e5;
    color: #d10000;
    text-align: center;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 15px;
    font-size: 14px;
  }

  .footer-text {
    text-align: center;
    font-size: 13px;
    color: #888;
    margin-top: 15px;
  }

  .footer-text a {
    color: #007bff;
    text-decoration: none;
  }

  .footer-text a:hover {
    text-decoration: underline;
  }
</style>
</head>
<body>
  <form class="login-container" method="POST" action="">
    <h2>Login</h2>
    <?php if ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <input type="text" name="username" placeholder="Username" required autocomplete="off">
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
    <div class="footer-text">
      <p>SPRS Dummy System © <?= date("Y") ?></p>
    </div>
  </form>
</body>
</html>
