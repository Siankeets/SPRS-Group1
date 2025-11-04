<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: dummy_login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$points = $_SESSION['points'];
$name = $_SESSION['name']; // testing name column
?>

<!DOCTYPE html>
<html>
<head>
    <title>SPRS Dashboard</title>
    <style>
        body { font-family: Arial; background: #eef2f3; text-align: center; }
        .card { background: white; padding: 20px; margin: 100px auto; width: 400px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
        .logout { color: red; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Welcome, <?= htmlspecialchars($name) ?>!</h2> <!--$username changed to $name for test-->
        <p>Role: <strong><?= htmlspecialchars($role) ?></strong></p>
        <?php if ($role == 'student'): ?>
            <p>Your current points: <strong><?= htmlspecialchars($points) ?></strong></p>
        <?php else: ?>
            <p>Admin Panel Access Granted ✅</p>
        <?php endif; ?>
        <br>
        <a href="dummy_logout.php" class="logout">Logout</a>
    </div>
</body>
</html>
