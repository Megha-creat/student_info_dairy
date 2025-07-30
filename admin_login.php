<?php
session_start();

// Login Logic
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli('localhost', 'root', '', 'student_system');

    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows === 1) {
        $_SESSION['admin'] = $username;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
            padding: 30px;
            text-align: center;
            color: #333;
        }
        h2 {
            color: #1f4e79;
        }
        .form-container {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }
        form {
            background: #ffffff;
            padding: 25px 20px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            background: #1f4e79;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background: #163b5c;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .home-btn {
            margin-top: 15px;
            display: inline-block;
            text-decoration: none;
            background-color: #1f4e79;
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .home-btn:hover {
            background-color: #163b5c;
        }
    </style>
</head>
<body>
    
    <h2>Admin Login</h2>
    <div class="form-container">
        <form method="POST">
            <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit">Login</button>
            <a href="index.html" class="home-btn">🏠 Home</a>
        </form>
    </div>
</body>
</html>
