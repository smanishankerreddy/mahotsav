<?php
ob_start();
session_start();
include("includes/db.php");

// Check if user is logged in
//if (!isset($_SESSION['user'])) {
  //  header("Location: login.php");
   // exit();
//}

if (isset($_POST['logout'])) {
  session_destroy();
  header("Location: login.php");
  exit();
}

// check login
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vignan Mahotsav | Home</title>
  <style>
    * { box-sizing: border-box; font-family: "Poppins", sans-serif; }
    body {
      margin: 0;
      background: linear-gradient(135deg, #667eea, #764ba2);
      min-height: 100vh;
      color: #fff;
      padding: 20px;
    }
    
    .header {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .header h1 {
      color: #ffe66d;
      margin: 0;
      font-size: 2.5em;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .header p {
      margin: 10px 0 0 0;
      font-size: 1.1em;
      opacity: 0.9;
    }
    
    .container {
      max-width: 900px;
      margin: 0 auto;
    }
    
    .welcome-card {
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(15px);
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
      margin-bottom: 25px;
    }
    
    .welcome-card h2 {
      color: #ffe66d;
      margin-top: 0;
      margin-bottom: 20px;
      font-size: 1.8em;
    }
    
    .user-info {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 15px;
      margin-top: 20px;
    }
    
    .info-item {
      background: rgba(255,255,255,0.1);
      padding: 15px;
      border-radius: 12px;
      border-left: 4px solid #ffe66d;
      overflow: hidden;
    }
    
    .info-item label {
      font-size: 0.85em;
      opacity: 0.8;
      display: block;
      margin-bottom: 5px;
    }
    
    .info-item .value {
      font-size: 1.05em;
      font-weight: 600;
      word-wrap: break-word;
      overflow-wrap: break-word;
    }
    
    .actions-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }
    
    .action-card {
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(15px);
      border-radius: 15px;
      padding: 25px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      text-decoration: none;
      color: #fff;
      display: block;
    }
    
    .action-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.3);
      background: rgba(255,255,255,0.2);
    }
    
    .action-card .icon {
      font-size: 2.5em;
      margin-bottom: 10px;
    }
    
    .action-card h3 {
      margin: 10px 0 5px 0;
      color: #ffe66d;
      font-size: 1.2em;
    }
    
    .action-card p {
      margin: 0;
      font-size: 0.9em;
      opacity: 0.85;
    }
    
    .logout-btn {
      background: rgba(255,100,100,0.8);
      color: #fff;
      border: none;
      padding: 12px 30px;
      border-radius: 10px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s ease;
      font-size: 1em;
      margin-top: 20px;
      display: block;
      width: 200px;
      margin-left: auto;
      margin-right: auto;
    }
    
    .logout-btn:hover {
      background: rgba(255,50,50,0.9);
      transform: scale(1.05);
    }
    
    .mh-id-badge {
      display: inline-block;
      background: linear-gradient(135deg, #ffd700, #ffed4e);
      color: #000;
      padding: 8px 20px;
      border-radius: 25px;
      font-weight: 700;
      font-size: 1.1em;
      letter-spacing: 1px;
      box-shadow: 0 4px 15px rgba(255,230,109,0.4);
    }
  </style>
</head>
<body>

<div class="container">
  <div class="header">
    <h1>🎉 Vignan Mahotsav Portal</h1>
    <p>Welcome to your dashboard</p>
  </div>

  <div class="welcome-card">
    <h2>Hello, <?php echo htmlspecialchars($user['name']); ?>! 👋</h2>
    <p>Your Mahotsav ID: <span class="mh-id-badge"><?php echo htmlspecialchars($user['mh_id']); ?></span></p>
    
    <div class="user-info">
      <div class="info-item">
        <label>Registration Number</label>
        <div class="value"><?php echo htmlspecialchars($user['regno']); ?></div>
      </div>
      
      <div class="info-item">
        <label>Email</label>
        <div class="value"><?php echo htmlspecialchars($user['email']); ?></div>
      </div>
      
      <div class="info-item">
        <label>Cell Number</label>
        <div class="value"><?php echo htmlspecialchars($user['cellno']); ?></div>
      </div>
      
      <div class="info-item">
        <label>Branch</label>
        <div class="value"><?php echo htmlspecialchars($user['branch']); ?></div>
      </div>
      
      <div class="info-item">
        <label>College</label>
        <div class="value"><?php echo htmlspecialchars($user['college']); ?></div>
      </div>
      
      <div class="info-item">
        <label>State</label>
        <div class="value"><?php echo htmlspecialchars($user['state']); ?></div>
      </div>
    </div>
  </div>

  <div class="actions-grid">
    <a href="register_events.php" class="action-card">
      <div class="icon">📝</div>
      <h3>Register for Events</h3>
      <p>Browse and register for upcoming events</p>
    </a>
    
    <a href="my_registrations.php" class="action-card">
      <div class="icon">🎫</div>
      <h3>My Registrations</h3>
      <p>View your event registrations</p>
    </a>
    
    <a href="event_schedule.php" class="action-card">
      <div class="icon">📅</div>
      <h3>Event Schedule</h3>
      <p>Check the festival schedule</p>
    </a>
    
    <a href="update_profile.php" class="action-card">
      <div class="icon">👤</div>
      <h3>Update Profile</h3>
      <p>Edit your profile information</p>
    </a>
    
    <a href="results.php" class="action-card">
      <div class="icon">🏆</div>
      <h3>Results</h3>
      <p>View competition results</p>
    </a>
    
    <a href="announcements_page.php" class="action-card">
      <div class="icon">📢</div>
      <h3>Announcements</h3>
      <p>Latest updates and news</p>
    </a>
  </div>

  <form method="POST" action="">
    <button type="submit" name="logout" class="logout-btn">🚪 Logout</button>
  </form>
</div>

<?php ob_end_flush(); ?>


</body>
</html>
