<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f9f9f9;
        }
        header {
            background: #1f4e79;
            color: white;
            padding: 15px 20px;
            font-size: 22px;
        }
        .container {
            display: flex;
        }
        .sidebar {
            width: 200px;
            background: #244e68;
            height: 100vh;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #163b5c;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th {
            background: #1f4e79;
            color: white;
        }
        td, th {
            padding: 10px;
            text-align: left;
        }
        .logout {
            background: red;
            color: white;
            border: none;
            padding: 8px 14px;
            cursor: pointer;
            border-radius: 5px;
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <header>
        Admin Dashboard - Welcome, <?php echo $_SESSION['admin']; ?>
        <a href="index.html" style="float:right;"><button class="logout">Logout</button></a>
    </header>
    <div class="container">
        <div class="sidebar">
            <a href="admin_dashboard.php">📋 View Students</a>
            <a href="post_notice.php">📢 Post Notices</a>
            <a href="uplod_result.php">📝 Upload Results</a>
            <a href="view_contacts.php">📨 View Contacts</a>
        </div>
        <div class="content">
            <h2>Student Information</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    
                    <th>Department</th>
                    
                </tr>

                <?php
                $conn = new mysqli('localhost', 'root', '', 'student_system');

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $query = "SELECT * FROM students";
                $result = $conn->query($query);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['department']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No student data found.</td></tr>";
                }

                $conn->close();
                ?>
            </table>
        </div>
    </div>
</body>
</html>
