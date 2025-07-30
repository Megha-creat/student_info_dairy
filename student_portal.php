<?php
session_start();
$conn = new mysqli("localhost", "root", "", "student_system");

// Registration logic
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $roll = trim($_POST['roll']);
    $department = $_POST['department'];
    $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO students (name, email, password, roll_no, department) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $pass, $roll, $department);
    $stmt->execute();
    echo "<script>alert('Registration Successful');</script>";
}

// Login logic
if (isset($_POST['login'])) {
    $email = $_POST['login_email'];
    $pass = $_POST['login_password'];

    $stmt = $conn->prepare("SELECT * FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['student'] = $user;
    } else {
        echo "<script>alert('Login Failed');</script>";
    }
}

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: student_portal.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Portal</title>
    <link rel="stylesheet" href="style2.css">
    <style>
        a.home-btn {
            position: absolute;
            top: 15px;
            left: 15px;
            text-decoration: none;
            font-size: 16px;
            background: #4CAF50;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
        }
        .dashboard {
            padding: 20px;
        }
    </style>
</head>
<body>

<!-- ✅ HOME BUTTON -->
<a href="index.html" class="home-btn">🏠 Home</a>

<?php if (!isset($_SESSION['student'])): ?>
    <h2>Student Portal</h2>
    <div class="form-container">
        <!-- Login Form -->
        <form method="POST" id="login-form" style="display: block;">
            <h3>Login</h3>
            <input type="email" name="login_email" placeholder="Email" required>
            <input type="password" name="login_password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
            <p>Don't have an account? <a href="#" onclick="toggleForms(false)">Register</a></p>
        </form>

        <!-- Registration Form -->
        <form method="POST" id="register-form" style="display: none;">
            <h3>Register</h3>
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="roll" placeholder="Roll No" required>
            <select name="department" required>
                <option value="">Select Department</option>
                <option value="BCA">BCA</option>
                <option value="BBA">BBA</option>
                <option value="MCA">MCA</option>
            </select>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Register</button>
            <p>Already have an account? <a href="#" onclick="toggleForms(true)">Login</a></p>
        </form>
    </div>

    <script>
        function toggleForms(showLogin) {
            document.getElementById("login-form").style.display = showLogin ? "block" : "none";
            document.getElementById("register-form").style.display = showLogin ? "none" : "block";
        }
    </script>

<?php else: ?>
    <div class="dashboard">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['student']['name']); ?> 👋</h2>
        <p><strong>Department:</strong> <?= htmlspecialchars($_SESSION['student']['department']); ?></p>
        <p><strong>Roll:</strong> <?= htmlspecialchars($_SESSION['student']['roll_no']); ?></p>
        <p><a href="?logout=1">🚪 Logout</a></p>

        <!-- Uploaded PDF Results -->
        <h3>📄 Your Results</h3>
        <ul>
            <?php
            $roll = $_SESSION['student']['roll_no'];
            $stmt = $conn->prepare("SELECT file_path, uploaded_at FROM result_uploads WHERE roll = ?");
            $stmt->bind_param("s", $roll);
            $stmt->execute();
            $pdfs = $stmt->get_result();

            if ($pdfs->num_rows > 0) {
                while ($row = $pdfs->fetch_assoc()) {
                    $file = htmlspecialchars($row['file_path']);
                    $date = htmlspecialchars($row['uploaded_at']);
                    echo "<li><a href='$file' target='_blank'>View Result (Uploaded on $date)</a></li>";
                }
            } else {
                echo "<li>No result PDF uploaded yet.</li>";
            }
            ?>
        </ul>

        <!-- Notices Section -->
        <h3>📢 Latest Notices</h3>
        <ul>
            <?php
            $notices = $conn->query("SELECT title, content, posted_on FROM notices ORDER BY posted_on DESC");
            if ($notices->num_rows > 0) {
                while ($n = $notices->fetch_assoc()) {
                    $title = htmlspecialchars($n['title']);
                    $content = nl2br(htmlspecialchars($n['content']));
                    $date = date('d M Y', strtotime($n['posted_on']));
                    echo "<li><strong>📝 $title</strong> <em>($date)</em><br><p>$content</p><hr></li>";
                }
            } else {
                echo "<li>No notices posted yet.</li>";
            }
            ?>
        </ul>
    </div>
<?php endif; ?>

</body>
</html>
