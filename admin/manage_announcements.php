<?php
session_start();
include '../includes/db.php';
if (!isset($_SESSION['admin'])) header("Location: admin_login.php");

// 🟢 Add announcement
if (isset($_POST['add'])) {
  $title = $_POST['title'] ?? 'Sample Title';
  $content = $_POST['content'] ?? 'Default content';
  $type = $_POST['type'] ?? 'General';
  $priority = $_POST['priority'] ?? 'Low';
  $icon = $_POST['icon'] ?? '📢';
  $date = $_POST['date'] ?? date('Y-m-d');

  $conn->query("INSERT INTO announcements (title, content, type, priority, icon, date)
                VALUES ('$title', '$content', '$type', '$priority', '$icon', '$date')");
}

// 🗑️ Delete announcement
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $conn->query("DELETE FROM announcements WHERE id = $id");
  header("Location: manage_announcements.php?msg=deleted");
  exit();
}

$ann = $conn->query("SELECT * FROM announcements ORDER BY date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>📢 Manage Announcements</title>
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: linear-gradient(135deg, #f7f9fc, #e0e7ff);
      color: #333;
      margin: 0;
      padding: 30px;
      text-align: center;
    }

    h2 {
      color: #1d2671;
      text-shadow: 0 0 8px rgba(0,0,0,0.1);
    }

    form {
      background: #fff;
      width: 70%;
      margin: 30px auto;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      text-align: left;
    }

    input, textarea, select {
      width: 100%;
      margin-bottom: 15px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      outline: none;
      font-size: 15px;
      transition: 0.3s;
    }

    input:focus, textarea:focus, select:focus {
      border-color: #1d2671;
      box-shadow: 0 0 8px rgba(29,38,113,0.3);
    }

    button {
      width: 100%;
      padding: 12px;
      background: linear-gradient(90deg, #1d2671, #c33764);
      color: #fff;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      font-size: 16px;
      transition: 0.3s;
    }

    button:hover {
      transform: scale(1.03);
      background: linear-gradient(90deg, #c33764, #1d2671);
      box-shadow: 0 4px 15px rgba(195,55,100,0.4);
    }

    table {
      width: 90%;
      margin: 30px auto;
      border-collapse: collapse;
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    th, td {
      padding: 12px 15px;
      text-align: center;
      border-bottom: 1px solid #eee;
    }

    th {
      background-color: #1d2671;
      color: #fff;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    tr:hover {
      background-color: #f1f5ff;
    }

    a {
      color: #c33764;
      text-decoration: none;
      font-weight: 600;
    }

    a:hover {
      color: #ff004c;
    }

    /* Back Button */
    .back-btn {
      display: inline-block;
      background: linear-gradient(90deg, #1d2671, #c33764);
      color: #fff;
      text-decoration: none;
      font-weight: 600;
      padding: 10px 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      transition: 0.3s;
      box-shadow: 0 4px 12px rgba(29,38,113,0.3);
    }

    .back-btn:hover {
      background: linear-gradient(90deg, #c33764, #1d2671);
      transform: scale(1.05);
    }

    h3 {
      margin-top: 50px;
      color: #1d2671;
    }
  </style>
</head>
<body>

  <h2>📢 Manage Announcements</h2>
  <a href="index.php" class="back-btn">⬅ Back</a>

  <form method="POST" action="">
    <input type="text" name="title" placeholder="Title" required>
    <textarea name="content" placeholder="Content" required></textarea>
    <input type="text" name="type" placeholder="Type (e.g. Event Update, Results, Safety)">
    <select name="priority">
      <option>Low</option>
      <option>Medium</option>
      <option>High</option>
      <option>Critical</option>
    </select>
    <input type="text" name="icon" placeholder="Icon (e.g. 📢, 🎉, 🏆)" maxlength="5">
    <input type="date" name="date">
    <button type="submit" name="add">Add Announcement</button>
  </form>

  <h3>📋 Existing Announcements</h3>
  <table>
    <tr>
      <th>ID</th>
      <th>Icon</th>
      <th>Title</th>
      <th>Type</th>
      <th>Priority</th>
      <th>Date</th>
      <th>Message</th>
      <th>Action</th>
    </tr>

    <?php while ($row = $ann->fetch_assoc()) { ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['icon']) ?></td>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['type']) ?></td>
        <td><?= htmlspecialchars($row['priority']) ?></td>
        <td><?= htmlspecialchars($row['date']) ?></td>
        <td><?= nl2br(htmlspecialchars($row['content'])) ?></td>
        <td>
          <a href="manage_announcements.php?delete=<?= $row['id']; ?>" 
             onclick="return confirm('Are you sure you want to delete this announcement?');">❌ Delete</a>
        </td>
      </tr>
    <?php } ?>
  </table>

</body>
</html>
