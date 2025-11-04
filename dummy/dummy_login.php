<?php
session_start();
include 'connection_dummydb.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if ($password === $user['password']) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['points'] = $user['points'];
            $_SESSION['name'] = $user['name']; // testing name column

            header("Location: dummy_index.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SPRS Dummy Login</title>
    <style>
        body { font-family: Arial; background: #f3f3f3; text-align: center; }
        form { background: white; padding: 20px; margin: 100px auto; width: 300px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
        input { margin: 8px 0; padding: 10px; width: 90%; }
        button { background: #4CAF50; color: white; border: none; padding: 10px; width: 100%; border-radius: 5px; }
        .error { color: red; }
    </style>
</head>
<body>
    <form method="POST" action="">
        <h2>Student Point-Reward System</h2>
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
        <p class="error"><?= $error ?></p>
    </form>
</body>
</html>
