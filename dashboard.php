<?php
session_start();

// Security check: If the student hasn't logged in, block access and redirect to login
if (!isset($_SESSION['student_id']) || !isset($_SESSION['reg_number'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Exam Dashboard - C.A.A.S.S Bukuru</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f4f9; 
            margin: 0; 
            padding: 0; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
        }
        .card { 
            background: white; 
            padding: 40px; 
            border-radius: 8px; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
            width: 480px; 
            text-align: center; 
            box-sizing: border-box;
        }
        h2 { 
            color: #333; 
            margin-top: 0; 
            font-size: 24px; 
        }
        .welcome-box { 
            background: #e8f5e9; 
            padding: 20px; 
            border-radius: 6px; 
            margin: 20px 0; 
            border: 1px solid #c8e6c9; 
        }
        .student-name {
            color: #2e7d32;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        .reg-number {
            font-size: 14px; 
            color: #555; 
            font-family: monospace;
            background: #fff;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
            border: 1px solid #ddd;
        }
        p {
            color: #555;
            font-size: 14px;
            line-height: 1.5;
        }
        .btn-start { 
            display: block; 
            background: #007bff; 
            color: white; 
            padding: 14px; 
            text-decoration: none; 
            border-radius: 4px; 
            font-weight: bold; 
            margin-top: 25px; 
            font-size: 16px;
        }
        .btn-start:hover { 
            background: #0056b3; 
        }
        .btn-logout { 
            display: inline-block; 
            background: #dc3545; 
            color: white; 
            padding: 8px 16px; 
            text-decoration: none; 
            border-radius: 4px; 
            font-size: 13px; 
            margin-top: 20px; 
        }
        .btn-logout:hover { 
            background: #c82333; 
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>CBT Examination Portal</h2>
        
        <div class="welcome-box">
            <span style="font-size: 13px; color: #666; text-transform: uppercase; letter-spacing: 1px;">Authenticated Candidate</span>
            <span class="student-name"><?php echo htmlspecialchars($_SESSION['student_name']); ?></span>
            <div class="reg-number">Reg No: <?php echo htmlspecialchars($_SESSION['reg_number']); ?></div>
        </div>

        <p>You are successfully logged in for the JSS1 Entrance Screening Examination. Click the button below whenever you are ready to begin your test.</p>

        <!-- Directs to your examination question environment -->
        <a href="exam.php" class="btn-start">Start Examination</a>

        <br>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
</body>
</html>