<?php
session_start();
include '../includes/db.php';
if (!isset($_SESSION['admin'])) header("Location: admin_login.php");

if (isset($_POST['add'])) {
  $name = $_POST['event_name'];
  $desc = $_POST['description'];
  $date = $_POST['date'];
  $time = $_POST['time'];
  $loc = $_POST['location'];
  $category = $_POST['category'];
  $conn->query("INSERT INTO events (event_name, description, date, time, location, category)
                VALUES ('$name','$desc','$date','$time','$loc', '$category')");


}

if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $conn->query("DELETE FROM events WHERE id=$id");
}

$events = $conn->query("SELECT * FROM events ORDER BY date");
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <style>
    /* General Body and Container Styling */
/* General Body and Container Styling */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f7f6;
    color: #333;
    line-height: 1.6;
    padding: 20px;
}

h2, h3 {
    color: #007bff;
    border-bottom: 2px solid #007bff;
    padding-bottom: 5px;
    margin-top: 30px;
    text-align: center; /* Center the headings too for better symmetry */
}

/* --- Form Styling (Updated for Centering) --- */

form {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 600px; /* Essential: Must have a defined max-width */
    margin-bottom: 30px;
    display: grid;
    gap: 15px;

    /* --- Centering Code --- */
    margin-left: auto;
    margin-right: auto;
    /* --- End Centering Code --- */
}

form > * {
    /* Target all direct children of the form */
    display: block;
}

/* Style for input fields and textareas */
input[type="text"],
input[type="date"],
input[type="time"],
label {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

label {
    font-weight: bold;
    color: #555;
    padding: 0;
    margin-bottom: -10px;
}

/* Specific styling for the placeholder within the category input */
input[name="category"]::placeholder {
    font-style: italic;
    color: #aaa;
}

/* Button Styling */
button[name="add"] {
    background-color: #28a745;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
    width: 100%;
    margin-top: 10px;
}

button[name="add"]:hover {
    background-color: #218838;
}

/* --- Table Styling (Existing Events) --- */

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #ffffff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

table th, table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #dee2e6;
}

/* Table Header */
table th {
    background-color: #007bff;
    color: white;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Alternating Row Colors (Zebra Stripping) for readability */
table tr:nth-child(even) {
    background-color: #f8f9fa;
}

table tr:hover {
    background-color: #e9ecef;
}

/* Action Link Styling */
table a {
    color: #dc3545;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease;
}

table a:hover {
    color: #c82333;
    text-decoration: underline;
}

/* Remove default border from the PHP-generated table */
table[border="1"] {
    border: none;
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




<h2>📅 Manage Events</h2>
<a href="index.php" class="back-btn">⬅ Back</a>
<form method="POST" center>
  Event Name: <input type="text" name="event_name" required><br>
  Description: <input type="text" name="description"><br>
  <label>Category:</label>
<input type="text" name="category" placeholder="Enter event category (e.g. Cultural, Technical)" required><br>

  Date: <input type="date" name="date"><br>
  Time: <input type="time" name="time"><br>
  Location: <input type="text" name="location"><br>
  <button name="add">Add Event</button>
</form>

<h3>Existing Events</h3>
<table border="1" cellpadding="10">
  <tr><th>ID</th><th>Name</th><th>Date</th><th>Time</th><th>Category</th><th>Location</th><th>Action</th></tr>
  <?php while($row = $events->fetch_assoc()) { ?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['event_name'] ?></td>
    <td><?= $row['date'] ?></td>
    <td><?= $row['time'] ?></td>
    <td><?= $row['category'] ?></td>
    <td><?= $row['location'] ?></td>
    <td><a href="?delete=<?= $row['id'] ?>">❌ Delete</a></td>
  </tr>
  <?php } ?>
</table>
