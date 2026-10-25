<?php
session_start();
require_once 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Simple secure admin check (For project prototype, default is admin / admin123)
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid Administrator Username or Password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - C.A.A.S.S Bukuru</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; background: #f2f4f8; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: #fff; padding: 30px 35px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 380px; text-align: center; }
        .school-logo { width: 75px; height: 75px; object-fit: contain; margin-bottom: 10px; }
        .login-card h2 { color: #003366; font-size: 20px; margin: 0 0 5px 0; }
        .login-card h4 { color: #555; font-size: 13px; margin-top: 0; margin-bottom: 20px; }
        label { display: block; text-align: left; font-weight: bold; margin-bottom: 5px; color: #333; font-size: 14px; }
        input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
        button { background: #003366; color: white; border: none; padding: 12px; width: 100%; border-radius: 4px; font-size: 16px; cursor: pointer; font-weight: bold; }
        button:hover { background: #002244; }
        .error { color: #d9534f; background: #fdf7f7; border: 1px solid #f5c6cb; padding: 10px; border-radius: 4px; font-size: 13px; margin-bottom: 15px; text-align: left; }
    </style>
</head>
<body>
    <div class="login-card">
        <img src="logo.png" alt="C.A.A.S.S Logo" class="school-logo">
        <h2>C.A.A.S.S BUKURU</h2>
        <h4>Administrator Portal</h4>
        
        <?php if($error): ?>
            <div class="error"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Admin Username:</label>
            <input type="text" name="username" placeholder="Enter admin username" required>
            
            <label>Password:</label>
            <input type="password" name="password" placeholder="Enter admin password" required>
            
            <button type="submit">Login to Admin</button>
        </form>
    </div>
</body>
</html>