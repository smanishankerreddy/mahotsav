<?php
session_start();
include("includes/db.php");

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$student_id = $user['id']; // Assuming 'id' exists in `students` table

$success_message = "";

// Handle event registration
if (isset($_POST['register_event'])) {
    $event_id = $_POST['event_id'];

    // Check if already registered
    $check = $conn->query("SELECT * FROM registrations WHERE student_id='$student_id' AND event_id='$event_id'");
    if ($check->num_rows > 0) {
        $success_message = "⚠️ You are already registered for this event!";
    } else {
        $conn->query("INSERT INTO registrations (student_id, event_id, registered_at) VALUES ('$student_id', '$event_id', NOW())");
        $success_message = "✅ Successfully registered for the event!";
    }
}

// Fetch events from DB
$events = [];
$result = $conn->query("SELECT * FROM events ORDER BY date ASC");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Count how many students registered for each event
        $count_result = $conn->query("SELECT COUNT(*) AS total FROM registrations WHERE event_id='" . $row['id'] . "'");
        $count = $count_result->fetch_assoc()['total'] ?? 0;

        $row['registered'] = $count;
        $row['max_participants'] = $row['max_participants'] ?? 50; // fallback
        $events[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register for Events | Vignan Mahotsav</title>
  <style>
    * { box-sizing: border-box; font-family: "Poppins", sans-serif; }
    body {
      margin: 0;
      background: linear-gradient(135deg, #667eea, #764ba2);
      min-height: 100vh;
      color: #fff;
      padding: 20px;
    }
    .container { max-width: 1200px; margin: 0 auto; }
    .header { text-align: center; margin-bottom: 30px; }
    .header h1 { color: #ffe66d; font-size: 2.5em; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
    .back-btn {
      display: inline-block; background: rgba(255,255,255,0.2); color: #fff;
      padding: 10px 20px; border-radius: 10px; text-decoration: none; margin-bottom: 20px;
      transition: 0.3s;
    }
    .back-btn:hover { background: rgba(255,255,255,0.3); transform: translateX(-5px); }
    .success-message {
      background: rgba(76, 175, 80, 0.9); padding: 15px; border-radius: 10px;
      margin-bottom: 20px; text-align: center; animation: slideDown 0.5s ease;
    }
    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .events-grid {
      display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 25px; margin-top: 20px;
    }
    .event-card {
      background: rgba(255,255,255,0.15); backdrop-filter: blur(15px);
      border-radius: 15px; padding: 25px; box-shadow: 0 8px 25px rgba(0,0,0,0.3); transition: 0.3s;
    }
    .event-card:hover { transform: translateY(-5px); box-shadow: 0 12px 35px rgba(0,0,0,0.4); }
    .event-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px; }
    .event-name { color: #ffe66d; font-size: 1.4em; font-weight: 700; margin: 0; flex: 1; }
    .category-badge {
      background: linear-gradient(135deg, #667eea, #764ba2);
      padding: 5px 15px; border-radius: 20px; font-size: 0.85em; font-weight: 600;
    }
    .description { color: rgba(255,255,255,0.85); font-size: 0.9em; line-height: 1.5; margin: 15px 0; }
    .progress-bar {
      background: rgba(255,255,255,0.2); border-radius: 10px; height: 8px; margin: 10px 0; overflow: hidden;
    }
    .progress-fill {
      background: linear-gradient(90deg, #4caf50, #8bc34a); height: 100%; border-radius: 10px;
      transition: width 0.3s ease;
    }
    .capacity-info { font-size: 0.85em; opacity: 0.9; margin-top: 5px; }
    .register-btn {
      background: linear-gradient(135deg, #4caf50, #45a049); color: #fff; border: none;
      padding: 12px 25px; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;
      margin-top: 15px; font-size: 1em; transition: 0.3s;
    }
    .register-btn:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(76,175,80,0.4); }
    .register-btn:disabled { background: rgba(150,150,150,0.5); cursor: not-allowed; }
  </style>
</head>
<body>
<div class="container">
  <a href="home.php" class="back-btn">← Back to Dashboard</a>
  
  <div class="header">
    <h1>📝 Register for Events</h1>
    <p>Browse available events and register now!</p>
  </div>

  <?php if ($success_message): ?>
    <div class="success-message"><?php echo $success_message; ?></div>
  <?php endif; ?>

  <div class="events-grid">
    <?php foreach ($events as $event): 
      $capacity_percentage = ($event['registered'] / $event['max_participants']) * 100;
      $is_full = $event['registered'] >= $event['max_participants'];
    ?>
      <div class="event-card">
        <div class="event-header">
          <h3 class="event-name"><?= htmlspecialchars($event['event_name']) ?></h3>
          <span class="category-badge"><?= htmlspecialchars($event['category'] ?? 'General') ?></span>
        </div>

        <div class="description"><?= htmlspecialchars($event['description']) ?></div>

        <div class="detail-item">📅 <?= date('F j, Y', strtotime($event['date'])) ?></div>
        <div class="detail-item">🕐 <?= htmlspecialchars($event['time']) ?></div>
        <div class="detail-item">📍 <?= htmlspecialchars($event['location']) ?></div>

        <div class="progress-bar"><div class="progress-fill" style="width: <?= $capacity_percentage ?>%"></div></div>
        <div class="capacity-info"><?= $event['registered'] ?> / <?= $event['max_participants'] ?> participants</div>

        <form method="POST">
          <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
          <button type="submit" name="register_event" class="register-btn" <?= $is_full ? 'disabled' : '' ?>>
            <?= $is_full ? '🚫 Event Full' : '✓ Register Now' ?>
          </button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
