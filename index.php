<?php
session_start();
require_once 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $reg_number = trim($_POST['reg_number']);

    // Strict validation against the official pre-registered school list
    $stmt = $pdo->prepare("SELECT * FROM students WHERE fullname = ? AND reg_number = ?");
    $stmt->execute([$fullname, $reg_number]);
    $student = $stmt->fetch();

    if ($student) {
        if ($student['status'] == 'Completed') {
            $error = "This candidate has already completed the screening examination.";
        } else {
            $_SESSION['student_id'] = $student['id'];
            $_SESSION['student_name'] = $student['fullname'];
            $_SESSION['reg_number'] = $student['reg_number'];
            
            header("Location: exam.php");
            exit();
        }
    } else {
        $error = "Access Denied: This full name or registration number is not on the official school screening list.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>C.A.A.S.S Bukuru - JSS1 Entrance CBT Portal</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; background: #f2f4f8; margin: 0; display: flex; flex-direction: column; height: 100vh; }
        
        /* Top Navigation Bar */
        .top-navbar { background: #003366; color: white; display: flex; justify-content: space-between; align-items: center; padding: 12px 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .top-navbar .brand { font-size: 16px; font-weight: bold; letter-spacing: 0.5px; }
        .nav-links a { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; padding: 6px 12px; border-radius: 4px; transition: background 0.2s; }
        .nav-links a:hover, .nav-links a.active { background: rgba(255,255,255,0.15); }
        
        /* Main Container Centering */
        .main-container { flex: 1; display: flex; justify-content: center; align-items: center; }
        
        /* Login Card */
        .login-card { background: #fff; padding: 30px 35px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 380px; text-align: center; }
        .school-logo { width: 75px; height: 75px; object-fit: contain; margin-bottom: 10px; }
        .login-card h2 { color: #003366; font-size: 20px; margin: 0 0 5px 0; }
        .login-card h4 { color: #555; font-size: 13px; margin-top: 0; margin-bottom: 20px; }
        
        label { display: block; text-align: left; font-weight: bold; margin-bottom: 5px; color: #333; font-size: 14px; }
        input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
        
        button { background: #003366; color: white; border: none; padding: 12px; width: 100%; border-radius: 4px; font-size: 16px; cursor: pointer; font-weight: bold; }
        button:hover { background: #002244; }
        
        .error { color: #d9534f; background: #fdf7f7; border: 1px solid #f5c6cb; padding: 10px; border-radius: 4px; font-size: 13px; margin-bottom: 15px; text-align: left; }
        .info-note { font-size: 12px; color: #666; margin-top: 15px; border-top: 1px solid #eee; padding-top: 10px; text-align: left; }
    </style>
</head>
<body>

    <!-- Top Navigation Bar for Seamless Portal Switching -->
    <div class="top-navbar">
        <div class="brand">C.A.A.S.S BUKURU - CBT PORTAL</div>
        <div class="nav-links">
            <a href="index.php" class="active">Candidate Portal</a>
            <a href="admin_login.php">Admin Portal</a>
        </div>
    </div>

    <div class="main-container">
        <div class="login-card">
            <img src="logo.png" alt="C.A.A.S.S Logo" class="school-logo">
            <h2>C.A.A.S.S BUKURU</h2>
            <h4>JSS1 Entrance Screening Examination</h4>
            
            <?php if($error): ?>
                <div class="error"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <label>Official Full Name:</label>
                <input type="text" name="fullname" placeholder="e.g. Aisha Bello" required>
                
                <label>Registration Number:</label>
                <input type="text" name="reg_number" placeholder="e.g. CAASS/JSS1/2026/001" required>
                
                <button type="submit">Proceed to Exam</button>
            </form>

            <div class="info-note">
                <strong>Notice:</strong> Only officially pre-registered candidates can access the screening examination.
            </div>
        </div>
    </div>

</body>
</html>