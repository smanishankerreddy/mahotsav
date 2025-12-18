
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
  <title>Admin Panel</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header>🎓 Mahotsav Admin Dashboard</header>
<nav>
  <a href="index.php">🏠 Home</a>
  <a href="manage_events.php">📅 Events</a>
  <a href="manage_results.php">🏆 Results</a>
  <a href="manage_announcements.php">📢 Announcements</a>
  <a href="logout.php">🚪 Logout</a>
</nav>
<div class="container">
  <h2>Welcome, Admin!</h2>
  <p>Manage events, results, and announcements</p>
</div>
</div>
</body>
</html>
