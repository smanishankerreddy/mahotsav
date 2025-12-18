<?php
session_start();
include '../includes/db.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Basic static credentials
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid admin credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🔐 Admin Login | Vignan Mahotsav</title>
  <style>
    :root {
      --primary: #ffe66d;
      --bg1: #1d2671;
      --bg2: #c33764;
      --shadow: rgba(0,0,0,0.4);
    }

    body {
      font-family: "Poppins", sans-serif;
      background: linear-gradient(135deg, var(--bg1), var(--bg2));
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .login-box {
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(12px);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 10px 25px var(--shadow);
      width: 350px;
      text-align: center;
      animation: fadeIn 0.6s ease-out;
    }

    h2 {
      color: var(--primary);
      margin-bottom: 25px;
      font-size: 1.8rem;
      text-shadow: 0 0 10px rgba(255,230,109,0.6);
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
      font-size: 14px;
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
      font-size: 15px;
      transition: 0.3s ease;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    button:hover {
      background: #fff;
      color: var(--bg1);
      transform: scale(1.05);
    }

    .error {
      color: #ff4d4d;
      font-weight: 600;
      margin-top: 15px;
      animation: shake 0.3s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
    }
  </style>
</head>
<body>

  <div class="login-box">
    <h2>🔐 Admin Login</h2>
    <form method="POST">
      <label>Username</label>
      <input type="text" name="username" required>

      <label>Password</label>
      <input type="password" name="password" required>

      <button type="submit" name="login">Login</button>
    </form>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
  </div>

</body>
</html>
