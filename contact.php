<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "student_system");

    if ($conn->connect_error) {
        die("❌ Connection failed: " . $conn->connect_error);
    }

    // Sanitize input
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO contact (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Message sent successfully.'); window.location.href='contact.php';</script>";
        exit;
    } else {
        echo "<script>alert('❌ Error saving message.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contact Us</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style1.css" />
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">CIMT</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
      aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.html">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="contact.php">Contact</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Contact Form -->
<section class="container py-5">
  <div class="text-center mb-5">
    <h2>Contact Us</h2>
    <p class="lead">We'd love to hear from you! Send us a message using the form below.</p>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-8">
      <form method="POST" action="contact.php">
        <div class="mb-3">
          <label for="name" class="form-label">Your Name</label>
          <input type="text" class="form-control" name="name" id="name" required />
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Your Email</label>
          <input type="email" class="form-control" name="email" id="email" required />
        </div>
        <div class="mb-3">
          <label for="subject" class="form-label">Subject</label>
          <input type="text" class="form-control" name="subject" id="subject" required />
        </div>
        <div class="mb-3">
          <label for="message" class="form-label">Your Message</label>
          <textarea class="form-control" name="message" id="message" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary px-4">Send Message</button>
      </form>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="blog-footer text-center py-3 bg-light mt-5">
  <p>&copy; 2025 Chandannagar Institute of Management & Technology | Designed by Megha Dutta</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
