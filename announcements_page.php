<?php
session_start();
include("includes/db.php");

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

$stmt = $conn->prepare("SELECT * FROM announcements ORDER BY date DESC");
$stmt->execute();
$result = $stmt->get_result();
$announcements = [];

while ($row = $result->fetch_assoc()) {
    $announcements[] = $row;
}


// Sort by date (newest first)
usort($announcements, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Announcements | Vignan Mahotsav</title>
  <style>
    * { box-sizing: border-box; font-family: "Poppins", sans-serif; }
    body {
      margin: 0;
      background: linear-gradient(135deg, #667eea, #764ba2);
      min-height: 100vh;
      color: #fff;
      padding: 20px;
    }
    
    .container {
      max-width: 1000px;
      margin: 0 auto;
    }
    
    .header {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .header h1 {
      color: #ffe66d;
      margin: 0 0 10px 0;
      font-size: 2.5em;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .back-btn {
      display: inline-block;
      background: rgba(255,255,255,0.2);
      color: #fff;
      padding: 10px 20px;
      border-radius: 10px;
      text-decoration: none;
      margin-bottom: 20px;
      transition: 0.3s;
    }
    
    .back-btn:hover {
      background: rgba(255,255,255,0.3);
      transform: translateX(-5px);
    }
    
    .filter-bar {
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(15px);
      border-radius: 15px;
      padding: 20px;
      margin-bottom: 25px;
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
      justify-content: center;
    }
    
    .filter-btn {
      background: rgba(255,255,255,0.2);
      border: 2px solid rgba(255,255,255,0.3);
      color: #fff;
      padding: 8px 20px;
      border-radius: 20px;
      cursor: pointer;
      transition: 0.3s;
      font-weight: 600;
      font-size: 0.9em;
    }
    
    .filter-btn:hover, .filter-btn.active {
      background: rgba(255,230,109,0.9);
      color: #000;
      border-color: #ffe66d;
    }
    
    .announcement-card {
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(15px);
      border-radius: 15px;
      padding: 25px;
      margin-bottom: 20px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
      transition: 0.3s;
      border-left: 5px solid transparent;
    }
    
    .announcement-card:hover {
      transform: translateX(5px);
      box-shadow: 0 12px 35px rgba(0,0,0,0.4);
    }
    
    .announcement-card.priority-critical {
      border-left-color: #ff4757;
      background: rgba(255,71,87,0.15);
    }
    
    .announcement-card.priority-high {
      border-left-color: #ffa502;
      background: rgba(255,165,2,0.15);
    }
    
    .announcement-card.priority-medium {
      border-left-color: #2ed573;
      background: rgba(46,213,115,0.15);
    }
    
    .announcement-card.priority-low {
      border-left-color: #5f27cd;
    }
    
    .announcement-header {
      display: flex;
      justify-content: space-between;
      align-items: start;
      margin-bottom: 15px;
      flex-wrap: wrap;
      gap: 10px;
    }
    
    .announcement-title {
      display: flex;
      align-items: center;
      gap: 12px;
      flex: 1;
      min-width: 250px;
    }
    
    .announcement-icon {
      font-size: 2em;
    }
    
    .title-text {
      color: #ffe66d;
      font-size: 1.4em;
      font-weight: 700;
      margin: 0;
    }
    
    .announcement-meta {
      display: flex;
      gap: 10px;
      align-items: center;
      flex-wrap: wrap;
    }
    
    .type-badge {
      background: linear-gradient(135deg, #667eea, #764ba2);
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 0.8em;
      font-weight: 600;
    }
    
    .priority-badge {
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 0.8em;
      font-weight: 700;
    }
    
    .priority-critical {
      background: linear-gradient(135deg, #ff4757, #ff3838);
    }
    
    .priority-high {
      background: linear-gradient(135deg, #ffa502, #ff6348);
    }
    
    .priority-medium {
      background: linear-gradient(135deg, #2ed573, #7bed9f);
    }
    
    .priority-low {
      background: linear-gradient(135deg, #5f27cd, #341f97);
    }
    
    .date-badge {
      background: rgba(255,255,255,0.2);
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 0.8em;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    
    .announcement-content {
      color: rgba(255,255,255,0.9);
      line-height: 1.7;
      font-size: 1em;
      margin: 15px 0 0 0;
    }
    
    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 15px;
      margin-bottom: 25px;
    }
    
    .stat-card {
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(15px);
      border-radius: 15px;
      padding: 20px;
      text-align: center;
    }
    
    .stat-number {
      font-size: 2.2em;
      font-weight: 700;
      color: #ffe66d;
      margin: 10px 0;
    }
    
    .stat-label {
      font-size: 0.9em;
      opacity: 0.9;
    }
    
    @media (max-width: 768px) {
      .announcement-header {
        flex-direction: column;
      }
      
      .announcement-meta {
        width: 100%;
        justify-content: flex-start;
      }
      
      .filter-bar {
        justify-content: flex-start;
      }
    }
  </style>
</head>
<body>





<div class="container">
  <a href="home.php" class="back-btn">← Back to Dashboard</a>
  
  <div class="header">
    <h1>📢 Announcements</h1>
    <p>Latest updates and important notices</p>
  </div>

  <div class="stats-container">
    <div class="stat-card">
      <div class="stat-number"><?php echo count($announcements); ?></div>
      <div class="stat-label">Total Announcements</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">
        <?php echo count(array_filter($announcements, function($a) { return $a['priority'] == 'Critical' || $a['priority'] == 'High'; })); ?>
      </div>
      <div class="stat-label">Important Updates</div>
    </div>
    <div class="stat-card">
      <div class="stat-number">
        <?php echo count(array_unique(array_column($announcements, 'type'))); ?>
      </div>
      <div class="stat-label">Categories</div>
    </div>
  </div>

  <div class="filter-bar">
    <button class="filter-btn active" onclick="filterAnnouncements('all')">All</button>
    <button class="filter-btn" onclick="filterAnnouncements('Critical')">🔴 Critical</button>
    <button class="filter-btn" onclick="filterAnnouncements('High')">🟠 High Priority</button>
  </div>

  <div id="announcements-list">
    <?php foreach ($announcements as $announcement): ?>
      <div class="announcement-card priority-<?php echo strtolower($announcement['priority']); ?>" 
           data-priority="<?php echo $announcement['priority']; ?>"
           data-type="<?php echo $announcement['type']; ?>">
        <div class="announcement-header">
          <div class="announcement-title">
            <span class="announcement-icon"><?php echo $announcement['icon']; ?></span>
            <h3 class="title-text"><?php echo $announcement['title']; ?></h3>
          </div>
          
          <div class="announcement-meta">
            <span class="type-badge"><?php echo $announcement['type']; ?></span>
            <span class="priority-badge priority-<?php echo strtolower($announcement['priority']); ?>">
              <?php echo $announcement['priority']; ?>
            </span>
            <span class="date-badge">
              📅 <?php echo date('M j, Y', strtotime($announcement['date'])); ?>
            </span>
          </div>
        </div>
        
        <div class="announcement-content">
          <?php echo $announcement['content']; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<script>
function filterAnnouncements(filter) {
  const cards = document.querySelectorAll('.announcement-card');
  const buttons = document.querySelectorAll('.filter-btn');
  
  // Update active button
  buttons.forEach(btn => btn.classList.remove('active'));
  event.target.classList.add('active');
  
  // Filter cards
  cards.forEach(card => {
    if (filter === 'all') {
      card.style.display = 'block';
    } else {
      const priority = card.getAttribute('data-priority');
      const type = card.getAttribute('data-type');
      
      if (priority === filter || type === filter) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    }
  });
}
</script>

</body>
</html>