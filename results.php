<?php
include("includes/db.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>🏆 Festival Results | Vignan Mahotsav</title>
  <style>
    :root {
      --primary: #ffe66d;
      --bg1: #1d2671;
      --bg2: #c33764;
      --shadow: rgba(0, 0, 0, 0.3);
    }

    body {
      font-family: "Poppins", sans-serif;
      background: linear-gradient(135deg, var(--bg1), var(--bg2));
      color: #fff;
      margin: 0;
      padding: 50px 20px;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h2 {
      text-align: center;
      color: var(--primary);
      margin-bottom: 30px;
      font-size: 2rem;
      text-shadow: 0 0 10px rgba(255, 230, 109, 0.6);
    }

    table {
      width: 85%;
      border-collapse: collapse;
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 25px var(--shadow);
      animation: fadeIn 0.6s ease-out;
    }

    th, td {
      padding: 14px 18px;
      text-align: center;
      font-size: 15px;
    }

    th {
      background-color: var(--primary);
      color: var(--bg1);
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    tr:nth-child(even) {
      background-color: rgba(255, 255, 255, 0.08);
    }

    tr:hover {
      background-color: rgba(255, 255, 255, 0.18);
      transform: scale(1.01);
      transition: 0.3s ease;
    }

    .back-btn {
      display: inline-block;
      background: rgba(255,255,255,0.2);
      color: var(--primary);
      padding: 12px 25px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 500;
      margin-top: 35px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
      transition: 0.3s ease;
    }

    .back-btn:hover {
      background: var(--primary);
      color: var(--bg1);
      transform: translateY(-3px);
    }

    .no-results {
      font-size: 1.2rem;
      text-align: center;
      color: var(--primary);
      background: rgba(255, 255, 255, 0.1);
      padding: 20px 40px;
      border-radius: 10px;
      box-shadow: 0 4px 15px var(--shadow);
      animation: pop 0.5s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pop {
      from { transform: scale(0.9); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }
  </style>
</head>
<body>

<h2>🏆 Festival Results</h2>

<?php
$sql = "SELECT results.*, events.event_name 
        FROM results 
        JOIN events ON results.event_id = events.id 
        ORDER BY events.event_name ASC, position ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<table>
          <tr>
            <th>Event</th>
            <th>Participant Name</th>
            <th>Position</th>
          </tr>";
  while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>" . htmlspecialchars($row['event_name']) . "</td>
            <td>" . htmlspecialchars($row['participant_name']) . "</td>
            <td>" . htmlspecialchars($row['position']) . "</td>
          </tr>";
  }
  echo "</table>";
} else {
  echo "<div class='no-results'>🚫 No results announced yet.</div>";
}
?>

<a href='home.php' class='back-btn'>← Back to Dashboard</a>

</body>
</html>
