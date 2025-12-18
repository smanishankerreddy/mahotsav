<?php
session_start();
include '../includes/db.php';
if (!isset($_SESSION['admin'])) header("Location: admin_login.php");

if (isset($_POST['add'])) {
  $event_id = $_POST['event_id'];
  $name = $_POST['participant_name'];
  $pos = $_POST['position'];
  $conn->query("INSERT INTO results (event_id, participant_name, position)
                VALUES ('$event_id','$name','$pos')");
}

if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $conn->query("DELETE FROM results WHERE id=$id");
}

$events = $conn->query("SELECT id, event_name FROM events");
$results = $conn->query("SELECT r.*, e.event_name FROM results r JOIN events e ON r.event_id=e.id");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>🏆 Manage Results | Vignan Mahotsav</title>
  <style>
  /* Page layout */
  body {
    font-family: "Poppins", sans-serif;
    background: linear-gradient(135deg, #141e30, #243b55);
    color: #fff;
    margin: 0;
    padding: 40px;
  }

  h2, h3 {
    text-align: center;
    color: #00e6e6;
    text-shadow: 0 0 10px rgba(0, 230, 230, 0.5);
  }

  /* Form styling */
  form {
    background: rgba(255, 255, 255, 0.1);
    padding: 25px;
    border-radius: 15px;
    width: 400px;
    margin: 20px auto;
    box-shadow: 0 0 20px rgba(0, 255, 255, 0.2);
    backdrop-filter: blur(10px);
  }

  form select,
  form input {
    width: 100%;
    padding: 10px;
    margin: 10px 0 15px 0;
    border: none;
    border-radius: 10px;
    font-size: 15px;
  }

  form button {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    background: linear-gradient(90deg, #00e6e6, #0077ff);
    color: #000;
    transition: 0.3s;
  }

  form button:hover {
    background: linear-gradient(90deg, #0077ff, #00e6e6);
    transform: scale(1.05);
  }

  /* Table styling */
  table {
    width: 90%;
    margin: 40px auto;
    border-collapse: collapse;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 0 15px rgba(0, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
  }

  th, td {
    padding: 12px;
    text-align: center;
  }

  th {
    background: rgba(0, 230, 230, 0.3);
    font-weight: 700;
    color: #00e6e6;
  }

  tr:nth-child(even) {
    background: rgba(255, 255, 255, 0.05);
  }

  tr:hover {
    background: rgba(0, 230, 230, 0.1);
    transition: 0.3s;
  }

  a {
    text-decoration: none;
    color: #ff4d4d;
    font-weight: bold;
    transition: 0.2s;
  }

  a:hover {
    color: #ff0000;
    text-shadow: 0 0 5px #ff0000;
  }
  .back-btn {
  display: inline-block;
  margin: 15px 0 25px 60px;
  padding: 10px 20px;
  font-size: 16px;
  font-weight: 600;
  border-radius: 10px;
  background: linear-gradient(90deg, #00e6e6, #0077ff);
  color: #000;
  text-decoration: none;
  transition: 0.3s ease;
  box-shadow: 0 0 15px rgba(0, 230, 230, 0.5);
}

.back-btn:hover {
  background: linear-gradient(90deg, #0077ff, #00e6e6);
  transform: scale(1.05);
  box-shadow: 0 0 20px rgba(0, 230, 230, 0.8);
}

</style>
</head>

<body>

<h2>🏆 Manage Results</h2>
<a href="index.php" class="back-btn">⬅ Back</a>

<form method="POST">
  Event:
  <select name="event_id">
    <?php while($e = $events->fetch_assoc()) { ?>
      <option value="<?= $e['id'] ?>"><?= $e['event_name'] ?></option>
    <?php } ?>
  </select><br>
  Participant Name: <input type="text" name="participant_name"><br>
  Position: <input type="text" name="position"><br>
  <button name="add">Add Result</button>
</form>

<h3>All Results</h3>
<table border="1" cellpadding="10">
<tr><th>ID</th><th>Event</th><th>Participant</th><th>Position</th><th>Action</th></tr>
<?php while($r = $results->fetch_assoc()) { ?>
<tr>
  <td><?= $r['id'] ?></td>
  <td><?= $r['event_name'] ?></td>
  <td><?= $r['participant_name'] ?></td>
  <td><?= $r['position'] ?></td>
  <td><a href="?delete=<?= $r['id'] ?>">❌ Delete</a></td>
</tr>
<?php } ?>
</table>
</body>
</html>