<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: dummy_login.php");
    exit();
}

// Database connection
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "sprs_dummydb";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$role = $_SESSION['role'];
$username = $_SESSION['username'];

// Fetch data for teacher
if ($role === 'teacher') {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND role='teacher'");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $teacher = $stmt->get_result()->fetch_assoc();

    // Fetch students in teacher's department
    $dept = $teacher['department'];
    $sql = "SELECT * FROM users WHERE role='student' AND department='$dept'";
    $students = $conn->query($sql);

} elseif ($role === 'student') {
    $sql = "SELECT * FROM users WHERE username='$username'";
    $students = $conn->query($sql); // for students, only their own info
} else {
    // Admin
    $sql = "SELECT * FROM users";
    $students = $conn->query($sql);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dummy Dashboard</title>
<style>
body {
    font-family: 'Inter', sans-serif;
    background: #eef3f9;
    margin: 0;
    padding: 0;
}
.header {
    background: #007bff;
    color: white;
    padding: 15px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.logout-btn {
    background: white;
    color: #007bff;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
}
.logout-btn:hover {
    background: #f0f0f0;
}
.content {
    padding: 25px;
}

/* Profile card */
.profile-card {
    max-width: 400px;
    background: white;
    padding: 20px;
    margin: 30px auto;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    text-align: center;
}
.profile-card img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 15px;
}
.profile-card h2 {
    margin: 10px 0 5px;
}
.profile-card p {
    margin: 5px 0;
    color: #555;
}

/* Teacher student cards */
.teacher-students {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 20px;
}
.teacher-student-card {
    background: white;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    width: 220px;
    text-align: center;
}
.teacher-student-card img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 10px;
}
.teacher-student-card h3 {
    margin: 5px 0;
}
.teacher-student-card p {
    margin: 3px 0;
    font-size: 14px;
    color: #555;
}

/* Admin table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
table, th, td {
    border: 1px solid #ddd;
}
th, td {
    padding: 10px;
    text-align: left;
}
th {
    background: #007bff;
    color: white;
}
</style>
</head>
<body>
<div class="header">
    <div>
        <strong>Welcome, <?= htmlspecialchars($_SESSION["name"]); ?></strong>
        <span style="margin-left: 10px; font-size: 14px;">(<?= htmlspecialchars($_SESSION["role"]); ?>)</span>
    </div>
    <form action="dummy_logout.php" method="POST">
        <button class="logout-btn" type="submit">Logout</button>
    </form>
</div>

<div class="content">

<?php if ($role === 'student'): ?>
    <?php if ($row = $students->fetch_assoc()): ?>
        <div class="profile-card">
            <?php
            $imgPath = "images/" . htmlspecialchars($row['username']) . ".jpg";
            if (!file_exists($imgPath)) $imgPath = "images/default.jpg";
            ?>
            <img src="<?= $imgPath ?>" alt="Profile Image">
            <h2><?= htmlspecialchars($row['name']) ?></h2>
            <p><strong>Username:</strong> <?= htmlspecialchars($row['username']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($row['role']) ?></p>
            <p><strong>Department:</strong> <?= htmlspecialchars($row['department']) ?></p>
            <p><strong>Program:</strong> <?= htmlspecialchars($row['program']) ?></p>
            <p><strong>Major:</strong> <?= htmlspecialchars($row['major']) ?></p>
            <p><strong>Points:</strong> <?= htmlspecialchars($row['points']) ?></p>
        </div>
    <?php endif; ?>

<?php elseif ($role === 'teacher'): ?>
    <!-- Teacher profile at top -->
    <div class="profile-card">
        <?php
        $imgPath = "images/" . htmlspecialchars($teacher['username']) . ".jpg";
        if (!file_exists($imgPath)) $imgPath = "images/default.jpg";
        ?>
        <img src="<?= $imgPath ?>" alt="Teacher Image">
        <h2><?= htmlspecialchars($teacher['name']) ?></h2>
        <p><strong>Username:</strong> <?= htmlspecialchars($teacher['username']) ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($teacher['role']) ?></p>
        <p><strong>Department:</strong> <?= htmlspecialchars($teacher['department']) ?></p>
        <p><strong>Program:</strong> <?= htmlspecialchars($teacher['program']) ?></p>
    </div>

    <h2>Students in <?= htmlspecialchars($teacher['department']) ?> Department</h2>
    <div class="teacher-students">
        <?php if ($students->num_rows > 0): ?>
            <?php while($row = $students->fetch_assoc()): ?>
                <?php
                $imgPath = "images/" . htmlspecialchars($row['username']) . ".jpg";
                if (!file_exists($imgPath)) $imgPath = "images/default.jpg";
                ?>
                <div class="teacher-student-card">
                    <img src="<?= $imgPath ?>" alt="Student Image">
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <p><strong>Username:</strong> <?= htmlspecialchars($row['username']) ?></p>
                    <p><strong>Program:</strong> <?= htmlspecialchars($row['program']) ?></p>
                    <p><strong>Major:</strong> <?= htmlspecialchars($row['major']) ?></p>
                    <p><strong>Points:</strong> <?= htmlspecialchars($row['points']) ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No students found in your department.</p>
        <?php endif; ?>
    </div>

<?php else: ?>
    <!-- Admin table -->
    <h2>All Users</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Password</th>
            <th>Role</th>
            <th>Points</th>
            <th>Name</th>
            <th>Department</th>
            <th>Program</th>
            <th>Major</th>
        </tr>
        <?php if ($students->num_rows > 0): ?>
            <?php while($row = $students->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['password']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td><?= htmlspecialchars($row['points']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['department']) ?></td>
                    <td><?= htmlspecialchars($row['program']) ?></td>
                    <td><?= htmlspecialchars($row['major']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="9" style="text-align:center;">No data available</td></tr>
        <?php endif; ?>
    </table>
<?php endif; ?>
</div>
</body>
</html>
