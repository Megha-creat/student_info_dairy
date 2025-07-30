<?php
// post_notice.php
$conn = new mysqli("localhost", "root", "", "student_system");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $title = $_POST['title'];
    $message = $_POST['message'];

   $stmt = $conn->prepare("INSERT INTO notices (title, content, posted_on) VALUES (?, ?, NOW())");
$stmt->bind_param("ss", $title, $content); // use $content instead of $message
$stmt->execute();


    echo "<script>alert('Notice posted successfully!'); window.location='admin_dashboard.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Post Notice</title>
    <style>
        body {
            font-family: Arial;
            background: #f9f9f9;
            padding: 20px;
        }
        form {
            background: white;
            padding: 25px;
            border-radius: 10px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px #ccc;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            padding: 10px 20px;
            background: #0066cc;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>📢 Post a Notice</h2>
        <label>Title</label>
        <input type="text" name="title" required>

        <label>Message</label>
        <textarea name="message" rows="5" required></textarea>

        <button type="submit">Post Notice</button>
    </form>
</body>
</html>
