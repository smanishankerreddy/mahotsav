<?php
include 'includes/db.php';
$result = $conn->query("SELECT * FROM events ORDER BY date, time");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Event Schedule</title>
<style>
:root{
  --bg-grad-start: #1d2671;
  --bg-grad-end: #c33764;
  --accent: #ffe66d;
  --muted: rgba(255,255,255,0.9);
}

body{
  margin:0;
  font-family: "Poppins", sans-serif;
  background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end)) fixed no-repeat;
background-size: cover;

  color: var(--muted);
  padding:30px;
}

h2{
  text-align:center;
  color: var(--accent);
  margin-bottom:20px;
  font-size:2rem;
  text-shadow: 0 3px 10px rgba(0,0,0,0.3);
}

.schedule-container{
  max-width: 900px;
  margin: auto;
  background: rgba(255,255,255,0.1);
  border-radius: 12px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.3);
  padding:20px;
  backdrop-filter: blur(6px);
}

table{
  width:100%;
  border-collapse: collapse;
  margin-top:10px;
}

th, td{
  padding:12px 15px;
  text-align:left;
}

th{
  background-color: var(--accent);
  color: #1d2671;
  font-weight:600;
}

tr:nth-child(even){
  background-color: rgba(255,255,255,0.07);
}

tr:hover{
  background-color: rgba(255,255,255,0.15);
  transition:0.2s;
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
</style>
</head>
<body>

<div class="schedule-container">
  <h2>📅 Event Schedule</h2>
  <a href="home.php" class="back-btn">← Back to Dashboard</a>
  <table>
    <tr>
      <th>Event</th>
      <th>Description</th>
      <th>Date</th>
      <th>Time</th>
      <th>Location</th>
    </tr>
    <?php while($row = $result->fetch_assoc()) { ?>
    <tr>
      <td><?= htmlspecialchars($row['event_name']) ?></td>
      <td><?= htmlspecialchars($row['description']) ?></td>
      <td><?= htmlspecialchars($row['date']) ?></td>
      <td><?= htmlspecialchars($row['time']) ?></td>
      <td><?= htmlspecialchars($row['location']) ?></td>
    </tr>
    <?php } ?>
  </table>
</div>

</body>
</html>
