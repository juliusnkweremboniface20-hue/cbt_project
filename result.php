<?php
session_start();
require_once 'db.php';

// Security check: Ensure student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'];
$reg_number = $_SESSION['reg_number'];

$score = 0;
$total_questions = 0;
$percentage = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Fetch all questions from the database
        $stmt = $pdo->query("SELECT * FROM questions");
        $questions = $stmt->fetchAll();
        $total_questions = count($questions);

        if ($total_questions > 0) {
            foreach ($questions as $q) {
                $q_id = $q['id'];
                
                // Dynamically check the correct answer column name
                $correct_ans = "";
                if (isset($q['correct_answer'])) {
                    $correct_ans = trim(strtoupper($q['correct_answer']));
                } elseif (isset($q['correct_option'])) {
                    $correct_ans = trim(strtoupper($q['correct_option']));
                }

                // Check if the student answered this question correctly
                if (isset($_POST["question_" . $q_id])) {
                    $student_ans = trim(strtoupper($_POST["question_" . $q_id]));
                    
                    if ($student_ans === $correct_ans) {
                        $score++;
                    }
                }
            }

            // Calculate percentage safely
            $percentage = ($score / $total_questions) * 100;
        }

        // 1. Save result into the database results table
        $subject = "Entrance Screening";
        $stmt_res = $pdo->prepare("INSERT INTO results (reg_number, subject, score, total_questions) VALUES (?, ?, ?, ?)");
        $stmt_res->execute([$reg_number, $subject, $score, $total_questions]);

        // 2. Update student status to 'Completed' so Admin Dashboard sees it immediately
        $stmt_status = $pdo->prepare("UPDATE students SET status = 'Completed' WHERE reg_number = ?");
        $stmt_status->execute([$reg_number]);

    } catch (PDOException $e) {
        // Handle database error if needed
    }
} else {
    // If accessed directly via URL without submitting the exam form, redirect to login
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exam Submitted - C.A.A.S.S Bukuru</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; background: #f2f4f8; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 420px; text-align: center; }
        .school-logo { width: 75px; height: 75px; object-fit: contain; margin-bottom: 10px; }
        h2 { color: #003366; font-size: 20px; margin: 0 0 5px 0; }
        h4 { color: #555; font-size: 13px; margin-top: 0; margin-bottom: 20px; }
        .success-box { background: #e8f5e9; border: 1px solid #c8e6c9; color: #2e7d32; padding: 15px; border-radius: 6px; margin-bottom: 20px; font-size: 15px; font-weight: bold; }
        .details { background: #f9f9f9; padding: 12px; border-radius: 6px; margin-bottom: 25px; text-align: left; font-size: 14px; color: #333; border: 1px solid #eee; }
        .details p { margin: 6px 0; }
        .logout-btn { background: #d9534f; color: white; border: none; padding: 12px; width: 100%; border-radius: 4px; font-size: 16px; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-block; box-sizing: border-box; }
        .logout-btn:hover { background: #c9302c; }
    </style>
</head>
<body>
    <div class="card">
        <img src="logo.png" alt="C.A.A.S.S Logo" class="school-logo">
        <h2>C.A.A.S.S BUKURU</h2>
        <h4>JSS1 Entrance Screening Examination</h4>
        
        <div class="success-box">
            Exam Submitted Successfully!
        </div>

        <div class="details">
            <p><strong>Candidate Name:</strong> <?= htmlspecialchars($student_name); ?></p>
            <p><strong>Registration No:</strong> <?= htmlspecialchars($reg_number); ?></p>
            <p style="margin-top: 12px; color: #555; font-style: italic; text-align: center;">
                Your responses have been securely recorded. Result slips will be processed and issued by the school administration.
            </p>
        </div>

        <a href="logout.php" class="logout-btn">Log Out</a>
    </div>
</body>
</html>