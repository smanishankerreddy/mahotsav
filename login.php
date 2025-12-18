<?php include("includes/db.php"); session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vignan Mahotsav | Login</title>
  <style>
    * { box-sizing: border-box; font-family: "Poppins", sans-serif; }
    body {
      margin: 0;
      background: linear-gradient(135deg, #1d2671, #c33764);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      color: #fff;
    }
    .login-box {
      background: rgba(255,255,255,0.1);
      backdrop-filter: blur(12px);
      border-radius: 20px;
      padding: 40px 45px;
      width: 380px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    }
    h2 { text-align: center; color: #ffe66d; margin-bottom: 25px; }
    label { font-size: 14px; font-weight: 500; display: block; margin-top: 10px; }
    input {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: none;
      outline: none;
      margin-top: 5px;
      background: rgba(255,255,255,0.9);
      color: #000;
    }
    button {
      width: 100%;
      padding: 12px;
      background: #ffe66d;
      color: #000;
      font-weight: 600;
      border: none;
      border-radius: 8px;
      margin-top: 20px;
      cursor: pointer;
      transition: 0.3s ease;
    }
    button:hover {
      background: #fff;
      color: #1d2671;
      transform: scale(1.03);
    }
    .extra-links {
      text-align: center;
      margin-top: 15px;
    }
    .extra-links a {
      color: #ffe66d;
      text-decoration: none;
    }
    .extra-links a:hover { text-decoration: underline; }
  </style>
</head>
<body>

<div class="login-box">
  <h2>Student Login</h2>
  <form method="POST" action="">
    <label>RegNo / MHid</label>
    <input type="text" name="regno" placeholder="Enter RegNo or MHid" required>

    <label>Date of Birth</label>
    <input type="date" name="dob" required>

    <button type="submit" name="login">Login</button>
    <button type="reset">Reset</button>

    <div class="extra-links">
      <p>Don’t have an account? <a href="register.php">Create one</a></p>
    </div>
  </form>

  <?php
if (isset($_POST['login'])) {
  $regOrMh = trim($_POST['regno']); // can be RegNo or MHID
  $dob = trim($_POST['dob']);

  $stmt = $conn->prepare("SELECT * FROM students WHERE (regno=? OR mh_id=?) AND dob=?");
  $stmt->bind_param("sss", $regOrMh, $regOrMh, $dob);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 1) {
    session_start();
    $_SESSION['user'] = $result->fetch_assoc();
    echo "<script>
            alert('✅ Login Successful!');
            window.location.href = 'home.php';
          </script>";
  } else {
    echo "<script>alert('❌ Invalid RegNo/MHID or Date of Birth');</script>";
  }
}
?>

</div>

</body>
</html>
