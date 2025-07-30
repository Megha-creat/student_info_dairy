<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload'])) {
    if (isset($_POST['roll']) && isset($_FILES['result_file'])) {
        $roll = trim($_POST['roll']);
        $file = $_FILES['result_file'];

        // Create uploads folder if it doesn't exist
        if (!is_dir('uploads')) {
            mkdir('uploads', 0755, true);
        }

        $filename = basename($file['name']);
        $filepath = 'uploads/' . $filename;
        $filetype = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));

        if ($filetype !== "pdf") {
            echo "❌ Only PDF files are allowed.";
        } elseif (move_uploaded_file($file["tmp_name"], $filepath)) {
            $conn = new mysqli('localhost', 'root', '', 'student_system');

            if ($conn->connect_error) {
                die("Database connection failed: " . $conn->connect_error);
            }

            $stmt = $conn->prepare("INSERT INTO results (roll, file_path) VALUES (?, ?)");
            $stmt->bind_param("ss", $roll, $filepath);

            if ($stmt->execute()) {
                echo "✅ Result uploaded successfully.";
            } else {
                echo "❌ Database error: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        } else {
            echo "❌ Failed to upload the file.";
        }
    } else {
        echo "❌ Please fill out all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Result</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        form { max-width: 400px; margin: auto; background: #f9f9f9; padding: 20px; border-radius: 10px; }
        label, input, button { display: block; width: 100%; margin-bottom: 15px; }
        button { background: #007BFF; color: white; border: none; padding: 10px; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Upload Student Result</h2>
    <form method="POST" enctype="multipart/form-data">

        <label for="roll">Student Roll:</label>
        <input type="text" name="roll" id="roll" required>

        <label for="result_file">Upload Result (PDF):</label>
        <input type="file" name="result_file" id="result_file" accept="application/pdf" required>

        <button type="submit" name="upload">Upload</button>
    </form>
</body>
</html>
