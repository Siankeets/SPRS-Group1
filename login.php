<?php
session_start();
include('db_connect.php'); // Make sure this connects to your DB
// include('dummy/connection_dummydb.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // --- Basic validation ---
    if (!preg_match("/^[A-Za-z0-9_]{3,20}$/", $username)) {
        $error = "Username must be 3–20 characters long and contain only letters, numbers, and underscores.";
    } elseif (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{5,}$/", $password)) {
        $error = "Password must be at least 5 characters and contain letters and numbers.";
    } else {
        // --- Check database ---
        $conn->select_db('sprs_dummydb');

        $stmt = $conn->prepare("SELECT id, username, password, role, points, name, department, program, major 
                                FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user) {
            // Assuming passwords are stored as plain text (not recommended for production)
            // If hashed, use password_verify($password, $user['password'])
            if ($password === $user['password']) {
                // Set session
                $_SESSION['userID'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['points'] = $user['points'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['department'] = $user['department'];
                $_SESSION['program'] = $user['program'];
                $_SESSION['major'] = $user['major'];

                // Redirect based on role
                if ($user['role'] === 'student') {
                    header("Location: /SPRS/SPRS-Group1/student/student_index.php");
                    exit();
                } elseif ($user['role'] === 'admin') {
                    header("Location: /SPRS/SPRS-Group1/admin/staff_index.php");
                    exit();
                } else {
                    $error = "Unknown role assigned to this user.";
                }
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Username not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login — Student Reward System</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Montserrat:wght@700&display=swap" rel="stylesheet">

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

    .login-container {
      position: relative;
      z-index: 1;
      background: rgba(0, 0, 0, 0.45);
      padding: 50px 60px;
      border-radius: 20px;
      text-align: center;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
      max-width: 420px;
      width: 100%;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.12);
    }

    h2 {
      font-family: 'Montserrat', sans-serif;
      font-size: 38px;
      font-weight: 700;
      margin-bottom: 20px;
      background: linear-gradient(90deg, #4fc3f7, #0288d1, #81d4fa, #0288d1, #4fc3f7);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      letter-spacing: 1px;
      background-size: 200% auto;
      animation: shine 3s linear infinite;
    }

    @keyframes shine {
      0% { background-position: 0% center; }
      50% { background-position: 100% center; }
      100% { background-position: 0% center; }
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    input[type="text"],
    input[type="password"] {
      padding: 12px 16px;
      border-radius: 8px;
      border: none;
      outline: none;
      font-size: 16px;
      background: rgba(255, 255, 255, 0.10);
      color: #fff;
      transition: var(--transition);
    }

    input::placeholder {
      color: #cbd5e1;
    }

    input:focus {
      background: rgba(255, 255, 255, 0.25);
      box-shadow: 0 0 10px rgba(96, 165, 250, 0.8);
    }

    button[type="submit"] {
      margin-top: 10px;
      background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
      border: none;
      border-radius: 8px;
      padding: 14px;
      color: #fff;
      font-size: 18px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      box-shadow: 0 0 15px rgba(37, 99, 235, 0.6);
    }

    button[type="submit"]:hover {
      background: linear-gradient(135deg, var(--btn-hover), var(--accent-2));
      transform: translateY(-3px) scale(1.05);
      box-shadow: 0 0 25px rgba(96, 165, 250, 0.9);
    }

    .forgot-password {
      color: var(--accent-1);
      text-decoration: none;
      font-size: 14px;
      transition: var(--transition);
    }

    .forgot-password:hover {
      color: var(--accent-2);
    }

    .error-message {
      color: #f87171;
      margin-bottom: 12px;
      font-weight: 500;
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

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
</head>

<body>
  <div class="login-container">
    <h2>Welcome!</h2>

    <?php if (!empty($error)): ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form id="loginForm" method="POST" action="/SPRS/SPRS-Group1/login.php" novalidate>
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <a href="forget/forgot.html" class="forgot-password">Forgot password?</a>
      <button type="submit">Log In</button>
    </form>
  </div>

  <footer>© 2025 Student Point-Reward System</footer>

  <script>
    $(document).ready(function () {
      $("#loginForm").validate({
        rules: {
          username: {
            required: true,
            minlength: 3,
            maxlength: 20,
            pattern: /^[A-Za-z0-9_]+$/
          },
          password: {
            required: true,
            minlength: 5,
            pattern: /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]+$/
          }
        },
        messages: {
          username: {
            required: "Please enter your username",
            minlength: "Username must be at least 3 characters",
            maxlength: "Username cannot exceed 20 characters",
            pattern: "Username can only contain letters, numbers, and underscores"
          },
          password: {
            required: "Please enter your password",
            minlength: "Password must be at least 5 characters",
            pattern: "Password must include letters and numbers"
          }
        },
        errorPlacement: function (error, element) {
          error.insertAfter(element);
          error.css("color", "#f87171");
        }
      });
    });
  </script>
</body>
</html>