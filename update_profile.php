<?php
session_start();
include 'includes/db.php';

// ✅ Check login
if (!isset($_SESSION['user'])) {
    die("<p>⚠️ Please login first. <a href='login.php'>Login Here</a></p>");
}

$user_id = $_SESSION['user']['id'];

// ✅ Update profile
$updated = false;
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $branch = $_POST['branch'];

    $sql = "UPDATE students SET name='$name', branch='$branch' WHERE id='$user_id'";
    if ($conn->query($sql)) {
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['branch'] = $branch;
        $updated = true; // flag for popup
    } else {
        echo "<script>alert('❌ Error: " . $conn->error . "');</script>";
    }
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Profile | Vignan Mahotsav</title>
  <style>
    :root {
      --primary: #ffe66d;
      --bg-grad-start: #1d2671;
      --bg-grad-end: #c33764;
      --white: #fff;
      --shadow: rgba(0, 0, 0, 0.3);
    }

    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end)) fixed no-repeat;
      background-size: cover;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      color: var(--white);
    }

    .profile-box {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(12px);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 10px 25px var(--shadow);
      width: 350px;
      text-align: center;
      position: relative;
    }

    h2 {
      color: var(--primary);
      margin-bottom: 25px;
    }

    label {
      display: block;
      text-align: left;
      margin: 10px 0 5px;
      font-weight: 500;
    }

    input {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 8px;
      outline: none;
      background: rgba(255,255,255,0.9);
      color: #000;
    }

    button {
      width: 100%;
      padding: 12px;
      margin-top: 20px;
      border: none;
      border-radius: 8px;
      background: var(--primary);
      color: #000;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s ease;
    }

    button:hover {
      background: #fff;
      color: var(--bg-grad-start);
      transform: scale(1.03);
    }

    /* ✅ Back Button */
    .back-btn {
      display: inline-block;
      position: absolute;
      top: -60px;
      left: 0;
      background: rgba(255,255,255,0.2);
      color: var(--white);
      text-decoration: none;
      padding: 8px 15px;
      border-radius: 8px;
      font-weight: 500;
      transition: 0.3s ease;
    }

    .back-btn:hover {
      background: var(--primary);
      color: #000;
    }

    /* ✅ Popup */
    .popup {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .popup-content {
      background: #fff;
      color: #000;
      padding: 20px 30px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.4);
      font-size: 16px;
      font-weight: 600;
      animation: pop 0.3s ease-out;
    }

    @keyframes pop {
      from { transform: scale(0.8); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }
  </style>
</head>
<body>

<div class="profile-box">
  <a href="home.php" class="back-btn">← Back to Dashboard</a>   
  <h2>👤 Update Profile</h2>
  <form method="post">
    <label>Name</label>
    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

    <label>Branch</label>
    <input type="text" name="branch" value="<?= htmlspecialchars($user['branch']) ?>" required>

    <button type="submit" name="update">Update</button>
  </form>
</div>

<!-- ✅ Popup Box -->
<div id="popup" class="popup">
  <div class="popup-content">
    <p id="popup-message"></p>
  </div>
</div>

<?php if ($updated): ?>
<script>
  const popup = document.getElementById("popup");
  const msg = document.getElementById("popup-message");
  msg.textContent = "✅ Profile updated successfully!";
  popup.style.display = "flex";
  setTimeout(() => { popup.style.display = "none"; }, 2000);
</script>
<?php endif; ?>

</body>
</html>
