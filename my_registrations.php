<?php
session_start();
include("includes/db.php");

// Check login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$student_id = $user['id'];

// Fetch registrations from DB
$sql = "SELECT re.*, e.event_name, e.category, e.date, e.time, e.location AS venue 
        FROM register_events re
        JOIN events e ON re.event_id = e.id
        WHERE re.student_id = ?";
$stmt = $conn->prepare("
SELECT re.*, 
       e.event_name, 
       COALESCE(e.category, 'General') AS category, 
       e.date, 
       e.time, 
       e.location AS venue
FROM registrations re
JOIN events e ON re.event_id = e.id
WHERE re.student_id = ?
");

$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$registrations = [];
while ($row = $result->fetch_assoc()) {
    $registrations[] = [
        'event_name' => $row['event_name'],
        'category' => $row['category'],
        'date' => $row['date'],
        'time' => $row['time'],
        'venue' => $row['venue'],
        'registration_date' => $row['created_at'],
        'status' => $row['status'] ?? 'Confirmed',
        'team_size' => $row['team_size'] ?? 'Solo'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Registrations | Vignan Mahotsav</title>
  <style>
    * { box-sizing: border-box; font-family: "Poppins", sans-serif; }
    body {
      margin: 0;
      background: linear-gradient(135deg, #667eea, #764ba2);
      min-height: 100vh;
      color: #fff;
      padding: 20px;
    }
    .container { max-width: 1000px; margin: 0 auto; }
    .header { text-align: center; margin-bottom: 30px; }
    .header h1 {
      color: #ffe66d; margin: 0 0 10px 0; font-size: 2.5em;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    .back-btn {
      display: inline-block;
      background: rgba(255,255,255,0.2);
      color: #fff; padding: 10px 20px;
      border-radius: 10px; text-decoration: none;
      margin-bottom: 20px; transition: 0.3s;
    }
    .back-btn:hover {
      background: rgba(255,255,255,0.3);
      transform: translateX(-5px);
    }
    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px; margin-bottom: 30px;
    }
    .stat-card {
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(15px);
      border-radius: 15px;
      padding: 20px;
      text-align: center;
    }
    .stat-number {
      font-size: 2.5em;
      font-weight: 700;
      color: #ffe66d;
      margin: 10px 0;
    }
    .stat-label { font-size: 0.9em; opacity: 0.9; }
    .registration-card {
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(15px);
      border-radius: 15px;
      padding: 25px;
      margin-bottom: 20px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
      transition: 0.3s;
    }
    .registration-card:hover {
      transform: translateX(5px);
      box-shadow: 0 12px 35px rgba(0,0,0,0.4);
    }
    .card-header {
      display: flex; justify-content: space-between; align-items: start;
      margin-bottom: 15px; flex-wrap: wrap; gap: 10px;
    }
    .event-title {
      color: #ffe66d; font-size: 1.5em; font-weight: 700; margin: 0;
      flex: 1; min-width: 200px;
    }
    .status-badge {
      padding: 8px 20px; border-radius: 20px;
      font-size: 0.85em; font-weight: 600;
    }
    .status-confirmed { background: linear-gradient(135deg, #4caf50, #45a049); }
    .status-pending { background: linear-gradient(135deg, #ff9800, #f57c00); }
    .category-badge {
      background: rgba(255,255,255,0.2);
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 0.85em;
      display: inline-block;
      margin-top: 10px;
    }
    .details-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
      margin-top: 15px;
    }
    .detail-box {
      background: rgba(255,255,255,0.1);
      padding: 12px;
      border-radius: 10px;
    }
    .detail-label { font-size: 0.85em; opacity: 0.8; margin-bottom: 5px; }
    .detail-value { font-weight: 600; font-size: 1.05em; }
    .empty-state {
      text-align: center; padding: 60px 20px;
      background: rgba(255,255,255,0.1);
      border-radius: 15px; margin-top: 20px;
    }
    .empty-state-icon { font-size: 4em; margin-bottom: 20px; }
    .empty-state h3 { color: #ffe66d; margin: 10px 0; }
    .register-link {
      display: inline-block;
      background: linear-gradient(135deg, #4caf50, #45a049);
      color: #fff; padding: 12px 30px;
      border-radius: 10px; text-decoration: none;
      margin-top: 20px; font-weight: 600;
      transition: 0.3s;
    }
    .register-link:hover {
      transform: scale(1.05);
      box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
    }
  </style>
</head>
<body>

<div class="container">
  <a href="home.php" class="back-btn">← Back to Dashboard</a>
  
  <div class="header">
    <h1>🎫 My Registrations</h1>
    <p>View and manage your event registrations</p>
  </div>

  <div class="stats-container">
    <div class="stat-card">
      <div class="stat-number"><?php echo count($registrations); ?></div>
      <div class="stat-label">Total Registrations</div>
    </div>
    <div class="stat-card">
      <div class="stat-number"><?php echo count(array_filter($registrations, fn($r) => $r['status'] == 'Confirmed')); ?></div>
      <div class="stat-label">Confirmed</div>
    </div>
    <div class="stat-card">
      <div class="stat-number"><?php echo count(array_filter($registrations, fn($r) => $r['status'] == 'Pending')); ?></div>
      <div class="stat-label">Pending</div>
    </div>
  </div>

  <?php if (empty($registrations)): ?>
    <div class="empty-state">
      <div class="empty-state-icon">🎭</div>
      <h3>No Registrations Yet</h3>
      <p>You haven't registered for any events yet. Start exploring!</p>
      <a href="register_events.php" class="register-link">Browse Events</a>
    </div>
  <?php else: ?>
    <?php foreach ($registrations as $registration): ?>
      <div class="registration-card">
        <div class="card-header">
          <h3 class="event-title"><?php echo $registration['event_name']; ?></h3>
          <span class="status-badge status-<?php echo strtolower($registration['status']); ?>">
            <?php echo $registration['status']; ?>
          </span>
        </div>
        
        <span class="category-badge"><?php echo $registration['category']; ?></span>
        
        <div class="details-grid">
          <div class="detail-box">
            <div class="detail-label">📅 Event Date</div>
            <div class="detail-value"><?php echo date('F j, Y', strtotime($registration['date'])); ?></div>
          </div>
          <div class="detail-box">
            <div class="detail-label">🕐 Time</div>
            <div class="detail-value"><?php echo $registration['time']; ?></div>
          </div>
          <div class="detail-box">
            <div class="detail-label">📍 Venue</div>
            <div class="detail-value"><?php echo $registration['venue']; ?></div>
          </div>
          <div class="detail-box">
            <div class="detail-label">👥 Team Size</div>
            <div class="detail-value"><?php echo $registration['team_size']; ?></div>
          </div>
          <div class="detail-box">
            <div class="detail-label">📝 Registered On</div>
            <div class="detail-value">
  <?php 
    if (!empty($registration['registration_date']) && strtotime($registration['registration_date']) > 0) {
        echo date('F j, Y', strtotime($registration['registration_date']));
    } elseif (!empty($registration['created_at']) && strtotime($registration['created_at']) > 0) {
        echo date('F j, Y', strtotime($registration['created_at']));
    } else {
        echo date('F j, Y'); // show today as default
    }
  ?>
</div>

          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

</body>
</html>
